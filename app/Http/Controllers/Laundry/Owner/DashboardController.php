<?php

namespace App\Http\Controllers\Laundry\Owner;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $transactions = Transaction::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(price) as total_price')
            ->whereYear('created_at', $currentYear)
            ->where('status', '=', 'retrieved')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $totalIncome = Transaction::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '=', 'retrieved')
            ->sum('price');

        $totalTransactions = Transaction::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', '=', 'retrieved')
            ->count();

        $chartData = [];
        foreach ($transactions as $data) {
            $year = $data->year;
            $month = $data->month;
            $totalPrice = $data->total_price;

            $chartData[] = [
                'year' => $year,
                'month' => $month,
                'total_price' => $totalPrice,
            ];
        }

        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        })->get();

        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        return view('laundry.owner.pages.dashboard.index', compact('transactions', 'totalIncome', 'totalTransactions', 'chartData', 'employees', 'customers'));
    }
}
