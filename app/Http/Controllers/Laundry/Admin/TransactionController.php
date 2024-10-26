<?php

namespace App\Http\Controllers\Laundry\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $package = Package::where('status', 'active')->orderBy('id', 'ASC')->get();
        $customer = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->orderBy('name', 'ASC')->get();

        $query = $request->input('q');
        $status = $request->input('status');

        $transaction = Transaction::orderBy('id', 'DESC')->with('detail');

        if (!empty($query)) {
            $transaction = $transaction->where(function($q) use ($query) {
                $q->where('invoice', 'LIKE', '%' . $query . '%')
                    ->orWhere('customer_name', 'LIKE', '%' . $query . '%')
                    ->orWhere('customer_phone', 'LIKE', '%' . $query . '%');
            });
        }

        if ($status !== null && $status !== '') {
            $transaction = $transaction->where('status', $status);
        }

        $transaction = $transaction->paginate(30)->appends($request->except(['page', '_token']));

        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.transaction.index', compact('transaction', 'customer', 'package', 'notification'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:packages,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'date' => 'required|date',
            'weight' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'amount' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $lastTransaction = Transaction::orderBy('id', 'DESC')->first();
            $lastInvoiceNumber = $lastTransaction ? intval(substr($lastTransaction->invoice, 4)) : 0;
            $invoiceNumber = str_pad($lastInvoiceNumber + 1, 3, '0', STR_PAD_LEFT);
            $invoice = 'TRC-' . $invoiceNumber;

            $customer = User::findOrFail($request->customer_id);
            $package = Package::findOrFail($request->package_id);

            $date = Carbon::parse($request->date)->format('d F Y');
            $day = Carbon::parse($request->date)->format('l');

            $couponValue = $request->filled('coupon_id') ? 'used' : 'not used';

            $transaction = Transaction::create([
                'invoice' => $invoice,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'package' => $package->type,
                'day' => $day,
                'date' => $date,
                'weight' => $request->weight,
                'price' => $request->price,
                'coupon' => $couponValue,
                'status' => 'pending'
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'customer_id' => $request->customer_id,
                'package_id' => $request->package_id,
                'coupon_id' => $request->coupon_id,
                'amount' => $request->amount ?? 0
            ]);

            if ($request->filled('coupon_id')) {
                $coupon = Coupon::findOrFail($request->coupon_id);
                $coupon->update(['status' => 'used']);
            }

            $coupon = Coupon::where('temporary', $customer->id)->where('amount', '<', 10)->where('status', 'not used')->first();
            if ($coupon) {
                $coupon->increment('amount');
            } else {
                Coupon::create([
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'amount' => 1,
                    'status' => 'not used',
                    'temporary' => $customer->id
                ]);
            }

            $totalPrice = $request->weight * $package->price;
            $paidAmount = $request->amount ?? 0;
            $remainingAmount = $totalPrice - $paidAmount;

            $message1 = "Halo {$customer->name}, transaksi Anda dengan nomor faktur {$invoice} telah berhasil dibuat.\n";
            $message1 .= "Rincian transaksi:\n";
            $message1 .= "- Paket: {$package->type}\n";
            $message1 .= "- Tanggal: {$date} ({$day})\n";
            $message1 .= "- Berat: {$request->weight} kg\n";
            $message1 .= "- Total Harga: IDR " . number_format($totalPrice) . "\n";

            if ($paidAmount > 0) {
                $message1 .= "- Jumlah yang dibayarkan: IDR " . number_format($paidAmount) . "\n";
                $message1 .= "- Sisa yang harus dibayar: IDR " . number_format($remainingAmount) . "\n";
            }

            $message1 .= "Terima kasih telah menggunakan layanan kami.";

            $this->sendMessage($customer->phone, $message1);

            DB::commit();

            session()->flash('invoice', $transaction->invoice);
            Alert::toast('<span class="toast-information">Transaksi berhasil dibuat</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'package_id' => 'required|exists:packages,id',
            'date' => 'required|date',
            'weight' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'amount' => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $lastTransaction = Transaction::orderBy('id', 'DESC')->first();
            $lastInvoiceNumber = $lastTransaction ? intval(substr($lastTransaction->invoice, 4)) : 0;
            $invoiceNumber = str_pad($lastInvoiceNumber + 1, 3, '0', STR_PAD_LEFT);
            $invoice = 'TRC-' . $invoiceNumber;

            $package = Package::findOrFail($request->package_id);

            $date = Carbon::parse($request->date)->format('d F Y');
            $day = Carbon::parse($request->date)->format('l');

            $nameParts = explode(' ', $request->name);
            $username = Str::slug($nameParts[0]) . rand(0, 999);
            $password = Str::slug($nameParts[0]) . rand(0, 999);

            $customer = User::create([
                'username' => $username,
                'name' => $request->name,
                'password' => Hash::make($password),
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'active'
            ])->assignRole('customer');

            $transaction = Transaction::create([
                'invoice' => $invoice,
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'package' => $package->type,
                'day' => $day,
                'date' => $date,
                'weight' => $request->weight,
                'price' => $request->price,
                'coupon' => 'not used',
                'status' => 'pending'
            ]);

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'customer_id' => $customer->id,
                'package_id' => $package->id,
                'coupon_id' => 0,
                'amount' => $request->amount,
            ]);

            $coupon = Coupon::where('temporary', $customer->id)->where('amount', '<', 10)->where('status', 0)->first();
            if ($coupon) {
                $coupon->increment('amount');
            } else {
                Coupon::create([
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'customer_phone' => $customer->phone,
                    'amount' => 1,
                    'status' => 'not used',
                    'temporary' => $customer->id
                ]);
            }

            $totalPrice = $request->weight * $package->price;
            $paidAmount = $request->amount ?? 0;
            $remainingAmount = $totalPrice - $paidAmount;

            $message1 = "Halo " . strtoupper($request->name) . ", this is your Username and Password.\n";
            $message1 .= "Username: $username\nPassword: $password\n\n";
            $message1 .= "You can log in to our website at " . url('/');

            $message2 = "Halo {$customer->name}, transaksi Anda dengan nomor faktur {$invoice} telah berhasil dibuat.\n";
            $message2 .= "Rincian transaksi:\n";
            $message2 .= "- Paket: {$package->type}\n";
            $message2 .= "- Tanggal: {$date} ({$day})\n";
            $message2 .= "- Berat: {$request->weight} kg\n";
            $message2 .= "- Total Harga: IDR " . number_format($totalPrice) . "\n";

            if ($paidAmount > 0) {
                $message2 .= "- Jumlah yang dibayarkan: IDR " . number_format($paidAmount) . "\n";
                $message2 .= "- Sisa yang harus dibayar: IDR " . number_format($remainingAmount) . "\n";
            }

            $message2 .= "Terima kasih telah menggunakan layanan kami.";

            $this->sendMessage($request->phone, $message1);
            $this->sendMessage($request->phone, $message2);

            DB::commit();

            session()->flash('invoice', $transaction->invoice);
            Alert::toast('<span class="toast-information">Transaksi berhasil dibuat</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat membuat transaksi: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function processed($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['package'])->where('invoice', $invoice)->first();

            if (!$transaction) {
                Alert::toast('<span class="toast-information">Transaksi tidak ditemukan</span>')->hideCloseButton()->padding('25px')->toHtml();
            }

            if ($transaction->status == 'pending') {
                $transaction->update([
                    'status' => 'processed'
                ]);
            }

            DB::commit();

            Alert::toast('<span class="toast-information">Transaksi berhasil diperbarui</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function complete($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['package', 'detail', 'detail.customer'])->where('invoice', $invoice)->first();

            if (!$transaction) {
                Alert::toast('<span class="toast-information">Transaksi tidak ditemukan</span>')->hideCloseButton()->padding('25px')->toHtml();
            }

            if ($transaction->status == 'processed') {
                $transaction->update([
                    'status' => 'completed'
                ]);

                $date = Carbon::parse($transaction->date)->format('d F Y');
                $day = Carbon::parse($transaction->date)->format('l');

                $package = $transaction->detail->package;
                $customer = $transaction->detail->customer;

                $totalPrice = $transaction->weight * $package->price;
                $paidAmount = $transaction->detail->amount ?? 0;
                $remainingAmount = $totalPrice - $paidAmount;

                $message1 = "Halo {$customer->name}, pakaian Anda dengan nomor faktur {$invoice} telah dilaundry.\n";
                $message1 .= "Rincian transaksi:\n";
                $message1 .= "- Paket: {$package->type}\n";
                $message1 .= "- Tanggal: {$date} ({$day})\n";
                $message1 .= "- Berat: {$transaction->weight} kg\n";
                $message1 .= "- Total Harga: IDR " . number_format($totalPrice) . "\n";

                if ($paidAmount > 0) {
                    $message1 .= "- Jumlah yang dibayarkan: IDR " . number_format($paidAmount) . "\n";
                    $message1 .= "- Sisa yang harus dibayar: IDR " . number_format($remainingAmount) . "\n";
                }

                $message1 .= "Terima kasih telah menggunakan layanan kami.";

                $this->sendMessage($customer->phone, $message1);
            }

            DB::commit();

            Alert::toast('<span class="toast-information">Transaksi berhasil diperbarui</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function retrieved($invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with(['package', 'detail'])->where('invoice', $invoice)->first();

            if (!$transaction) {
                Alert::toast('<span class="toast-information">Transaksi tidak ditemukan</span>')->hideCloseButton()->padding('25px')->toHtml();
            }

            if ($transaction->status == 'completed') {
                $transaction->update([
                    'status' => 'retrieved'
                ]);

                $transaction->detail()->update([
                    'amount' => $transaction->price,
                ]);
            }

            DB::commit();

            Alert::toast('<span class="toast-information">Transaksi berhasil diperbarui</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat memperbarui transaksi: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function destroy(string $invoice)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::where('invoice', $invoice)->firstOrFail();
            TransactionDetail::where('transaction_id', $transaction->id)->delete();
            $transaction->delete();

            DB::commit();

            Alert::toast('<span class="toast-information">Transaksi berhasil dihapus</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menghapus transaksi: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    public function getCoupons($customer_id)
    {
        $coupons = Coupon::where('customer_id', $customer_id)
            ->where('status', '=', 'not used')
            ->where('amount', '=', 10)
            ->get(['id', 'amount']);

        return response()->json($coupons);
    }

    public function transactionReceipt($invoice)
    {
        $transaction = Transaction::with('detail')->where('invoice', $invoice)->firstOrFail();
        return view('laundry.admin.pages.transaction.receipt', compact('transaction'));
    }

    private function sendMessage($phone, $message)
    {
        $token = "yMo#effLUy4Vz3ZdVmgY";
        $curl = curl_init();

        $postData = json_encode([
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62'
        ]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new \Exception(curl_error($curl));
        }

        curl_close($curl);
    }
}
