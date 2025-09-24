<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Initial_Leave;
use App\Leave;
use App\Forfeited;
use App\ForfeitedCounts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveApplyingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
    }

    public function indexNewApply()
    {
        $annual = DB::table('users')
            ->leftJoin('initial_leave', 'initial_leave.user_id', '=', 'users.id')
            ->leftJoin('leave_category', 'leave_category.id', '=', 'initial_leave.leave_category_id')
            ->leftJoin('leave_transaction', 'leave_transaction.user_id', '=', 'users.id')
            ->select([
                DB::raw('
            (
                select (
                    select COALESCE(sum(total_day), 0) from leave_transaction where user_id=' . Auth::user()->id . ' and leave_category_id=1
                    and formStat=1
                )
            ) as transactionAnnual')
            ])
            ->first();

        $test = DB::table('leave_transaction')->where('leave_category_id', 1)->where('user_id', auth()->user()->id)->where('ap_hrd', 1)->get();

        $user = User::find(auth::user()->id);

        $startDate = date_create($user->join_date);
        $endDate = date_create($user->end_date);

        $startYear = date('Y', strtotime($user->join_date));
        $endYear = date('Y', strtotime($user->end_date));

        if (auth::user()->emp_status === "Permanent") {
            $yearEnd = date('Y');
        } else {
            $yearEnd = $endYear;
        }

        $now = date_create(date("Y-m-d"));
        $now1 = date_create(date('Y') . '-01-01');
        $now2 = date_create($yearEnd . '-12-31');

        if ($now <= $endDate) {
            $nowed = date_create("2023-03-09");
            $sekarang = $now;
        } else {
            $sekarang = $endDate;
        }

        $cont = date_diff($now, $now1);

        $daff = date_diff($startDate, $sekarang)->format('%m') + (12 * date_diff($startDate, $sekarang)->format('%y'));

        $daffPermanent = date_diff($now1, $now);
        $daffPermanent = $daffPermanent->m;

        $daffPermanent2 = date_diff($now1, $now2)->format('%m') + (12 * date_diff($now1, $now2->modify('+5 day'))->format('%y'));

        $daffPermanent1 = 12 - $daffPermanent;

        if ($daff <= $annual->transactionAnnual) {
            $newAnnual = $annual->transactionAnnual;
        } else {
            $newAnnual = $daff;
        }

        $totalAnnual = $newAnnual - $annual->transactionAnnual;

        $at = array(
            $totalAnnual, $newAnnual, $annual->transactionAnnual, $daff, $sekarang, $now
        );

        $totalAnnualPermanent = $user->initial_annual - $annual->transactionAnnual;

        $totalAnnualPermanent1 = $totalAnnualPermanent - $daffPermanent1;

        //-------------------------------------------------
        // EXDO CALCULATION WITH CORRECT LOGIC
        //-------------------------------------------------

        // Get all exdo records for this user
        $exdoRecords = Initial_Leave::where('user_id', auth::user()->id)
            ->where('initial', '>', 0)
            ->get();

        $totalValidExdo = 0;
        $totalExpiredExdo = 0;
        $currentDate = Carbon::now();

        // Process each exdo record to check expiry
        foreach ($exdoRecords as $record) {
            if ($record->expired) {
                $expiryDate = Carbon::parse($record->expired);
                
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

        // Get exdo taken (approved leaves)
        $minusExdo = Leave::where('user_id', auth::user()->id)
            ->where('leave_category_id', 2)
            ->where('formStat', true)
            ->sum('total_day');

        // Calculate remaining exdo that can be taken
        // Only valid exdo can be used, minus what's already taken
        $sisaExdo = $totalValidExdo - $minusExdo;
        
        // Ensure balance is not negative
        $sisaExdo = max(0, $sisaExdo);

        $try = [
            $sisaExdo,
            $totalValidExdo + $totalExpiredExdo, // Total exdo
            $minusExdo,
            $totalExpiredExdo, // Expired exdo
            "---",
            $totalValidExdo // Valid exdo
        ];

        $test = [
            'totalExdo' => $totalValidExdo + $totalExpiredExdo,
            'validExdo' => $totalValidExdo,
            'expiredExdo' => $totalExpiredExdo,
            'takenExdo' => $minusExdo,
            'sisaExdo' => $sisaExdo
        ];

        $forfeited = Forfeited::where('user_id', auth::user()->id)->pluck('countAnnual');
        $forfeitedCounts = ForfeitedCounts::where('user_id', auth::user()->id)->where('status', 1)->pluck('amount');
        $countAmount = $forfeited->sum() - $forfeitedCounts->sum();

        return view('leave.NewAnnual.indexNewAnnual', compact(
            'totalAnnual',
            'totalAnnualPermanent',
            'totalAnnualPermanent1',
            'at',
            'sisaExdo',
            'try',
            'test',
            'countAmount'
        ));
    }
}
