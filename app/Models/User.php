<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'password',
        'image',
        'gender',
        'phone',
        'address',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function getStatusLabelAttribute()
    {
        if ($this->status == 'active') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Aktif</span>';
        } elseif ($this->status == 'inactive') {
            return '<span class="badge bg-dark bg-opacity-10 text-dark rounded-0">Tidak Aktif</span>';
        } else {
            return 'Status Tidak Dikenali';
        }
    }

    public function getGenderLabelAttribute()
    {
        if ($this->gender == 'male') {
            return 'Laki-Laki';
        } elseif ($this->gender == 'female') {
            return 'Perempuan';
        } else {
            return 'Jenis Kelamin Tidak Dikenali';
        }
    }

    public function coupon()
    {
        return $this->hasMany(Coupon::class, 'customer_id');
    }
    public function detail()
    {
        return $this->hasMany(TransactionDetail::class, 'customer_id');
    }

    public function review()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function getReviewsCountAttribute()
    {
        return $this->review()->count();
    }

    public function notification()
    {
        return $this->hasMany(Notification::class, 'customer_id');
    }
}
