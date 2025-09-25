<?php

namespace App\Http\Controllers;

use App\Services\LeaveBalanceService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Leave_BalanceController extends Controller
{
    protected $leaveBalanceService;

    public function __construct(LeaveBalanceService $leaveBalanceService)
    {
        $this->middleware(['auth', 'active']);
        $this->leaveBalanceService = $leaveBalanceService;
    }

    /**
     * Display new leave application form with leave balance
     * 
     * @return \Illuminate\View\View
     */
    public function indexNewApply()
    {
        $userId = Auth::user()->id;
        $user = User::find($userId);
        
        // Get annual leave taken using service
        $annualTaken = $this->leaveBalanceService->getAnnualLeaveTaken($userId);
        $annual = (object)['transactionAnnual' => $annualTaken];
        
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
        
        // Prepare data for view
        $viewData = [
            'annual' => $annual,
            'totalAnnual' => $annualBalance['total_annual'],
            'totalAnnualPermanent1' => $annualBalance['total_annual_permanent'],
            'remainExdo' => $exdoBalance['remaining_exdo'],
            'startYear' => $annualBalance['start_year'],
            'yearEnd' => $annualBalance['year_end'],
            'user' => $user,
            'exdo' => $exdoBalance['total_exdo'],
            'minusExdo' => $exdoBalance['exdo_taken'],
            'w' => $exdoBalance['expired_exdo'],
            'forfeited' => $forfeitedData['forfeited'],
            'forfeitedCounts' => $forfeitedData['forfeited_counts'],
            'countAmount' => $forfeitedData['count_amount'],
            'bla' => $forfeitedData['valid_forfeited'],
            'renewPermanet' => $finalBalance['renew_permanent'],
            'renewContract' => $finalBalance['renew_contract'],
            'try' => [
                'annual' => $annualTaken,
                'totalAnnual' => $annualBalance['total_annual'],
                'totalAnnualPermanent1' => $annualBalance['total_annual_permanent'],
                'remainExdo' => $exdoBalance['remaining_exdo'],
                'startYear' => $annualBalance['start_year'],
                'yearEnd' => $annualBalance['year_end'],
                'user' => $user->initial_annual,
                'exdo' => $exdoBalance['total_exdo'],
                'minusExdo' => $exdoBalance['exdo_taken'],
                'w' => $exdoBalance['expired_exdo'],
                'forfeited' => $forfeitedData['forfeited'],
                'forfeitedCounts' => $forfeitedData['forfeited_counts'],
                'countAmount' => $forfeitedData['count_amount'],
                'bla' => $forfeitedData['valid_forfeited'],
                'renewPermanet' => $finalBalance['renew_permanent'],
                'renewContract' => $finalBalance['renew_contract']
            ]
        ];
        
        return view('leave.NewAnnual.indexNewAnnual', $viewData);
    }
}
