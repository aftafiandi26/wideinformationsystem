<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventVirRun extends Model
{
    protected $table = "event_virrun";
    protected $guarded = [];

    public function EventRegister()
    {
        return EventVirRunREG::where('ebib', $this->ebib)->first();
    }

    /**
     * Get all of the comments for the EventVirRun
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function register()
    {
        return $this->hasMany(EventVirRunREG::class, 'ebib', 'ebib');
    }
}
