<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventVirRunREG extends Model
{
    protected $table = "event_virrun_reg";
    protected $guarded = [];

    public function getUser()
    {
        return User::where('id', $this->user_id)->first();
    }

    public function getEventVirRun()
    {
        return EventVirRun::where('ebib', $this->ebib)->get();
    }

}
