<?php

namespace App\Http\Controllers;

use App\Dept_Category;
use Datatables;
use App\Leave;
use App\Leave_Category;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AllEmployesLeaveController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
    }

    public function indexSummary()
    {
        $department = Dept_Category::all();

        return view('leave.summary.indexAllEmployes', compact(['department']));
    }

    public function objectSummary()
    {
        $data = Leave::whereYear('leave_date', date('Y'))->where('ap_hrd', 1)->orderBy('leave_date', 'dasc')->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('ap_hrd', '@if($ap_hrd === 1){{ "Accepted" }} @elseif($ap_hrd === 0){{ "Progressing" }} @else {{ "Rejected" }} @endif')
            ->editColumn('leave_category_id', function (Leave $leave) {
                return $leave->getLeaveCategory();
            })
            ->editCOlumn('reason_leave', function (leave $leave) {
                $return = Str::lower($leave->reason_leave);
                return $return;
            })
            ->editColumn('leave_date', function (Leave $leave) {
                return date('d F', strtotime($leave->leave_date));
            })
            ->editColumn('end_leave_date', function (Leave $leave) {
                return date('d F', strtotime($leave->end_leave_date));
            })
            ->editColumn('back_work', function (Leave $leave) {
                return date('d F', strtotime($leave->back_work));
            })
            ->addColumn('actions', 'leave.summary.indexActions')
            ->make(true);
    }

    public function modalSummary($id)
    {
        $leave = Leave::find($id);
        $hd = User::where('dept_category_id', $leave->user()->dept_category_id)->where('hd', 1)->first();

        return view('leave.summary.modalAllEmployes', compact(['leave', 'hd']));
    }

    public function getRequest(Request $request)
    {
        $start = $request->input('startDate');
        $end   = $request->input('endDate');
        $dept  = $request->input('select');

        return redirect()->route('leave/summary/employes/find', [$start, $end, $dept]);
    }

    public function findLeave($start, $end, $dept)
    {
        $department = Dept_Category::all();

        return view('leave.summary.find.index', compact(['start', 'end', 'dept', 'department']));
    }

    public function findData($start, $end, $dept)
    {
        if ($dept != "all") {
            $query = Leave::whereBetween('leave_date', [$start, $end])->where('request_dept_category_name', $dept)->where('ap_hrd', 1)->orderBy('leave_date', 'asc')->get();
        } else {
            $query = Leave::whereBetween('leave_date', [$start, $end])->where('ap_hrd', 1)->orderBy('leave_date', 'asc')->get();
        }

        return Datatables::of($query)
            ->addIndexColumn()
            ->editColumn('ap_hrd', '@if($ap_hrd === 1){{ "Accepted" }} @elseif($ap_hrd === 0){{ "Progressing" }} @else {{ "Rejected" }} @endif')
            ->editColumn('leave_category_id', function (Leave $leave) {
                return $leave->getLeaveCategory();
            })
            ->editCOlumn('reason_leave', function (leave $leave) {
                $return = Str::lower($leave->reason_leave);
                return $return;
            })
            ->editColumn('leave_date', function (Leave $leave) {
                return date('d F', strtotime($leave->leave_date));
            })
            ->editColumn('end_leave_date', function (Leave $leave) {
                return date('d F', strtotime($leave->end_leave_date));
            })
            ->editColumn('back_work', function (Leave $leave) {
                return date('d F', strtotime($leave->back_work));
            })
            ->addColumn('actions', 'leave.summary.indexActions')
            ->make(true);
    }

    public function indexCalender()
    {
        return view('leave.calender.index');
    }

    public function objectCalender()
    {
        $query = Leave::where('ap_hrd', 1)
        ->where('ap_hd', 1)
        ->whereYear('leave_date', date('Y'))
        ->orderBy('leave_date', 'desc')
        // ->where('request_nik', 21801003)
        ->get();

        // Preload semua user dan kategori yang dibutuhkan
        $userIds = $query->pluck('user_id')->unique()->toArray();
        $categoryIds = $query->pluck('leave_category_id')->unique()->toArray();
        
        $users = User::whereIn('id', $userIds)->where('active', true)->get()->keyBy('id');
        $categories = Leave_Category::whereIn('id', $categoryIds)->get()->keyBy('id');
        
        $arrayQuery = [];
        
        foreach ($query as $value) {
            if (!isset($users[$value->user_id])) {
                continue;
            }
        
            $user = $users[$value->user_id];
            $category = isset($categories[$value->leave_category_id]) ? $categories[$value->leave_category_id] : null;
            
            if (!$category) {
                continue;
            }
        
            $colorMap = array(
                1 => 'lightblue',
                2 => 'lightgreen'
            );
            
            $color = isset($colorMap[$value->leave_category_id]) ? $colorMap[$value->leave_category_id] : 'grey';
            
            $textColor = $color === 'grey' ? 'white' : 'black';
        
            $arrayQuery[] = array(
                'id' => $value->id,
                'title' => $user['username'] . ' ' . "(" . $category->leave_category_name . ")",
                'start' => $value['leave_date'],
                'end' => $value['end_leave_date'],
                'color' => $color,
                'textColor' => $textColor,
                'allDay' => true,
                'extendedProps' => array(
                    'id' => $value->id,
                    'title' => $user['username'],
                    'start' => $value['leave_date'],
                    'end' => $value['end_leave_date'],
                )
            );

            new DateTime()
            
            // dd($arrayQuery);
        }
        
        return $arrayQuery;
    }
}