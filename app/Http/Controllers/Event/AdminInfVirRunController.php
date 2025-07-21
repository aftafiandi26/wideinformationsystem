<?php

namespace App\Http\Controllers\Event;

use App\EventVirRun;
use App\EventVirRunREG;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;

class AdminInfVirRunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);       
    }

    private function adminVerify()
    {
        $admin = null;
        if (auth()->user()->id === 4) {            
            $admin = User::find(4);
        }
        return $admin;
    }

    public function pageVerify()
    {
        $data = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->where('active', true)->first();
        $admin = $this->adminVerify();
        return view('all_employee.Event.Admin.InfVirRun.verify', compact(['data', 'admin']));       
    }

    public function datatablesPageVerify()
    {
        $query = EventVirRun::where('verify', false)->where('delete', false)->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function(EventVirRun $event) {
                $result = $event->EventRegister()->getUser()->getFullName();
                return $result;
            })
            ->addColumn('stravaURL', function (EventVirRun $event) {                
                return "<a href=" .$event->url." target='_blank' rel='noopener noreferrer'>".$event->url."</a>";
            })
            ->addColumn('actions', 'all_employee.Event.Admin.InfVirRun.actions')
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getVerify($id)
    {
        $event = EventVirRun::find($id);     

        return view('all_employee.Event.Admin.InfVirRun.pageVerify', compact(['event']));
    }

    public function approve(Request $request, $id)
    {
        $data = [
            'verify' => 1,
            'user_verify'   => auth()->user()->id
        ];

        $event = EventVirRun::find($id);
        $fullname = $event->EventRegister()->getUser()->getFullName();

        $event->update($data);

        Session::flash('success', Lang::get('messages.data_custom', ['data' => "$fullname submission has been approved"])); 
        return redirect()->route('admin/infinite-virtual-run/verify');
    }

    public function disapprove(Request $request, $id)
    {
        $data = [
            'verify' => 2,
            'user_verify'   => auth()->user()->id
        ];

        $event = EventVirRun::find($id);
        $fullname = $event->EventRegister()->getUser()->getFullName();

        $event->update($data);

        Session::flash('message', Lang::get('messages.data_custom', ['data' => "$fullname submission has been disapprove"])); 
        return redirect()->route('admin/infinite-virtual-run/verify');
    }

    public function delete(Request $request, $id)
    {
        $data = [
            'verify'        => 3,
            'user_verify'   => auth()->user()->id,
            'delete'        => true
        ];

        $event = EventVirRun::find($id);
        $fullname = $event->EventRegister()->getUser()->getFullName();

        $event->update($data);

        Session::flash('message', Lang::get('messages.data_custom', ['data' => "$fullname submission has been delete"])); 
        return redirect()->route('admin/infinite-virtual-run/verify');
    }   
    
    public function participant()
    {
        $data = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->where('active', true)->first();
        $admin = $this->adminVerify();

        return view('all_employee.Event.Admin.InfVirRun.participant', compact(['data', 'admin']));
    }

    public function datatablesParticipant()
    {
        $query = EventVirRunREG::where('periode', date('Y'))->get()
                ->map(function($event) {
                    $user = $event->getUser();
                    $event->fullname = $user->getFullname();
                    $event->nik = $user->nik;
                    return $event;
                });

        return Datatables::of($query)
            ->addIndexColumn()                        
            ->editColumn('active', function(EventVirRunREG $event) {               
                if ($event->active == true) {
                    $result = '<span class="text-success">Active</span>';
                } else {
                    $result = '<span class="text-danger">Inactive</span>';
                }
                return $result;
            })
            ->addColumn('getFullname', function (EventVirRunREG $event) {
                $fullname = $event->fullname;
                $names = explode(' ', $fullname);
                $initials = '';
                if (count($names) > 1) {
                    $initials = strtoupper(substr($names[0],0,1) . substr($names[1],0,1));
                } else {
                    $initials = strtoupper(substr($names[0], 0, 2));
                }
            
                // Styling warna background avatar inisial, disini contoh random soft pastel color
                $colors = ['#7dc7ff', '#a6d8ca', '#f9bb87', '#ffabc3', '#b09cff', '#ffd36e'];
                $color = $colors[array_rand($colors)];
            
                // HTML avatar bulat dengan warna background dan inisial teks
                $avatarStyle = "width: 30px; height: 30px; border-radius: 50%; background: $color; text-align: center; line-height: 30px; color: white; font-weight: 700; font-size: 14px; display: inline-block; margin-right: 10px;";
            
                // Bagi nama menjadi dua baris (nama depan & nama belakang)
                $firstName = $names[0];
                $lastName = count($names) > 1 ? implode(' ', array_slice($names, 1)) : '';
            
                // Gabungkan avatar dan nama dengan pemisah baris
                return '<span style="'.$avatarStyle.'">'. $initials .'</span><div style="display: inline-block; vertical-align: middle; line-height: 1.1;">
                         <div>'.$firstName.'</div>
                         <div style="font-weight: 500; color: #777;">'.$lastName.'</div>
                        </div>';
            })
            ->addColumn('stravaURL', function (EventVirRunREG $event) {                
                return "<a href=" .$event->profileUrl." target='_blank' rel='noopener noreferrer'>".$event->profileUrl."</a>";
            })           
            ->addColumn('actions', 'all_employee.Event.Admin.InfVirRun.actionsParticipants')
            ->rawColumns(['getFullname', 'stravaURL', 'actions', 'active'])
            ->make(true);
    }

    public function editProfileParticipant($id)
    {
        $data = EventVirRunREG::find($id);

        return view('all_employee.Event.Admin.InfVirRun.modalRemoveParticipants', compact(['data']));
    }

    public function updateProfileParticipant(Request $request, $id)
    {
        $rules = [
            'stravaProfile' => 'required|url|min:6'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
			return redirect()->route('admin/infinite-virtual-run/participant')
				->withErrors($validator)
				->withInput();
        }

        $event = EventVirRunREG::find($id);

        $data = [
            'profileUrl' => $request->input('stravaProfile')
        ];   
     
        $event->update($data);
        Session::flash('success', Lang::get('messages.data_custom', ['data' => $event->getUser()->getFullName()." the Strava Profile updated"])); 
        return redirect()->route('admin/infinite-virtual-run/participant');

    }

    public function history()
    {
        $data = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->where('active', true)->first();
        $admin = $this->adminVerify();

        $avatar = 'https://avatar.iran.liara.run/public';

        // https://avatar.iran.liara.run/public/boy

        return view('all_employee.Event.Admin.InfVirRun.history', compact(['data', 'admin']));
    }

    public function dataTablesHistory()
    {
        $query = EventVirRun::whereYEAR('created_at', date('Y'))->where('delete', false)->latest()->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function (EventVirRun $event) {
                $fullname = $event->EventRegister()->getUser()->getFullName();
                $names = explode(' ', $fullname);
                $initials = '';
                if (count($names) > 1) {
                    $initials = strtoupper(substr($names[0],0,1) . substr($names[1],0,1));
                } else {
                    $initials = strtoupper(substr($names[0], 0, 2));
                }
            
                // Styling warna background avatar inisial, disini contoh random soft pastel color
                $colors = ['#7dc7ff', '#a6d8ca', '#f9bb87', '#ffabc3', '#b09cff', '#ffd36e'];
                $color = $colors[array_rand($colors)];
            
                // HTML avatar bulat dengan warna background dan inisial teks
                $avatarStyle = "width: 30px; height: 30px; border-radius: 50%; background: $color; text-align: center; line-height: 30px; color: white; font-weight: 700; font-size: 14px; display: inline-block; margin-right: 10px;";
            
                // Bagi nama menjadi dua baris (nama depan & nama belakang)
                $firstName = $names[0];
                $lastName = count($names) > 1 ? implode(' ', array_slice($names, 1)) : '';
            
                // Gabungkan avatar dan nama dengan pemisah baris
                return '<span style="'.$avatarStyle.'">'. $initials .'</span><div style="display: inline-block; vertical-align: middle; line-height: 1.1;">
                         <div>'.$firstName.'</div>
                         <div style="font-weight: 500; color: #777;">'.$lastName.'</div>
                        </div>';
            })
            ->addColumn('stravaURL', function (EventVirRun $event) {                
                return "<a href=" .$event->url." target='_blank' rel='noopener noreferrer'>".$event->url."</a>";
            })
            ->rawColumns(['staravaURL', 'fullname'])
            ->editColumn('verify', function (EventVirRun $event) {
                if ($event->verify == 1) {
                    // tambahkan class hijau untuk Verified
                    return '<span class="badge badge-success">Verified</span>';
                } elseif ($event->verify == 2) {
                    // tambahkan class merah untuk Rejected
                    return '<span class="badge badge-danger">Rejected</span>';
                } else {                    
                    return "<a href=" .route('admin/infinite-virtual-run/verify')." >Verify</a>";
                }         
            })
            ->make(true);
    }

    public function kickOut(Request $request, $id)
    {
        $event = EventVirRunREG::find($id);

        $event->update(['active' => false]);

        Session::flash('success', Lang::get('messages.data_custom', ['data' => $event->getUser()->getFullName()." has been disqualified from the competition."])); 
        return redirect()->route('admin/infinite-virtual-run/participant');
    }
  
}
