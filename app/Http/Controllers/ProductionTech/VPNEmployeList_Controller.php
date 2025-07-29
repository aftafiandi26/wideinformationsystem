<?php

namespace App\Http\Controllers\ProductionTech;

use App\FormOvertimes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;

class VPNEmployeList_Controller extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active', 'prodTech']);
    }

    public function index()
    {
        return view('production_tech.vpn_overtime.employe_list');
    }

    public function dataTablesIndex()
    {
        $query = FormOvertimes::where('app_coor', true)->whereDATE('startovertime', date('Y-m-d'))->orderBy('startovertime', 'asc')->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function (FormOvertimes $form) {
                $workstation = $form->getUser()->getFullname();
                return $workstation;
            })
            ->addColumn('workstation', function (FormOvertimes $form) {
                return $form->getWS()['hostname'];
            })
            ->addColumn('position', function (FormOvertimes $form) {
                $position = $form->getUser()['position'];
                return $position;
            })
            ->addColumn('duration', function (FormOvertimes $form) {
                $start = new DateTime($form->startovertime);
                $end = new DateTime($form->endovertime);

                $interval = $start->diff($end);

                $duration = $interval->format('%H:%I:%S'); // hasil seperti "08:00:00"
                return $duration;            
            })
            ->make(true);
    }

    public function findTable(Request $request)
    {
        $rules = [
            'start' => 'required|date',
            'end'   => 'required|date'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
			return redirect()->route('prodTech/manage/vpn/list')
				->withErrors($validator)
				->withInput();
        }        

        $start = $request->input('start');
        $end = $request->input('end');

        if ($start > $end) {
            Session::flash('getError', Lang::get('messages.data_custom', ['data' => 'Date wrong! Looking for Data stopped']));
            return redirect()->route('prodTech/manage/vpn/list');
        }
        return redirect()->route('prodTech/manage/vpn/list/findData', compact(['start', 'end']));

    }

    public function findData($started, $ended)
    {
        return view('production_tech.vpn_overtime.findEmp', compact(['started', 'ended']));
    }

    public function dataTabelsFindData($started, $ended)
    {
        $query = FormOvertimes::where('app_coor', true)->whereDATE('startovertime', '>=', $started)->whereDATE('startovertime', '<=', $ended)->orderBy('startovertime', 'asc')->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function (FormOvertimes $form) {
                $workstation = $form->getUser()->getFullname();
                return $workstation;
            })
            ->addColumn('workstation', function (FormOvertimes $form) {
                return $form->getWS()['hostname'];
            })
            ->addColumn('position', function (FormOvertimes $form) {
                $position = $form->getUser()['position'];
                return $position;
            })
            ->addColumn('duration', function (FormOvertimes $form) {
                $start = new DateTime($form->startovertime);
                $end = new DateTime($form->endovertime);

                $interval = $start->diff($end);

                $duration = $interval->format('%H:%I:%S'); // hasil seperti "08:00:00"
                return $duration;            
            })
            ->make(true);
    }
}
