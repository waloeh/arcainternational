<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categories extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'name',
        'limit_per_month',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    // Relasi ke reimbursements
    public function reimbursements()
    {
        return $this->hasMany(Reimbursements::class, 'category_id', 'category_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->timezone('Asia/Jakarta')
            ->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)
            ->timezone('Asia/Jakarta')
            ->format('Y-m-d H:i:s');
    }
}
