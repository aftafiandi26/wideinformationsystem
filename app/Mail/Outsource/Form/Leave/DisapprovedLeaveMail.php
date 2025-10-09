<?php

namespace App\Mail\Outsource\Form\Leave;

use App\Leave;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DisapprovedLeaveMail extends Mailable
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
        $leave = Leave::find($this->data);
        $keu = "disapproved";
        return $this->view('leave.outsources.leave.formLeave.mails.approved', compact('leave', 'keu'))->subject('[Disapproved] Leave Application - WIS');
    }
}
