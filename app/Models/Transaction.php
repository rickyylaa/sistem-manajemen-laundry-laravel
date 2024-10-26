<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 'pending') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Pending</span>';
        } elseif ($this->status == 'processed') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Diproses</span>';
        } elseif ($this->status == 'completed') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Selesai</span>';
        } elseif ($this->status == 'retrieved') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Diambil</span>';
        } else {
            return 'Status Tidak Dikenali';
        }
    }

    public function getAmountNonLabelAttribute()
    {
        if ($this->amount < $this->price - $this->detail->amount) {
            return 'Belum Lunas';
        } elseif ($this->amount == $this->price - $this->detail->amount) {
            return 'Lunas';
        } else {
            return 'Status Tidak Dikenali';
        }
    }

    public function getTotalAttribute()
    {
        return $this->price;
    }

    public function customer()
    {
        return $this->belongsTo(User::class)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            });
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function detail()
    {
        return $this->hasOne(TransactionDetail::class);
    }

    public function detailS()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
