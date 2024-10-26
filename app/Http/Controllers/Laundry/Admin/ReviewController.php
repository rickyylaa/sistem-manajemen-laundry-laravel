<?php

namespace App\Http\Controllers\Laundry\Admin;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class ReviewController extends Controller
{
    public function index()
    {
        $review = Review::with('customer')->orderBy('id', 'DESC')->paginate(3);
        $totalReview = Review::count();
        $averageRating = Review::avg('rating');

        $notifications = Notification::getNotifications();
        return view('laundry.admin.pages.review.index', compact('review', 'totalReview', 'averageRating', 'notifications'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'admin_id' => 'required|exists:users,id',
            'reply' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $review = Review::findOrFail($id);

            $review->update([
                'admin_id' => $request->admin_id,
                'reply' => $request->reply
            ]);

            DB::commit();

            Alert::toast('<span class="toast-information">Ulasan berhasil dibalas</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat membalas ulasan: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
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

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $review = Review::findOrFail($id);
            $review->delete();

            DB::commit();

            Alert::toast('<span class="toast-information">Ulasan berhasil dihapus</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menghapus ulasan: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }
}
