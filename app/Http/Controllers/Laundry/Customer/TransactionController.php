<?php

namespace App\Http\Controllers\Laundry\Customer;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $customerId = Auth::id();

        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ', request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $transaction = Transaction::whereHas('detail', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })->with(['customer', 'detail'])->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'DESC')->get();
        return view('laundry.customer.pages.transaction.index', compact('transaction'));
    }

    public function transactionReceipt($invoice)
    {
        $transaction = Transaction::with('detail')->where('invoice', $invoice)->firstOrFail();
        return view('laundry.customer.pages.transaction.receipt', compact('transaction'));
    }
}
