<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reimbursements extends Model
{
    use SoftDeletes;

    protected $table = 'reimbursements';
    protected $primaryKey = 'id_reimburse';

    protected $fillable = [
        'title',
        'description',
        'amount',
        'category_id',
        'user_id',
        'status',
        'file_name',
        'submited_at',
        'approved_at',
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'category_id');
    }

    // Relasi ke Users
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
