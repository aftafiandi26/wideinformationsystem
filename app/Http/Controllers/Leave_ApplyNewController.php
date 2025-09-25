<?php

namespace App\Http\Controllers;

use App\Services\LeaveBalanceService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Leave_ApplyNewController extends Controller
{
    protected $leaveBalanceService;

    public function __construct(LeaveBalanceService $leaveBalanceService)
    {
        $this->middleware(['auth', 'active']);
        $this->leaveBalanceService = $leaveBalanceService;
    }

    /**
     * Display new leave application form
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        
        // Get user's leave balance information
        $leaveBalance = $this->getUserLeaveBalance($userId, $user);
        
        return view('leave.applyNew.index', [
            'user' => $user,
            'leaveBalance' => $leaveBalance
        ]);
    }

    /**
     * Get user's complete leave balance information
     * 
     * @param int $userId
     * @param User $user
     * @return array
     */
    public function getUserLeaveBalance($userId, $user)
    {
        // Get annual leave taken
        $annualTaken = $this->leaveBalanceService->getAnnualLeaveTaken($userId);
        
        // Calculate annual leave balance
        $annualBalance = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $annualTaken);
        
        // Calculate exdo balance
        $exdoBalance = $this->leaveBalanceService->calculateExdoBalance($userId);
        
        // Calculate forfeited leave
        $forfeitedData = $this->leaveBalanceService->calculateForfeitedLeave($userId);
        
        // Calculate final balance
        $finalBalance = $this->leaveBalanceService->calculateFinalBalance(
            $annualBalance, 
            $forfeitedData, 
            $user->emp_status
        );
        
        return [
            'annual_taken' => $annualTaken,
            'annual_balance' => $annualBalance,
            'exdo_balance' => $exdoBalance,
            'forfeited_data' => $forfeitedData,
            'final_balance' => $finalBalance,
            'summary' => [
                'total_annual_available' => $annualBalance['total_annual'],
                'total_annual_permanent' => $annualBalance['total_annual_permanent'],
                'remaining_exdo' => $exdoBalance['remaining_exdo'],
                'renew_permanent' => $finalBalance['renew_permanent'],
                'renew_contract' => $finalBalance['renew_contract']
            ]
        ];
    }

    /**
     * Get leave balance data for AJAX requests
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveBalanceData()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        
        $leaveBalance = $this->getUserLeaveBalance($userId, $user);
        
        return response()->json([
            'success' => true,
            'data' => $leaveBalance
        ]);
    }

    /**
     * Validate leave application
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateLeaveApplication(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:annual,exdo',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|integer|min:1'
        ]);

        $userId = Auth::user()->id;
        $user = User::find($userId);
        $leaveType = $request->leave_type;
        $totalDays = $request->total_days;
        
        // Get current leave balance
        $leaveBalance = $this->getUserLeaveBalance($userId, $user);
        
        $isValid = false;
        $message = '';
        $availableDays = 0;
        
        if ($leaveType === 'annual') {
            if ($user->emp_status === 'Permanent') {
                $availableDays = $leaveBalance['final_balance']['renew_permanent'];
            } else {
                $availableDays = $leaveBalance['final_balance']['renew_contract'];
            }
            
            if ($totalDays <= $availableDays) {
                $isValid = true;
                $message = 'Annual leave application is valid';
            } else {
                $message = 'Insufficient annual leave balance. Available: ' . $availableDays . ' days';
            }
        } elseif ($leaveType === 'exdo') {
            $availableDays = $leaveBalance['exdo_balance']['remaining_exdo'];
            
            if ($totalDays <= $availableDays) {
                $isValid = true;
                $message = 'Exdo application is valid';
            } else {
                $message = 'Insufficient exdo balance. Available: ' . $availableDays . ' days';
            }
        }
        
        return response()->json([
            'success' => $isValid,
            'message' => $message,
            'available_days' => $availableDays,
            'requested_days' => $totalDays,
            'leave_type' => $leaveType
        ]);
    }

    /**
     * Get leave categories for dropdown
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveCategories()
    {
        $categories = DB::table('leave_category')
            ->select('id', 'name', 'description')
            ->where('active', 1)
            ->orderBy('name')
            ->get();
        
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }

    /**
     * Get user's leave history
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveHistory(Request $request)
    {
        $userId = Auth::user()->id;
        $limit = $request->get('limit', 10);
        $offset = $request->get('offset', 0);
        
        $leaveHistory = DB::table('leave_transaction as lt')
            ->leftJoin('leave_category as lc', 'lc.id', '=', 'lt.leave_category_id')
            ->select([
                'lt.id',
                'lt.start_date',
                'lt.end_date',
                'lt.total_day',
                'lt.reason',
                'lt.formStat',
                'lc.name as category_name',
                'lt.created_at'
            ])
            ->where('lt.user_id', $userId)
            ->orderBy('lt.created_at', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $totalCount = DB::table('leave_transaction')
            ->where('user_id', $userId)
            ->count();
        
        return response()->json([
            'success' => true,
            'data' => $leaveHistory,
            'total_count' => $totalCount,
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    /**
     * Get leave balance summary for dashboard
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveBalanceSummary()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        
        $leaveBalance = $this->getUserLeaveBalance($userId, $user);
        
        $summary = [
            'employee_status' => $user->emp_status,
            'annual_leave' => [
                'taken' => $leaveBalance['annual_taken'],
                'available' => $user->emp_status === 'Permanent' 
                    ? $leaveBalance['final_balance']['renew_permanent']
                    : $leaveBalance['final_balance']['renew_contract'],
                'total_entitled' => $user->initial_annual
            ],
            'exdo' => [
                'total_owned' => $leaveBalance['exdo_balance']['total_exdo']->sum(),
                'taken' => $leaveBalance['exdo_balance']['exdo_taken']->sum(),
                'expired' => $leaveBalance['exdo_balance']['expired_exdo']->sum(),
                'available' => $leaveBalance['exdo_balance']['remaining_exdo']
            ],
            'forfeited' => [
                'total' => $leaveBalance['forfeited_data']['forfeited']->sum(),
                'counted' => $leaveBalance['forfeited_data']['forfeited_counts']->sum(),
                'remaining' => $leaveBalance['forfeited_data']['valid_forfeited']
            ]
        ];
        
        return response()->json([
            'success' => true,
            'summary' => $summary
        ]);
    }

    /**
     * Check if user can apply for leave
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function canApplyLeave(Request $request)
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        
        // Check if user is active
        if (!$user->active) {
            return response()->json([
                'success' => false,
                'message' => 'User account is not active'
            ]);
        }
        
        // Check if user has valid employment status
        if (!in_array($user->emp_status, ['Permanent', 'Contract', 'PKL'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid employment status'
            ]);
        }
        
        // PKL employees cannot apply for annual leave
        if ($user->emp_status === 'PKL' && $request->leave_type === 'annual') {
            return response()->json([
                'success' => false,
                'message' => 'PKL employees cannot apply for annual leave'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'User can apply for leave'
        ]);
    }

    /**
     * Store new leave application
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLeaveApplication(Request $request)
    {
        $request->validate([
            'leave_category_id' => 'required|integer|exists:leave_category,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_days' => 'required|integer|min:1',
            'reason' => 'required|string|max:500'
        ]);

        $userId = Auth::user()->id;
        $user = User::find($userId);
        
        // Validate leave balance before storing
        $leaveBalance = $this->getUserLeaveBalance($userId, $user);
        $leaveCategoryId = $request->leave_category_id;
        
        $isValid = false;
        $availableDays = 0;
        
        if ($leaveCategoryId == 1) { // Annual leave
            if ($user->emp_status === 'Permanent') {
                $availableDays = $leaveBalance['final_balance']['renew_permanent'];
            } else {
                $availableDays = $leaveBalance['final_balance']['renew_contract'];
            }
        } elseif ($leaveCategoryId == 2) { // Exdo
            $availableDays = $leaveBalance['exdo_balance']['remaining_exdo'];
        }
        
        if ($request->total_days <= $availableDays) {
            $isValid = true;
        }
        
        if (!$isValid) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient leave balance. Available: ' . $availableDays . ' days'
            ]);
        }
        
        // Store leave application
        $leaveId = DB::table('leave_transaction')->insertGetId([
            'user_id' => $userId,
            'leave_category_id' => $leaveCategoryId,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_day' => $request->total_days,
            'reason' => $request->reason,
            'formStat' => 0, // Pending approval
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Leave application submitted successfully',
            'leave_id' => $leaveId
        ]);
    }

    /**
     * Get user's pending leave applications
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPendingApplications()
    {
        $userId = Auth::user()->id;
        
        $pendingApplications = DB::table('leave_transaction as lt')
            ->leftJoin('leave_category as lc', 'lc.id', '=', 'lt.leave_category_id')
            ->select([
                'lt.id',
                'lt.start_date',
                'lt.end_date',
                'lt.total_day',
                'lt.reason',
                'lt.formStat',
                'lc.name as category_name',
                'lt.created_at'
            ])
            ->where('lt.user_id', $userId)
            ->where('lt.formStat', 0) // Pending
            ->orderBy('lt.created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $pendingApplications
        ]);
    }
}
