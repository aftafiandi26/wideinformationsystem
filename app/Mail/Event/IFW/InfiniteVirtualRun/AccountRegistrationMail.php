<?php

namespace App\Mail\Event\IFW\InfiniteVirtualRun;

use App\EventOutsider;
use App\EventVirRunREG;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cookie;

class AccountRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $id;

    public function __construct($id)
    {      
        $this->id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $param = $this->id;
        $user = User::find($param['id']);
        
        $eventREG = EventVirRunREG::where('user_id', $user->id)->where('periode', date('Y'))->first();
        $account = EventOutsider::where('user_id', $user->id)->first();

        $fullname = $user->getFullName();
        $username = $user->username;
        $img = asset('assets/logo/Infinite Studios_verysmall.png');
        $password = $param['pass'];

        return $this->to($eventREG->email)->from('infinitevirtualrun@infinitestudios.id', 'InfiniteVirtualRun')->subject('InfiniteVirtualRun2025 Account')->view('all_employee.Event.Outsider.Email.accountRegis', compact(['fullname', 'username', 'img', 'password']));      
    }
}
