<?php

namespace App\Http\Controllers\Laundry\Employee;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $currentDate = Carbon::now()->toDateString();

        $transactions = Transaction::selectRaw('DATE(created_at) as date, SUM(price) as total_price')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', '=', 'retrieved')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'asc')
            ->get();

        $dailyIncome = Transaction::whereDate('created_at', $currentDate)
            ->where('status', '=', 'retrieved')
            ->sum('price');

        $dailyTransaction = Transaction::whereDate('created_at', $currentDate)
            ->where('status', '=', 'retrieved')
            ->count();

        $chartData = [];
        foreach ($transactions as $data) {
            $date = $data->date;
            $totalPrice = $data->total_price;

            $chartData[] = [
                'date' => $date,
                'total_price' => $totalPrice,
            ];
        }

        $employees = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        })->get();

        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        return view('laundry.employee.pages.dashboard.index', compact('transactions', 'dailyIncome', 'dailyTransaction', 'chartData', 'employees', 'customers'));
    }
}
