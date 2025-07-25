<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventOutsider extends Model
{
    protected $table = "event_outsider";
    protected $guarded = [];

    public function user()
    {
        return User::where('id', $this->user_id)->first();
    }

    public function eventReg()
    {
        return EventVirRunREG::where('ebib', $this->ebib)->first();
    }   
}
