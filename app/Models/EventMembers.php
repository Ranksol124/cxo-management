<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventMembers extends Model
{
  

    protected $table = 'members_event_status';
    protected $fillable = [
        'member_id',
        'event_id',
        'status'

    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}
