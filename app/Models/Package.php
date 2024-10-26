<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'price', 'status'];

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

    public function detail()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
