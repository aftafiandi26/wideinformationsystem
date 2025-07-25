<?php

namespace App\Http\Controllers\Event;

use App\EventOutsider;
use App\EventVirRun;
use App\EventVirRunREG;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\Event\IFW\InfiniteVirtualRun\AccountRegistrationMail;
use App\NewUser;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;

class Outsider_InfiVirRunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    private function ebib()
    {
        $ebib = EventVirRunREG::where('periode', date('Y'))->orderBy('ebib', 'desc')->first();

        if ($ebib) {
            return $ebib->ebib;
        }
        return 25000;        
    }    

    public function adminRegistration()
    {
       return view('all_employee.Event.Outsider.adminRegis');
    }
    
    public function modalRegistration()
    {
        return view('all_employee.Event.Outsider.modalRegistrations');
    }

    public function storeRegister(Request $request)
    {
        $rules = [
            'firstName'     => 'required',
            'lastName'      => 'required',
            'email'         => 'required|email',
            'username'      => 'required|unique:users,username',
            'password'      => 'required|min:6',
            'gender'        => 'required',
            'strava'        => 'required|url|min:6',
            'phone'         => 'required|max:12|min:10',
            'company'       => 'required'
        ];    

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
			return redirect()->route('outsider/infiniteVirRun/run/adminRegistration')
				->withErrors($validator)
				->withInput();
        }

        $pass = $request->input('password');  
        $password = Hash::make($pass);

        $data = [
            'first_name' => $request->input('firstName'),
            'last_name'  => $request->input('lastName'),
            'email'      => $request->input('email'),
            'username'   => $request->input('username'),
            'password'   => $password,
            'evnt_member_outsider' => true,
            'gender'     => $request->input('gender'),
            'phone'      => $request->input('phone'),  
            'active'    => true            
        ];   
   
        NewUser::insert($data);
        $user = User::where('username', $data['username'])->where('active', 1)->first();
      
        $reg = [
            'user_id' => $user->id,
            'ebib'    => $this->ebib() + 1,
            'active'  => true,
            'periode' => date('Y'),  
            'email'   => $data['email'],
            'gender'  => $data['gender'],
            'profileUrl' => $request->input('strava')           
        ];    

        EventVirRunREG::create($reg);
        $eventReg = EventVirRunREG::where('user_id', $user->id)->where('periode', $reg['periode'])->first();

        $outsider = [
            'user_id'   => $user->id,
            'ebib'      => $eventReg->ebib,
            'gender'    => $data['gender'],
            'phone'     => $data['phone'],
            'company'   => $request->input('company')
        ];

        EventOutsider::create($outsider);  
        $param = [
            'id'    => $user->id,
            'pass'  => $pass
        ];
        Mail::send(new AccountRegistrationMail($param));
        Session::flash('success', Lang::get('messages.data_custom', ['data' => $user->getFullName() . " registered"]));            
        return redirect()->route('outsider/infiniteVirRun/run/adminRegistration');        
    }

    public function datatabalesRegister()
    {
        $query = EventOutsider::all();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('fullname', function(EventOutsider $event) {
                return $event->user()->getFullName();
            })
            ->addColumn('email', function(EventOutsider $event) {
                return  $event->eventReg()->email;
            })
            ->addColumn('strava', function(EventOutsider $event) {              
                $result = "<a href='".$event->eventReg()->profileUrl."' class='btn btn-xs btn-info' title='strava profile' target='_blank' rel='noopener noreferrer' >Strava Profile</a>";
                return $result;
            })
            ->addColumn('actions', 'all_employee.Event.Outsider.actionRegis') 
            ->rawColumns(['actions', 'strava'])                       
            ->make(true);
    }

    public function editAccount($id)
    {
        $data = EventOutsider::find($id);
        return view('all_employee.Event.Outsider.edit', compact(['data']));
    }

    public function updateAccount(Request $request, $id)
    {

        $rules = [
            'firstName'     => 'required',
            'lastName'      => 'required',
            'email'         => 'required|email',          
            'gender'        => 'required',
            'strava'        => 'required|url|min:6',
            'phone'         => 'required|max:12|min:10',
            'company'       => 'required'
        ];    

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
			return redirect()->route('outsider/infiniteVirRun/run/adminRegistration')
				->withErrors($validator)
				->withInput();
        }
        
        $getAccount = EventOutsider::find($id);

        $data = [
            'first_name' => $request->input('firstName'),
            'last_name'  => $request->input('lastName'),
            'email'      => $request->input('email'),
            'username'   => $request->input('username'),
            // 'evnt_member_outsider' => true,
            'gender'     => $request->input('gender'),
            'phone'      => $request->input('phone'),  
            // 'active'    => true            
        ];  
        User::where('id', $getAccount->user_id)->update($data);

        $reg = [ 
            'email'   => $data['email'],
            'gender'  => $data['gender'],
            'profileUrl' => $request->input('strava'),
            // 'active'    => true,
        ]; 
        EventVirRunREG::where('user_id', $getAccount->user_id)->where('ebib', $getAccount->ebib)->update($reg);

        $outsider = [           
            'gender'    => $data['gender'],
            'phone'     => $data['phone'],
            'company'   => $request->input('company')
        ];
        $getAccount->update($outsider);
        
        Session::flash('success', Lang::get('messages.data_custom', ['data' => $getAccount->user()->getFullName() . " account updated"]));  
        return redirect()->route('outsider/infiniteVirRun/run/adminRegistration');

    }

    public function delete($id)
    {
        $data = EventOutsider::find($id);

        $btName = "Inactive";
        if ($data->user()->active == false) {
            $btName = "Active";
        }
        return view('all_employee.Event.Outsider.modalDelete', compact(['data', 'btName']));
    }

    public function destroy(Request $request, $id)
    {
        $account = EventOutsider::find($id);

        if ($account->user()->active == true) {
            User::where('id', $account->user_id)->update([
                'active' => false
            ]);
    
            EventVirRunREG::where('ebib', $account->ebib)->where('user_id', $account->user_id)->update([
                'active' => false               
            ]); 
            $btName = "Inactive";
        } else {
            User::where('id', $account->user_id)->update([
                'active' => true
            ]);
    
            EventVirRunREG::where('ebib', $account->ebib)->where('user_id', $account->user_id)->update([
                'active' => true
            ]); 
            $btName = "Active";
        }       

        Session::flash('success', Lang::get('messages.data_custom', ['data' => $account->user()->getFullName() . " account " . $btName]));  
        return redirect()->route('outsider/infiniteVirRun/run/adminRegistration');
    }    
}
