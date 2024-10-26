<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
