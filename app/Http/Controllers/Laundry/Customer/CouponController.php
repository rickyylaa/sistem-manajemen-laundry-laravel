<?php

namespace App\Http\Controllers\Laundry\Customer;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $coupon = Coupon::where('temporary', $userId)->orderBy('id', 'ASC')->paginate(10);
        return view('laundry.customer.pages.coupon.index', compact('coupon'));
    }
}
