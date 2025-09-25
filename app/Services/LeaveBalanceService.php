<?php

namespace App\Services;

use App\Initial_Leave;
use App\Leave;
use App\User;
use App\Forfeited;
use App\ForfeitedCounts;
use Illuminate\Support\Facades\DB;

class LeaveBalanceService
{
    /**
     * Calculate annual leave taken by user
     * 
     * @param int $userId
     * @return int
     */
    public function getAnnualLeaveTaken($userId)
    {
        return DB::table('leave_transaction')
            ->where('user_id', $userId)
            ->where('leave_category_id', 1)
            ->where('formStat', 1)
            ->sum('total_day');
    }

    /**
     * Calculate exdo balance for user
     * 
     * @param int $userId
     * @return array
     */
    public function calculateExdoBalance($userId)
    {
        // Get total exdo owned
        $totalExdo = Initial_Leave::where('user_id', $userId)->pluck('initial');
        
        // Get expired exdo
        $expiredExdo = Initial_Leave::where('user_id', $userId)
            ->whereDate('expired', '<', date('Y-m-d'))
            ->pluck('initial');
        
        // Get exdo taken
        $exdoTaken = Leave::where('user_id', $userId)
            ->where('leave_category_id', 2)
            ->where('formStat', true)
            ->pluck('total_day');
        
        // Calculate remaining exdo
        $goingExdo = 0;
        if ($expiredExdo->sum() >= $exdoTaken->sum()) {
            $goingExdo = $expiredExdo->sum() - $exdoTaken->sum();
        }
        
        $remainingExdo = $totalExdo->sum() - $exdoTaken->sum() - $goingExdo;
        
        return [
            'total_exdo' => $totalExdo,
            'expired_exdo' => $expiredExdo,
            'exdo_taken' => $exdoTaken,
            'remaining_exdo' => $remainingExdo,
            'going_exdo' => $goingExdo
        ];
    }

    /**
     * Calculate forfeited leave
     * 
     * @param int $userId
     * @return array
     */
    public function calculateForfeitedLeave($userId)
    {
        $forfeited = Forfeited::where('user_id', $userId)->pluck('countAnnual');
        $forfeitedCounts = ForfeitedCounts::where('user_id', $userId)
            ->where('status', 1)
            ->pluck('amount');
        
        $countAmount = $forfeited->sum() - $forfeitedCounts->sum();
        $validForfeited = max(0, $countAmount);
        
        return [
            'forfeited' => $forfeited,
            'forfeited_counts' => $forfeitedCounts,
            'count_amount' => $countAmount,
            'valid_forfeited' => $validForfeited
        ];
    }

    /**
     * Calculate annual leave balance based on employment status
     * 
     * @param User $user
     * @param int $annualTaken
     * @return array
     */
    public function calculateAnnualLeaveBalance($user, $annualTaken)
    {
        $startDate = date_create($user->join_date);
        $endDate = date_create($user->end_date);
        
        $startYear = date('Y', strtotime($user->join_date));
        $endYear = date('Y', strtotime($user->end_date));
        
        // Determine year end based on employment status
        if ($user->emp_status === "Permanent") {
            $yearEnd = date('Y');
        } else {
            $yearEnd = $endYear;
        }
        
        $now = date_create(date("Y-m-d"));
        $yearStart = date_create(date('Y') . '-01-01');
        $yearEndDate = date_create($yearEnd . '-12-31');
        
        // Calculate working months
        if ($now <= $endDate) {
            $currentDate = $now;
        } else {
            $currentDate = $endDate;
        }
        
        $workingMonths = date_diff($startDate, $currentDate)->format('%m') + 
                        (12 * date_diff($startDate, $currentDate)->format('%y'));
        
        // Calculate annual leave based on employment status
        if ($user->emp_status === "Permanent") {
            $permanentMonths = date_diff($yearStart, $now)->m;
            $permanentTotalMonths = date_diff($yearStart, $yearEndDate)->format('%m') + 
                                   (12 * date_diff($yearStart, $yearEndDate->modify('+5 day'))->format('%y'));
            $remainingPermanentMonths = 12 - $permanentMonths;
            
            $newAnnual = $workingMonths;
            if ($workingMonths <= $annualTaken) {
                $newAnnual = $annualTaken;
            }
            
            $totalAnnual = $newAnnual - $annualTaken;
            $totalAnnualPermanent = $user->initial_annual - $annualTaken;
            $totalAnnualPermanent1 = $totalAnnualPermanent - $remainingPermanentMonths;
            
            return [
                'total_annual' => $totalAnnual,
                'total_annual_permanent' => $totalAnnualPermanent1,
                'start_year' => $startYear,
                'year_end' => $yearEnd,
                'new_annual' => $newAnnual
            ];
        } else {
            // Contract employee logic
            $newAnnual = $workingMonths;
            if ($workingMonths <= $annualTaken) {
                $newAnnual = $annualTaken;
            }
            
            $totalAnnual = $newAnnual - $annualTaken;
            
            return [
                'total_annual' => $totalAnnual,
                'total_annual_permanent' => 0,
                'start_year' => $startYear,
                'year_end' => $yearEnd,
                'new_annual' => $newAnnual
            ];
        }
    }

    /**
     * Calculate final leave balance with forfeited adjustment
     * 
     * @param array $annualBalance
     * @param array $forfeitedData
     * @param string $empStatus
     * @return array
     */
    public function calculateFinalBalance($annualBalance, $forfeitedData, $empStatus)
    {
        $renewPermanent = $annualBalance['total_annual_permanent'] - $forfeitedData['valid_forfeited'];
        $renewContract = $annualBalance['total_annual'] - $forfeitedData['valid_forfeited'];
        
        // PKL employees get no annual leave
        if ($empStatus === "PKL") {
            $renewContract = 0;
        }
        
        return [
            'renew_permanent' => $renewPermanent,
            'renew_contract' => $renewContract
        ];
    }
}
