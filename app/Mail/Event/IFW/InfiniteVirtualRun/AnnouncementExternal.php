<?php

namespace App\Mail\Event\IFW\InfiniteVirtualRun;

use App\EventOutsider;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnnouncementExternal extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->data;

        $external = User::with(['eventExternal'])->select(['users.id', 'users.first_name', 'users.last_name', 'users.email'])->where('active', true)->where('evnt_member_outsider', true)->get();
        $email = $external->pluck('email');                

        return $this->bcc($email)->from('infinitevirtualrun@infinitestudios.id', 'InfiniteVirtualRun')->view('all_employee.Event.Outsider.Email.mailAnnouncement', compact(['data']));
    }
}
