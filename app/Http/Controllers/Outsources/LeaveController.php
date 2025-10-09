<?php

namespace App\Http\Controllers\Outsources;

use App\Dept_Category;
use App\Mail\Outsource\Form\Leave\SendingApplyingLeaveMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leave;
use App\Leave_Category;
use App\Services\LeaveBalanceOutsourceService;
use App\Services\ProvincesService;
use App\Services\ApprovalLeaveServices;
use App\Services\HolidayService;
use App\Services\EtcLeaveServices;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active', 'outsource']);
    }

    public function indexNewApplyOutsource()
    {
        $leaveBalanceOutsourceService = new LeaveBalanceOutsourceService();
        $userId = auth()->user()->id;
        
        // Get comprehensive outsource leave information
        $leaveInfo = $leaveBalanceOutsourceService->getOutsourceLeaveInfo($userId);
        
        // Prepare data for view
        $user = $leaveInfo['user'];
        $annualTaken = $leaveInfo['annual_taken'];
        $exdoBalance = $leaveInfo['exdo_balance'];
        $forfeitedData = $leaveInfo['forfeited_data'];
        $leaveBalance = $leaveInfo['leave_balance'];
        $finalBalance = $leaveInfo['final_balance'];
        $monthlyProgression = $leaveInfo['monthly_progression'];
        
        // Create annual object for backward compatibility
        $annual = (object) ['transactionAnnual' => $annualTaken];
        
        // Calculate year information
        $startYear = $leaveBalance['start_year'];
        $currentYear = $leaveBalance['current_year'];

        return view('leave.outsources.leave.applyingLeave.indexNewAnnualOutsource', [
            'annual' => $annual,
            'totalAnnual' => $finalBalance['final_balance'],
            'earnedAnnual' => $finalBalance['earned_annual'],
            'workingMonths' => $finalBalance['working_months'],
            'remainExdo' => $exdoBalance['remaining_exdo'],
            'startYear' => $startYear,
            'currentYear' => $currentYear,
            'user' => $user,
            'exdo' => $exdoBalance['total_exdo'],
            'minusExdo' => $exdoBalance['exdo_taken'],
            'expiredExdo' => $exdoBalance['expired_exdo'],
            'forfeited' => $forfeitedData['forfeited'],
            'forfeitedCounts' => $forfeitedData['forfeited_counts'],
            'countAmount' => $forfeitedData['count_amount'],
            'validForfeited' => $forfeitedData['valid_forfeited'],
            'monthlyProgression' => $monthlyProgression,          
        ]);
    }

    public function createLeave()
    {
        $leaveBalanceOutsourceService = new LeaveBalanceOutsourceService();
        $userId = auth()->user()->id;
        
        // Get comprehensive outsource leave information
        $leaveInfo = $leaveBalanceOutsourceService->getOutsourceLeaveInfo($userId);
        $finalBalance = $leaveInfo['final_balance'];
        $finalBalance = $finalBalance['final_balance'];   

        // Get provinces data from service
        $provincesData = ProvincesService::getProvincesJson();
        $provincesByRegion = ProvincesService::getProvincesByRegionJson();

        $headDept = ApprovalLeaveServices::getHeadOfDepartment(auth()->user()->dept_category_id);
        $leaveCategory = Leave_Category::where('id', 1)->get();
        
        return view('leave.outsources.leave.formLeave.annual', compact('finalBalance', 'provincesData', 'provincesByRegion', 'headDept', 'leaveCategory'));
    }

    public function reviewLeave()
    {
        // Get all leave categories
        $leaveCategories = Leave_Category::all();
        $leaveCategory = [];
        foreach ($leaveCategories as $category) {
            $leaveCategory[] = [
                'id' => $category->id,
                'leave_category_name' => $category->leave_category_name
            ];
        }           
        
        return view('leave.outsources.leave.formLeave.review', compact('leaveCategory'));
    }

    public function storeLeave(Request $request)
    {
        $rules = [
            'username' => 'required',
            'nik' => 'required',
            'email' => 'required',
            'position' => 'required',
            'dept_category_name' => 'required',
            'join_date' => 'required',
            'leave_date' => 'required',
            'end_leave_date' => 'required',
            'back_work' => 'required',
            'period' => 'required',
            'leave_category_id' => 'required',
            'entitlement' => 'required',
            'remaining' => 'required',
            'perhitungan' => 'required',
            'city' => 'required',
            'provinces' => 'required',
            'head_of_department' => 'required',
            'reason' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('outsource/leave/outsource/createLeave')->withErrors($validator)->withInput();
        }

        // Prevent overlapping leave dates for this user
        $requestedStartDate = $request->input('leave_date');
        $requestedEndDate = $request->input('end_leave_date');

        $hasOverlappingLeave = Leave::where('user_id', auth()->user()->id)->where('ap_hrd', 0)
            ->where(function ($q) use ($requestedStartDate, $requestedEndDate) {
                // Existing leave starts within requested range
                $q->whereBetween('leave_date', [$requestedStartDate, $requestedEndDate])
                    // Existing leave ends within requested range
                    ->orWhereBetween('end_leave_date', [$requestedStartDate, $requestedEndDate])
                    // Existing leave fully covers the requested range
                    ->orWhere(function ($q2) use ($requestedStartDate, $requestedEndDate) {
                    $q2->where('leave_date', '<=', $requestedStartDate)
                        ->where('end_leave_date', '>=', $requestedEndDate);
                });
            })
            ->exists();

        if ($hasOverlappingLeave) {
            return redirect()->route('outsource/leave/outsource')->with('getError', 'Selected leave dates overlap with an existing leave. Please choose a different period');
        }

        $taken = Leave::where('user_id', auth()->user()->id)->where('leave_category_id', $request->input('leave_category_id'))->sum('total_day');

        $emailPM = $request->input('head_of_department');
        $emailCoor = null;
        $emailSPV = null;
        $emailProducer = null;

        $approvalService = new ApprovalLeaveServices();
        $ruleForm = $approvalService->ruleOutsourceLeave($emailCoor, $emailSPV, $emailPM, $emailProducer);
        $deptName = Dept_Category::where('id', auth()->user()->dept_category_id)->value('dept_category_name');
        $data = [
            'user_id' => auth()->user()->id,
            'leave_category_id' => $request->input('leave_category_id'),
            'req_advance' => 0,
            'exdoExpired' => 0,
            'request_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'request_nik' => auth()->user()->nik,
            'request_position' => auth()->user()->position,
            'request_join_date' => auth()->user()->join_date,
            'request_dept_category_name' => $deptName,
            'period' => date('Y'),
            'leave_date' => $request->input('leave_date'),
            'end_leave_date' => $request->input('end_leave_date'),
            'back_work' => $request->input('back_work'),
            'total_day' => $request->input('perhitungan'),
            'entitlement' => $request->input('entitlement'),
            'pending' => $request->input('entitlement'),
            'remain' => $request->input('remaining'),
            'taken' => $taken + $request->input('perhitungan'),
            'email_pm' => $ruleForm['email_pm'],
            'email_koor' => $ruleForm['email_coor'],
            'email_spv' => $ruleForm['email_spv'],
            'email_producer' => $ruleForm['email_producer'],
            'ap_gm' => $ruleForm['ap_gm'],
            'ap_koor' => $ruleForm['ap_coor'],
            'ap_spv' => $ruleForm['ap_spv'],
            'ap_pm' => $ruleForm['ap_pm'],
            'ap_producer' => $ruleForm['ap_producer'],
            'ap_hd' => $ruleForm['ap_hd'],
            'ap_infinite' => $ruleForm['ap_infinite'],
            'date_ap_gm' => $ruleForm['date_ap_gm'],
            'date_ap_pipeline' => $ruleForm['date_ap_pipeline'],
            'date_ap_spv' => $ruleForm['date_ap_spv'],
            'date_ap_pm' => $ruleForm['date_ap_pm'],
            'date_producer' => $ruleForm['date_producer'],
            'date_ap_infinite' => $ruleForm['date_ap_infinite'],
            'ver_hr' => $ruleForm['ver_hr'],
            'ap_pipeline' => $ruleForm['ap_pipeline'],
            'date_ap_koor' => $ruleForm['date_ap_coor'],
            'reason_leave' => strtolower($request->input('reason')),
            'r_departure' => $request->input('provinces'),
            'r_after_leaving' => $request->input('city'),
            'formStat' => 1,
            'resendmail' => 2,
        ];     
        Leave::insert($data);
        $leave = Leave::where('user_id', auth()->user()->id)->latest()->first();
        Mail::to('dede.aftafiandi@infinitestudios.id')->send(new SendingApplyingLeaveMail($leave->id));

        return redirect()->route('outsource/leave/outsource')->with('success', 'Leave application submitted successfully');
    }

    /**
     * Get holidays for a specific year
     * 
     * @param int $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHolidays($year)
    {
        try {
            $holidays = HolidayService::getAllHolidays($year);

            return response()->json([
                'success' => true,
                'data' => $holidays,
                'year' => $year,
                'total' => count($holidays)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving holidays: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all leave categories from EtcLeaveServices
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveCategories()
    {
        try {
            $categories = EtcLeaveServices::getAllLeaveCategories();
            
            return response()->json([
                'success' => true,
                'data' => $categories,
                'total' => count($categories)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving leave categories: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave category by ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveCategoryById($id)
    {
        try {
            $category = EtcLeaveServices::getLeaveCategoryById($id);
            
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Leave category not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving leave category: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate leave request
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateLeaveRequest(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
            $requestedDays = $request->input('requested_days');
            
            $validation = EtcLeaveServices::validateLeaveRequest($categoryId, $requestedDays);
            
            return response()->json($validation);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error validating leave request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get leave requirements
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLeaveRequirements($id)
    {
        try {
            $requirements = EtcLeaveServices::getLeaveRequirements($id);
            
            return response()->json([
                'success' => true,
                'data' => $requirements
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving leave requirements: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Log leave application for audit trail
     * 
     * @param int $userId
     * @param array $leaveData
     * @return void
     */
    private function logLeaveApplication($userId, $leaveData)
    {
        Log::info('Leave application submitted', [
            'user_id' => $userId,
            'leave_data' => $leaveData,
        ]);
    }
    
    public function etcLeave()
    {       
        $userId = auth()->user()->id;

        // Get provinces data from service
        $provincesData = ProvincesService::getProvincesJson();
        $provincesByRegion = ProvincesService::getProvincesByRegionJson();

        $headDept = ApprovalLeaveServices::getHeadOfDepartment(auth()->user()->dept_category_id);
        
        // Get leave categories from EtcLeaveServices
        $leaveBalanceCategories = EtcLeaveServices::getAllLeaveCategories();

        // dd($leaveBalanceCategories);

        // dilanjutkan dengan ambil id dari leave balance categories yang is_paid adalah true

        $leaveCategory = Leave_Category::whereNotIn('id', [1, 2])->get();
        
        return view('leave.outsources.leave.formLeave.etc', compact('provincesData', 'provincesByRegion', 'headDept', 'leaveBalanceCategories', 'leaveCategory'));
    }

}
