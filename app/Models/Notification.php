<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'review_id', 'is_read'];

    public function customer()
    {
        return $this->belongsTo(User::class)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'customer');
            });
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
