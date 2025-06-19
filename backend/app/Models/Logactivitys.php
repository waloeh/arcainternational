<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class LogActivitys extends Model
{
    protected $table = 'log_activitys';
    protected $primaryKey = 'log_id';
    public $timestamps = false; // Karena kita hanya punya `created_at`

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'created_at',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
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
