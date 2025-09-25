<?php

namespace App\Http\Controllers;

use App\Initial_Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leave;
use App\User;
use App\Services\LeaveBalanceService;
use Yajra\Datatables\Facades\Datatables;

class CoordinatorLeaveBalanceController extends Controller
{
    protected $leaveBalanceService;

    public function __construct(LeaveBalanceService $leaveBalanceService)
    {
        $this->middleware(['auth', 'active']);
        $this->leaveBalanceService = $leaveBalanceService;
    }

    public function index()
    {
        return view('production.coordinator.leaveBalance.index');
    }
    
    public function dataTables()
    {
        $query = User::select(['id', 'username', 'nik', 'first_name', 'last_name', 'position', 'emp_status', 'dept_category_id', 'initial_annual', 'join_date', 'end_date'])
            ->where('active', 1)
            ->whereNotIn('nik', ["", "123456789", "D0002"])
            ->whereNotIn('emp_status', ["Outsource"])
            ->orderBy('first_name', 'asc')
            ->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function (User $user) {
                return $user->getFullName();
            })
            ->addColumn('department', function (User $user) {
                return $user->getDepartment();
            })
            ->addColumn('total_annual', function (User $user) {
                $result = $user->initial_annual;
                $user->total_annual = $result;
                return $result;
            })
            ->addColumn('annual_taken', function (User $user) {
                // Use LeaveBalanceService for consistent calculation
                $taken = $this->leaveBalanceService->getAnnualLeaveTaken($user->id);
                $user->annual_taken = $taken;
                return $taken;
            })
            ->addColumn('annual_available', function (User $user) {
                // Use LeaveBalanceService for consistent calculation
                $annual_available = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $user->annual_taken);
                
                // Get the appropriate balance based on employment status
                if ($user->emp_status === 'Permanent') {
                    $balance = $annual_available['total_annual_permanent'];
                } else {
                    $balance = $annual_available['total_annual'];
                }
                
                $user->annual_available  = $balance;
                return $balance;
            })         
            ->addColumn('final_annual_balance', function (User $user) {
                // Get annual leave taken               
                
                $annualTaken = $this->leaveBalanceService->getAnnualLeaveTaken($user->id);
                $annual = (object)['transactionAnnual' => $annualTaken];
                
                // Calculate annual leave balance
                $annualBalance = $this->leaveBalanceService->calculateAnnualLeaveBalance($user, $annualTaken);
                
                // Calculate forfeited leave
                $forfeitedData = $this->leaveBalanceService->calculateForfeitedLeave($user->id);              
 
                if ($user->forfeitcase === 1) {                    
                    $result = $user->initial_annual - $annual->transactionAnnual;                   
                } else {
                    $result = $user->initial_annual - $annual->transactionAnnual - $forfeitedData['valid_forfeited'];                    
                }   
                $user->final_annual_balance = $result;
                return $result;
            })
            ->addColumn('total_exdo', function (User $user) {
                // Use LeaveBalanceService for consistent calculation
                $exdoBalance = $this->leaveBalanceService->calculateExdoBalance($user->id);
                $exdo = $exdoBalance['total_exdo']->sum();
                $user->total_exdo = $exdo;
                return $exdo;
            })
            ->addColumn('exdo_expired', function (User $user) {
                // Use LeaveBalanceService for consistent calculation
                $exdoBalance = $this->leaveBalanceService->calculateExdoBalance($user->id);
                $expired = $exdoBalance['expired_exdo']->sum();
                $user->exdo_expired = $expired;
                return $expired;
            })
            ->addColumn('exdo_taken', function (User $user) {
                // Use LeaveBalanceService for consistent calculation
                $exdoBalance = $this->leaveBalanceService->calculateExdoBalance($user->id);
                $taken = $exdoBalance['exdo_taken']->sum();
                $user->exdo_taken = $taken;
                return $taken;
            })
            ->addColumn('exdo_balance', function (User $user) {
                // Use LeaveBalanceService for consistent calculation
                $exdoBalance = $this->leaveBalanceService->calculateExdoBalance($user->id);
                $balance = $exdoBalance['remaining_exdo'];
                
                // Store for debugging
                $user->exdo_valid = $exdoBalance['total_exdo']->sum();
                $user->exdo_expired = $exdoBalance['expired_exdo']->sum();
                $user->exdo_balance = $balance;
                
                return $balance;
            })
            ->addColumn('total_balance', function (User $user) {
                return $user->final_annual_balance + $user->exdo_balance;
            })
            ->make(true);
    }

}
