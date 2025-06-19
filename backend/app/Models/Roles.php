<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name',
    ];

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
