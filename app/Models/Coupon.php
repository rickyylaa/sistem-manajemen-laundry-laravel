<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 'used') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Sudah Digunakan</span>';
        } elseif ($this->status == 'not used') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Belum Digunakan</span>';
        } else {
            return 'Status Tidak Dikenali';
        }
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            });
    }

    public function detail()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
