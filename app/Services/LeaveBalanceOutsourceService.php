<?php

namespace App\Services;

use App\Initial_Leave;
use App\Leave;
use App\User;
use App\Forfeited;
use App\ForfeitedCounts;
use Illuminate\Support\Facades\DB;

class LeaveBalanceOutsourceService
{
    /**
     * Calculate annual leave taken by outsource user
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
     * Calculate exdo balance for outsource user
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
     * Calculate forfeited leave for outsource user
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
     * Calculate outsource leave balance with current date as end date
     * 
     * @param User $user
     * @param int $annualTaken
     * @return array
     */
    public function calculateOutsourceLeaveBalance($user, $annualTaken)
    {
        $startDate = date_create($user->join_date);
        $currentDate = date_create(date("Y-m-d")); // Use current date as end date
        
        // Calculate working months from join date to current date
        $workingMonths = $this->calculateWorkingMonths($startDate, $currentDate);
        
        // For outsource employees, leave balance is based on months worked
        // Each month worked = 1 day of annual leave
        $earnedAnnualLeave = $workingMonths;
        
        // Calculate remaining annual leave
        $remainingAnnualLeave = max(0, $earnedAnnualLeave - $annualTaken);
        
        // Calculate year information
        $startYear = date('Y', strtotime($user->join_date));
        $currentYear = date('Y');
        
        return [
            'total_annual' => $remainingAnnualLeave,
            'earned_annual' => $earnedAnnualLeave,
            'working_months' => $workingMonths,
            'start_year' => $startYear,
            'current_year' => $currentYear,
            'start_date' => $user->join_date,
            'current_date' => date('Y-m-d')
        ];
    }

    /**
     * Calculate working months between two dates
     * 
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return int
     */
    private function calculateWorkingMonths($startDate, $endDate)
    {
        $diff = date_diff($startDate, $endDate);
        $months = $diff->format('%m') + (12 * $diff->format('%y'));
        
        // Add partial month if more than 15 days
        if ($diff->format('%d') >= 15) {
            $months += 1;
        }
        
        return $months;
    }

    /**
     * Calculate monthly progression of leave balance
     * 
     * @param User $user
     * @return array
     */
    public function calculateMonthlyProgression($user)
    {
        $startDate = date_create($user->join_date);
        $currentDate = date_create(date("Y-m-d"));
        
        $progression = [];
        $tempDate = clone $startDate;
        
        while ($tempDate <= $currentDate) {
            $monthsWorked = $this->calculateWorkingMonths($startDate, $tempDate);
            $earnedLeave = $monthsWorked;
            
            $progression[] = [
                'date' => $tempDate->format('Y-m-d'),
                'month' => $tempDate->format('F Y'),
                'months_worked' => $monthsWorked,
                'earned_leave' => $earnedLeave
            ];
            
            // Move to next month
            $tempDate->modify('first day of next month');
        }
        
        return $progression;
    }

    /**
     * Calculate final outsource leave balance
     * 
     * @param array $leaveBalance
     * @param array $forfeitedData
     * @return array
     */
    public function calculateFinalOutsourceBalance($leaveBalance, $forfeitedData)
    {
        // For outsource employees, forfeited leave doesn't apply the same way
        // They get leave based on months worked, not forfeited adjustments
        $finalBalance = $leaveBalance['total_annual'];
        
        return [
            'final_balance' => $finalBalance,
            'earned_annual' => $leaveBalance['earned_annual'],
            'working_months' => $leaveBalance['working_months'],
            'forfeited_adjustment' => 0 // Outsource employees don't have forfeited adjustments
        ];
    }

    /**
     * Get comprehensive outsource leave information
     * 
     * @param int $userId
     * @return array
     */
    public function getOutsourceLeaveInfo($userId)
    {
        $user = User::find($userId);
        $annualTaken = $this->getAnnualLeaveTaken($userId);
        $exdoBalance = $this->calculateExdoBalance($userId);
        $forfeitedData = $this->calculateForfeitedLeave($userId);
        $leaveBalance = $this->calculateOutsourceLeaveBalance($user, $annualTaken);
        $finalBalance = $this->calculateFinalOutsourceBalance($leaveBalance, $forfeitedData);
        $monthlyProgression = $this->calculateMonthlyProgression($user);
        
        return [
            'user' => $user,
            'annual_taken' => $annualTaken,
            'exdo_balance' => $exdoBalance,
            'forfeited_data' => $forfeitedData,
            'leave_balance' => $leaveBalance,
            'final_balance' => $finalBalance,
            'monthly_progression' => $monthlyProgression
        ];
    }
}
