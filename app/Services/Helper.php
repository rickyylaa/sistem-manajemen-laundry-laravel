<?php

namespace App\Services;

class Helper
{
    public static function translateRole($roleName)
    {
        $roles = [
            'admin' => 'Admin',
            'owner' => 'Pemilik',
            'employee' => 'Karyawan',
            'customer' => 'Pelanggan'
        ];

        return $roles[$roleName] ?? ucfirst($roleName);
    }
}
