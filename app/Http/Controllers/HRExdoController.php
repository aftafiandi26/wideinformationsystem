<?php

namespace App\Http\Controllers;

use App\Initial_Leave;
use App\Leave;
use App\Services\StatusFormServices;
use App\User;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;

class HRExdoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active', 'hr']);
    }

    public function index()
    {
        return view('HRDLevelAcces.leave.exdo.index.index');
    }

    public function dataTables()
    {
        $users = User::where('active', true)->whereNotIn('nik', ["", "123456789"])->whereNotNull('nik')->orderBy('first_name', 'asc')->get();

        return Datatables::of($users)
            ->addIndexColumn()
            ->addColumn('fullname', function (User $user) {
                return $user->getFullName();
            })
            ->addColumn('department', function (User $user) {
                return $user->getDepartment();
            })
            ->addColumn('total', function (User $user) {
                $exdo = Initial_Leave::where('user_id', $user->id)->pluck('initial');

                $user->temp_exdo = $exdo->sum();

                return $exdo->sum();
            })
            ->addColumn('taken', function (User $user) {
                $takenExdo = Leave::where('user_id', $user->id)->where('leave_category_id', 2)->where('formStat', true)->pluck('total_day');
                $user->temp_taken = $takenExdo->sum();

                return $takenExdo->sum();
            })
            ->addColumn('expired', function (User $user) {
                $expired = Initial_Leave::where('user_id', $user->id)->whereDATE('expired', '<', date('Y-m-d'))->pluck('initial')->sum();

                $takenExdo = $user->temp_taken;
                $exdo = $user->temp_exdo;

                $remains = 0;

                if ($expired >= $takenExdo) {
                    $remains = $expired - $takenExdo;
                }

                $remains = $exdo - $takenExdo - $remains;

                $user->temp_remains = $remains;

                $amount = $remains + $takenExdo;
                $return = $exdo - $amount;

                return $return;
            })
            ->addColumn('remains', function (User $user) {
                $remains = $user->temp_remains;

                return $remains;
            })
            ->make(true);
    }

    public function dataTablesLimit()
    {
        $query = Initial_Leave::whereDATE('expired', '>=', date('Y-m-d'))->whereDATE('expired', '<=', date('Y-m-d', strtotime('+2 weeks')))->get();

        return Datatables::of($query)
        ->addIndexColumn()
        ->addColumn('fullname', function(Initial_leave $initial_leave) {
            $user = User::find($initial_leave->user_id);
            $initial_leave->fullname = $user->fullname;
            return $user->getFullName();
        })
        ->addColumn('nik', function (Initial_leave $initial_leave) {
            $user = User::find($initial_leave->user_id);
            $initial_leave->nik = $user->nik;
            return $user->nik;
        })
        ->addColumn('department', function (Initial_leave $initial_leave) {
            $user = User::find($initial_leave->user_id);
            $initial_leave->department = $user->getDepartment();
            return $initial_leave->department;
        })
        ->addColumn('position', function (Initial_leave $initial_leave) {
            $user = User::find($initial_leave->user_id);
            $initial_leave->position = $user->position;
            return $initial_leave->position;
        })
        ->make(true);
    }

    public function dataTablesFormExdo()
    {
        $query = Leave::where('leave_category_id', 2)->where('ap_hrd', 0)->orderBy('leave_date', 'asc')->whereYEAR('leave_date', date('Y'))->get();
     
        return Datatables::of($query)
        ->addIndexColumn()
        ->addColumn('statusForm', function (Leave $leave) {
            $status = new StatusFormServices();
            $return = $status->statusForm($leave);
            return $return;
        })
        ->make(true);
    }
}