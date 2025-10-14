<?php

namespace App\Models;
use App\Models\User;



use Illuminate\Database\Eloquent\Model;

class SpotLight extends Model
{
    protected $table = 'spot_lights';
    protected static ?string $model = User::class;
    protected $fillable = [
        'user_id',
        'spotlight',

    ];
    public function spotlight()
    {
        return $this->hasOne(SpotLight::class);
    }

}
