<?php

namespace App\Http\Controllers\Event;

use App\EventVirRun;
use App\EventVirRunREG;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;

class InfiniteRunController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'active']);
    }

    private function ebib()
    {
        $ebib = EventVirRunREG::where('periode', date('Y'))->orderBy('ebib', 'desc')->first();

        if ($ebib) {
            return $ebib->ebib;
        }
        return 25000;        
    }    

    private function adminVerify()
    {
        $admin = null;
        if (auth()->user()->id === 4) {            
            $admin = User::find(4);
        }
        return $admin;
    }

    public function index()
    { 
        $data = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->where('active', true)->first();
        $admin = $this->adminVerify();

        return view('all_employee.Event.InfiniteVirRun.index', compact(['data', 'admin']));
    }

    public function register()
    {
        return view('all_employee.Event.InfiniteVirRun.register');
    }

    public function postRegister(Request $request)
    {
        $rules = [
            'profile'   => 'required|url|min:10'
        ];

        $data = [
            'user_id' => auth()->user()->id,
            'ebib'    => $this->ebib() + 1,
            'active'  => true,
            'periode' => date('Y'),  
            'email'   => auth()->user()->email,
            'gender'  => auth()->user()->gender,
            'profileUrl' => $request->input('profile')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
			Session::flash('getError', Lang::get('messages.data_custom', ['data' => 'Invalid URL.']));    
            return redirect()->route('infiniteVirRun/index');
        }

        dd("re");
        EventVirRunREG::create($data);
        Session::flash('success', Lang::get('messages.data_custom', ['data' => 'You are registered']));    
        return redirect()->route('infiniteVirRun/index');
        
    }

    public function submission()
    {
        $data = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->where('active', true)->first();
        $admin = $this->adminVerify();

        $virrun = EventVirRun::where('ebib', $data->ebib)->whereIn('verify', [true, false])->whereDATE('created_at', date('Y-m-d'))->get()->count();
        $countVir = 2 - $virrun;

        $findEbib = 25002;

        if ($data->ebib == $findEbib) {
            $countVir = 999999;
        }

        if ($countVir == 0) {          
            Session::flash('reminder', Lang::get('messages.data_custom', ['data' => 'You can submit your activities again tomorrow. Thank you']));
            return redirect()->route('infiniteVirRun/index');
        }        
       
        return view('all_employee.Event.InfiniteVirRun.submission', compact(['data', 'admin', 'countVir']));
    }

    public function postSubmssion(Request $request)
    {

        $getUser = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->first();  
        $virrun = EventVirRun::where('ebib', $getUser)->whereIn('verify', [true, false])->whereDATE('created_at', date('Y-m-d'))->get()->count();  
        if (empty($getUser)) {
            Session::flash('getError', Lang::get('messages.data_custom', ['data' => 'You are not an event participant']));    
            return redirect()->route('infiniteVirRun/index');
        }
        $findEbib = 25002;
        if ($getUser->ebib != $findEbib) {
            if ($virrun == 2) {
                Session::flash('reminder', Lang::get('messages.data_custom', ['data' => 'You can do this again later. Thank you']));
                return redirect()->route('infiniteVirRun/index');
            }
        }       

        $rules = [
            'strava'    => 'required|url|min:10',
            'hours'     => 'required|numeric|max:12|min:0',
            'minute'    => 'required|numeric|max:60|min:0',
            'second'    => 'required|numeric|max:60|min:0',
            'distanceInput' => 'required|numeric|min:0'
        ];    
        
        $starva = $request->input('strava');
        $hours = $request->input('hours');
        $minute = $request->input('minute');
        $second = $request->input('second');
        $distance = $request->input('distanceInput');
        $distanceInput = str_replace(',', '.', $distance);

        $distanceFloat = (float) $distanceInput;      
        $timeString = sprintf('%02d:%02d:%02d', $hours, $minute, $second); 

        function truncateDecimal($number, $decimals = 2) {
            $factor = pow(10, $decimals);               // 10^2 = 100
            return floor($number * $factor) / $factor;  // Mengalikan, floor ke bawah, lalu bagi kembali
        }

        $distanceTruncated = truncateDecimal($distanceFloat, 2);
        $distance = number_format($distanceTruncated, 2, '.', '');

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {            
			return redirect()->route('infiniteVirRun/submission')
				->withErrors($validator)
				->withInput();
        }
      
        if ($timeString == "00:00:00") {
            Session::flash('getError', Lang::get('messages.data_custom', ['data' => 'Please check your moving time. [00:00:00]']));
            return redirect() ->route('infiniteVirRun/submission');
        }
        $data = [
            'ebib'          => $getUser->ebib,
            'mvtime'        => $timeString,
            'distance'      => $distance,
            'url'           => $starva
        ];       
 
        EventVirRun::create($data);  
        Session::flash('success', Lang::get('messages.data_custom', ['data' => 'You have made the submission, and we will review it promptly.'])); 
        return redirect()->route('infiniteVirRun/submission/list');
    }

    public function listedSubmission()
    {
        $data = EventVirRunREG::where('user_id', auth()->user()->id)->where('periode', date('Y'))->where('active', true)->first();
        $admin = $this->adminVerify();

        return view('all_employee.Event.InfiniteVirRun.listSubmission', compact(['data', 'admin']));
    }

    public function datatablesListedSubmission()
    {
        $ebib = EventVirRunREG::where('user_id', auth()->user()->id)->where('active', true)->where('periode', date('Y'))->first();

        $query = EventVirRun::where('ebib', $ebib->ebib)->Where('delete',false)->orderBy('id', 'desc')->get();

        return Datatables::of($query)
            ->addIndexColumn()
            ->addColumn('stravaURL', function (EventVirRun $event) {                                
                $result = "<a href='".$event->url."' class='btn btn-xs btn-info' title='strava profile' target='_blank' rel='noopener noreferrer' >Strava Link</a>";
                return $result;
            })
            ->rawColumns(['staravaURL'])
            ->editColumn('verify', function (EventVirRun $event) {
                if ($event->verify == 1) {
                    return "Verified";
                }          
                if ($event->verify == 2) {
                    return "Rejected";
                }                
            })
            ->make(true);
    }

    public function dataTablesMale()
    {
        $query = EventVirRunREG::where('periode', date('Y'))->where('active', true)->where('gender', 'Male')->get()
                    ->map(function($event) {
                        $getVirRun = $event->getEventVirRun()->where('verify', 1)->where('delete', false);
                        $totalDistance = $getVirRun->pluck('distance')->sum();
                        $event->activities = $getVirRun->pluck('id')->count();
                        $event->distance_temp = $totalDistance;
                        return $event;
                    });
        $sorted = $query->sortByDesc('distance_temp')->values();
        $sorted = $sorted->map(function($event, $index) {
            $event->rank = $index + 1; // rank dimulai dari 1
            if ($event->distance_temp >= 25) {
                $event->row_class = $event->rank == 1 ? 'top-rank' :
                ($event->rank == 2 ? 'winner' :
                ($event->rank == 3 ? 'runner-up' : ''));
            }
            return $event;
        });
    
        return Datatables::of($sorted)
            ->addIndexColumn()
            ->addColumn('fullname', function(EventVirRunREG $event) {          
                if (empty($event->getUser())) {
                    return null;
                }
                $fullname = $event->getUser()->getFullName();
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
            ->addColumn('top-rank', function(EventVirRunREG $event) {
                return $event->rank;
            })         
            ->addColumn('duration', function(EventVirRunREG $event) {
                $timeStrings = $event->getEventVirRun()->where('verify', 1)->where('delete', false)->pluck('mvtime'); 
                $totalSeconds = 0;

                foreach ($timeStrings as $time) {
                    list($hours, $minutes, $seconds) = explode(':', $time);
                    $totalSeconds += ((int)$hours * 3600) + ((int)$minutes * 60) + (int)$seconds;
                }

                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;

                $result = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                return $result;
            })            
            ->addColumn('progress', function (EventVirRunREG $event) {
                $distance = $event->distance_temp;              
                $maxDistance = 25;
                $divide = $distance / $maxDistance;
                $percantage = $divide * 100;

                if ($percantage > 100) {
                    $percantage = 100;
                }

                $result = $percantage."%";
                $event->percantage_temp = $percantage;
                return $result;
                
            })
            ->addColumn('status', function (EventVirRunREG $event) {
                $percantage = $event->percantage_temp;

                if ($percantage == "100") {
                    return "Finisher";
                }

                return null;
            })
            ->addColumn('entity', function (EventVirRunREG $event) {
                $entity = $event->getEventOutsider();
                if ($entity) {
                    $return = $entity->company;
                } else {
                    $return = "Infinite Studios";
                }
                return $return;
            })           
            ->rawColumns(['fullname'])
            ->make(true);
    }   

    public function dataTablesFemale()
    {
        $query = EventVirRunREG::where('periode', date('Y'))->where('active', 1)->where('gender', 'Female')->get()
                    ->map(function($event) {
                        $getVirRun = $event->getEventVirRun()->where('verify', 1)->where('delete', false);
                        $totalDistance = $getVirRun->pluck('distance')->sum();
                        $event->activities = $getVirRun->pluck('id')->count();
                        $event->distance_temp = $totalDistance;
                        return $event;
                    });
        $sorted = $query->sortByDesc('distance_temp')->values();
        $sorted = $sorted->map(function($event, $index) {
            $event->rank = $index + 1; // rank dimulai dari 1
            if ($event->distance_temp >= 25) {
                $event->row_class = $event->rank == 1 ? 'top-rank' :
                ($event->rank == 2 ? 'winner' :
                ($event->rank == 3 ? 'runner-up' : ''));
            }
            return $event;
        });
    
        return Datatables::of($sorted)
            ->addIndexColumn()
            ->addColumn('fullname', function(EventVirRunREG $event) {
                if (empty($event->getUser())) {
                    return null;
                }
                $fullname = $event->getUser()->getFullName();              

                $names = explode(' ', $fullname);
                $initials = '';
                if (count($names) > 1) {
                    $initials = strtoupper(substr($names[0],0,1) . substr($names[1],0,1));
                } else {
                    $initials = strtoupper(substr($names[0], 0, 2));
                }
            
                $colors = ['#7dc7ff', '#a6d8ca', '#f9bb87', '#ffabc3', '#b09cff', '#ffd36e'];
                $color = $colors[array_rand($colors)];
            
                $avatarStyle = "width: 30px; height: 30px; border-radius: 50%; background: $color; 
                                display: flex; justify-content: center; align-items: center;
                                color: white; font-weight: 700; font-size: 14px; margin-right: 10px;";
            
                $firstName = $names[0];
                $lastName = count($names) > 1 ? implode(' ', array_slice($names, 1)) : '';
            
                // Gunakan flex container agar avatar dan nama sejajar tengah vertikal
                return '<div style="display: flex; align-items: center;">
                            <span style="'.$avatarStyle.'">'. $initials .'</span>
                            <div style="line-height: 1.1;">
                                <div>'.$firstName.'</div>
                                <div style="font-weight: 500; color: #777;">'.$lastName.'</div>
                            </div>
                        </div>';
            })          
            ->addColumn('top-rank', function(EventVirRunREG $event) {
                return $event->rank;
            })         
            ->addColumn('duration', function(EventVirRunREG $event) {
                $timeStrings = $event->getEventVirRun()->where('verify', 1)->where('delete', false)->pluck('mvtime'); 
                $totalSeconds = 0;

                foreach ($timeStrings as $time) {
                    list($hours, $minutes, $seconds) = explode(':', $time);
                    $totalSeconds += ((int)$hours * 3600) + ((int)$minutes * 60) + (int)$seconds;
                }

                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;

                $result = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                return $result;
            })            
            ->addColumn('progress', function (EventVirRunREG $event) {
                $distance = $event->distance_temp;              
                $maxDistance = 25;
                $divide = $distance / $maxDistance;
                $percantage = $divide * 100;

                if ($percantage > 100) {
                    $percantage = 100;
                }

                $result = $percantage."%";
                $event->percantage_temp = $percantage;
                return $result;
                
            })
            ->addColumn('status', function (EventVirRunREG $event) {
                $percantage = $event->percantage_temp;

                if ($percantage == "100") {
                    return "Finisher";
                }

                return null;
            })
            ->addColumn('entity', function (EventVirRunREG $event) {
                $entity = $event->getEventOutsider();
                if ($entity) {
                    $return = $entity->company;
                } else {
                    $return = "Infinite Studios";
                }
                return $return;
            })  
            ->rawColumns(['fullname'])
            ->make(true);
    }   

    public function dpfECert($id)
    {           
        $user = User::find($id);
        $idEvent = $user->eventRegister()->ebib;
        $filename = $idEvent.".pdf";
        $event = EventVirRun::where('ebib', $idEvent)->where('verify', true)->where('delete', false)->get();       
        $timeStrings = $event->pluck('mvtime');

        $totalSeconds = 0;
        foreach ($timeStrings as $time) {
            list($hours, $minutes, $seconds) = explode(':', $time);
            $totalSeconds += ((int)$hours * 3600) + ((int)$minutes * 60) + (int)$seconds;
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);  
        $distance = $event->pluck('distance')->sum();              

        $name = $user->getFullName();
            $length = strlen($name);
            // dd($length);

            if ($length <= 30) {             
                $marginTop = 670;
            } else {
                $marginTop = 605;
            } 
        // ini_set('memory_limit', '64M');
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('all_employee.Event.InfiniteVirRun.e-cert2025', compact(['user', 'marginTop', 'duration', 'distance']));   
        return $pdf->stream($filename);   
    }

    
    
}
