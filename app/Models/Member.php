<?php

namespace App\Models;

use App\Notifications\MemberWelcomeNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class Member extends Model
{
    use Notifiable;
    protected $fillable = [
        'full_name',
        'email',
        'contact',
        'designation',
        'city',
        'zip_code',
        'membership_type',
        'payment_method',
        'dp',
        'user_id',
        'plan_id',
        'status'
    ];

    public function enterpriseDetails()
    {
        return $this->hasOne(EnterpriseMemberDetail::class);
    }

    public function goldDetails()
    {
        return $this->hasOne(GoldMemberDetail::class);
    }

    public function silverDetails()
    {
        return $this->hasOne(SilverMemberDetail::class);
    }
    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class, 'plan_id');
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    //country for member
    function country()
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id');
    }

    //member state
    function state(){
        return $this->belongsTo(\App\Models\State::class, 'state_id');
    }

    //member city
    function city(){
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }

    //Inside this functon we enter member also in user table and send welcome email to member 
    protected static function booted()
    {
        
        static::created(function ($member) {

            if (!$member->user_id) {
                $user = null;
                
                info("register member email");
                info($member->email);

                if ($member->email) {
                    $user = \App\Models\User::where('email', $member->email)->first();
                }

                if (!$user) {
                    $randomPassword = Str::random(8);
                    

                    $user = \App\Models\User::create([
                        'name'     => $member->full_name,
                        'email'    => $member->email ?: 'member_' . uniqid() . '@example.com',
                        'password' => bcrypt($randomPassword),
                    ]);
                    $userIDEncrypted = urlencode(Crypt::encryptString($user->id));
                    $verificationURL = url('/member/verify?token=' . $userIDEncrypted);
        

                    // Send notification instead of mailable
                    if ($member->email) {
                        info("sending email");
                        $user->notify(new MemberWelcomeNotification($verificationURL ));
                    }
                }

                $member->update(['user_id' => $user->id]);
            }
        });

    }
    public function hasPlan(): bool
{
    return !is_null($this->plan_id);
}
}
