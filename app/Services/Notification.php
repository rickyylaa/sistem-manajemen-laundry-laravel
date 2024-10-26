<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Notification
{
    public static function getNotifications()
    {
        return DB::table('notifications')
            ->join('users', 'notifications.customer_id', '=', 'users.id')
            ->join('reviews', 'notifications.review_id', '=', 'reviews.id')
            ->where('notifications.is_read', 0)
            ->select(
                'notifications.id as notification_id',
                'users.name as customer_name',
                'users.image as customer_image',
                'reviews.comment as comment'
            )
            ->orderBy('notifications.created_at', 'DESC')
            ->get();
    }
}
