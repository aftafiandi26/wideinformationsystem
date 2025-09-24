<?php

namespace App\Http\Controllers;

use App\Initial_Leave;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leave;
use App\User;
use Yajra\Datatables\Facades\Datatables;

class CoordinatorLeaveBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
    }

    public function index()
    {
        return view('production.coordinator.leaveBalance.index');
    }
    
    public function dataTables()
    {
        $query = User::select(['id', 'username', 'nik', 'first_name', 'last_name', 'position', 'emp_status', 'dept_category_id', 'initial_annual', 'join_date', 'end_date'])->where('active', 1)->whereNotIn('nik', ["", "123456789"])->orderBy('first_name', 'asc')->get();

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
            ->addColumn('annual_taken', function (User $user)  {
                $taken = Leave::where('user_id', $user->id)->where('leave_category_id', 1)->where('formstat', 1)->sum('total_day');
                $user->annual_taken = $taken;
                return $taken;
            })
            ->addColumn('annual_balance', function (User $user) {
                $balance = $user->total_annual - $user->annual_taken;
                $user->annual_balance = $balance;
                return $balance;
            })
            ->addColumn('total_exdo', function (User $user) {
                $exdo = Initial_Leave::where('user_id', $user->id)->pluck('initial')->sum();
                $user->total_exdo = $exdo;
                return $exdo;
            })
            ->addColumn('exdo_expired', function (User $user) {
                $expired = Initial_Leave::where('user_id', $user->id)->where('expired', '<', date('Y-m-d'))->pluck('initial')->sum();
                $user->exdo_expired = $expired;
                return $expired;
            })          
            ->addColumn('exdo_taken', function (User $user) {
                $taken = Leave::where('user_id', $user->id)->where('leave_category_id', 2)->where('formstat', 1)->sum('total_day');
                $user->exdo_taken = $taken;
                return $taken;
            })
            ->addColumn('exdo_balance', function (User $user) {
                // Get current date for comparison
                $currentDate = \Carbon\Carbon::now();
                
                // Get all exdo records for this user
                $exdoRecords = Initial_Leave::where('user_id', $user->id)
                    ->where('initial', '>', 0)
                    ->get();
                
                $totalValidExdo = 0;
                $totalExpiredExdo = 0;
                
                // Process each exdo record to check expiry
                foreach ($exdoRecords as $record) {
                    if ($record->expired) {
                        $expiryDate = \Carbon\Carbon::parse($record->expired);
                        
                        if ($currentDate->greaterThan($expiryDate)) {
                            // Exdo has expired
                            $totalExpiredExdo += $record->initial;
                        } else {
                            // Exdo is still valid
                            $totalValidExdo += $record->initial;
                        }
                    } else {
                        // If no expiry date, consider it valid
                        $totalValidExdo += $record->initial;
                    }
                }
                
                // Get exdo taken (already calculated in exdo_taken column)
                $exdoTaken = $user->exdo_taken;
                
                // Calculate remaining exdo that can be taken
                // Only valid exdo can be used, minus what's already taken
                $remainingExdo = $totalValidExdo - $exdoTaken;
                
                // Ensure balance is not negative
                $finalBalance = max(0, $remainingExdo);
                
                // Store for debugging
                $user->exdo_valid = $totalValidExdo;
                $user->exdo_expired = $totalExpiredExdo;
                $user->exdo_balance = $finalBalance;
                
                return $finalBalance;
            })
            ->addColumn('total_balance', function (User $user) {
                return $user->annual_balance + $user->exdo_balance;
            })
            ->make(true);
    }
}
