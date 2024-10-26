<?php

namespace App\Http\Controllers\Laundry\Employee;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        $searchQuery = $request->q;
        $status = $request->status;

        if (!empty($searchQuery)) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('customer_name', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('customer_phone', 'LIKE', '%' . $searchQuery . '%')
                    ->orWhere('amount', 'LIKE', '%' . $searchQuery . '%');
            });
        }

        if (!is_null($status) && $status !== '') {
            $query->where('status', $status);
        }

        $coupon = $query->orderBy('id', 'DESC')->paginate(30)->appends($request->except(['page', '_token']));

        $notification = Notification::getNotifications();
        return view('laundry.employee.pages.coupon.index', compact('coupon', 'notification'));
    }

    public function receive($id)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::with(['customer'])->where('id', $id)->first();

            if ($coupon->status == 'not used') {
                $coupon->update([
                    'temporary' => NULL,
                    'status' => 'used'
                ]);
            }

            DB::commit();

            Alert::toast('<span class="toast-information">Kupon berhasil diterima</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menerima kupon: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            DB::commit();

            Alert::toast('<span class="toast-information">Kupon berhasil dihapus</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menghapus kupon: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }
}
