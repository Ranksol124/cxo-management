<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;
use App\Traits\Encryptable;

class Event extends Model
{
    use HasFactory, Encryptable;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'link',
        'event_status',
        'event_type',
        'event_image',
        'website_preview',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function title(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }

    protected function description(): Attribute
    {
        return $this->makeEncryptableAttribute();
    }
}
