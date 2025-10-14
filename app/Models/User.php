<?php

namespace App\Models;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use App\Notifications\CxoUserWelcomeNotification;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasSuperAdmin;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'plan_id',
        'email',
        'password',
        'profile_picture',
        'contact',
        'address',
        'spotlight'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function member()
    {
        return $this->hasOne(Member::class)->with('plan');
    }
    public function MemberFeed()
    {
        return $this->hasOne(MemberFeed::class);
    }
    public function canAccessPanel(Panel $panel): bool
    {
        // Super-admin and admin can access all panels
        return $this->hasAnyRole(['super-admin', 'admin']);
    }

    function events()
    {
        return $this->hasMany(Event::class);
    }
    public function spotlight()
    {
        return $this->hasOne(\App\Models\Spotlight::class);
    }
    public function getProfilePictureUrlAttribute()
    {
        if ($this->profile_picture) {
            return Storage::url($this->profile_picture);
        }

        return asset('images/default-avatar.png'); // fallback
    }
}
