<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    // Relasi ke Log Activity
    public function logs()
    {
        return $this->hasMany(LogActivitys::class, 'user_id', 'user_id');
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        // Deteksi semua kolom yang berakhiran "_at"
        if (Str::endsWith($key, '_at') && !is_null($value)) {
            return Carbon::parse($value)
                ->timezone('Asia/Jakarta')
                ->format('Y-m-d H:i:s');
        }

        return $value;
    }
}
