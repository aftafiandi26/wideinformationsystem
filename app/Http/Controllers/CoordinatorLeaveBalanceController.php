<?php

namespace App\Http\Controllers;

use App\Dept_Category;
use App\Initial_Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leave;
use App\Leave_Category;
use App\User;
use App\Services\LeaveBalanceService;
use App\Services\StatusFormServices;
use Yajra\Datatables\Facades\Datatables;

class CoordinatorLeaveBalanceController extends Controller
{
    protected $leaveBalanceService;

    public function __construct(LeaveBalanceService $leaveBalanceService)
    {
        $this->middleware(['auth', 'active', 'leaveBalance']);
        $this->leaveBalanceService = $leaveBalanceService;
    }

    private function deptID()
    {
        $deptID = auth()->user()->dept_category_id;

        if ($deptID === 1) {
            $return = [1, 10, 11];
        }
        if ($deptID === 2) {
            $return = [2];
        }
        if ($deptID === 3) {
            $return = [3];
        }
        if ($deptID === 4) {
            $return = [4, 6];
        }
        if ($deptID === 5) {
            $return = [5];
        }
        if ($deptID === 6) {
            $return = [4, 6];
        }
        if ($deptID === 7) {
            $return = [7];
        }
        if ($deptID === 8) {
            $return = [8];
        }
        if ($deptID === 9) {
            $return = [9];
        }
        if ($deptID === 10) {
            $return = [1, 10, 11];
        }
        if ($deptID === 11) {
            $return = [1, 10, 11];
        }
        if (auth()->user()->hr === 1) {
            $return = [1,2,3,4,5,6,7,8,9,10,11];
        }
        return $return;
    }

    public function index()
    {
        return view('production.coordinator.leaveBalance.index');
    }
    
    public function dataTables()
    {
        $deptID = $this->deptID();

        $query = User::select(['id', 'username', 'nik', 'first_name', 'last_name', 'position', 'emp_status', 'dept_category_id', 'initial_annual', 'join_date', 'end_date'])
            ->where('active', 1)
            ->whereNotIn('nik', ["", "123456789", "D0002"])
            ->whereNotIn('emp_status', ["Outsource"])
            ->whereIn('dept_category_id', $deptID)
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

    public function dataTablesForm()
    {
        $deptID = $this->deptID();
        $changeDeptID = Dept_Category::whereIN('id', $deptID)->pluck('dept_category_name');
     
        $query = Leave::whereIN('request_dept_category_name', $changeDeptID)->where('ap_hrd', 0)->where('formStat', 1)->whereYEAR('leave_date', date('Y'))->get();

        return Datatables::of($query)
        ->addIndexColumn()
        ->editColumn('leave_category_id', function (Leave $leave) {
            $return = Leave_Category::find($leave->leave_category_id);
            $categoryName = $return->leave_category_name;
            
            // Tentukan warna berdasarkan kategori
            $color = '';
            if ($categoryName == 'Annual') {
                $color = 'color: black;';
            } elseif ($categoryName == 'Exdo') {
                $color = 'color: blue;';
            } else {
                $color = 'color: green;';
            }
            
            $html = "<span class='nameCategory' style='{$color}'>". $categoryName."</span>";
            return $html;
        })
        ->setRowClass(function (Leave $leave) {
            $return = Leave_Category::find($leave->leave_category_id);
            $categoryName = $return->leave_category_name;
            
            // Tentukan class CSS untuk baris berdasarkan kategori
            if ($categoryName == 'Annual') {
                return 'row-annual';
            } elseif ($categoryName == 'Exdo') {
                return 'row-exdo';
            } else {
                return 'row-other';
            }
        })
        ->addColumn('statusForm', function (Leave $leave) {
            $status = new StatusFormServices();
            $return = $status->statusForm($leave);
            return $return;
        })
        ->make(true);
    }

}
