<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'admin_id', 'rating', 'comment', 'reply'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            });
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            });
    }
}
