<?php

namespace App\Http\Controllers\Laundry\Customer;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Review;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class DashboardController extends Controller
{
    public function index()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $customerId = auth()->user()->id;
        $transaction = Transaction::whereHas('detail', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })->with(['customer', 'detail'])
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->orderBy('created_at', 'DESC')
            ->get();

        $package = Package::orderBy('id', 'ASC')->where('status', 1)->paginate(10);
        $review = Review::with('customer')->orderBy('id', 'DESC')->paginate(3);
        $totalReview = Review::count();
        $averageRating = Review::avg('rating');
        return view('laundry.customer.pages.dashboard.index', compact('transaction', 'package', 'review', 'totalReview', 'averageRating'));
    }

    public function reviewStore(Request $request, Review $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $customer = auth()->user();

            $newReview = $review->create([
                'customer_id' => $customer->id,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);

            Notification::create([
                'customer_id' => $newReview->customer_id,
                'review_id' => $newReview->id,
                'is_read' => 0
            ]);

            $options = [
                'cluster' => 'ap1',
                'useTLS' => true
            ];

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $data = ['customer_id' => $customer->id];
            $pusher->trigger('my-channel', 'my-event', $data);

            DB::commit();

            Alert::toast('<span class="toast-information">Ulasan berhasil ditulis</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menulis ulasan: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function reviewLoadMore(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 3;

        $reviews = Review::with('customer', 'admin')->orderBy('id', 'DESC')
            ->paginate($perPage, ['*'], 'page', $page);

        $reviewItems = $reviews->items();
        foreach ($reviewItems as $review) {
            $review->customer->reviews_count = $review->customer->review()->count();
        }

        return response()->json([
            'data' => $reviewItems,
            'next_page_url' => $reviews->nextPageUrl()
        ]);
    }
}
