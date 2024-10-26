<?php

namespace App\Http\Controllers\Laundry\Admin;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Services\Notification;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\PDF as PDF;

class ReportController extends Controller
{
    public function index()
    {
        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');

        if (request()->date != '') {
            $date = explode(' - ',request()->date);
            $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
            $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';
        }

        $transaction = Transaction::with(['customer'])->whereBetween('created_at', [$start, $end])->where('status', 'retrieved')->get();
        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.report.index', compact('transaction', 'notification'));
    }

    public function pdf($dateRange)
    {
        $date = explode('+', $dateRange);
        $start = Carbon::parse($date[0])->format('Y-m-d') . ' 00:00:01';
        $end = Carbon::parse($date[1])->format('Y-m-d') . ' 23:59:59';

        $transaction = Transaction::with(['customer', 'detail', 'package'])->whereBetween('created_at', [$start, $end])->where('status', 'retrieved')->get();
        return view('laundry.admin.pages.report.pdf', compact('transaction', 'date'));
    }
}
