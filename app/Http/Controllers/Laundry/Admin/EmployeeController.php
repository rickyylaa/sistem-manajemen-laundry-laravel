<?php

namespace App\Http\Controllers\Laundry\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $employeeQuery = User::whereHas('roles', function ($query) {
            $query->where('name', 'employee');
        });

        $query = request()->q;
        if ($query != '') {
            $employeeQuery->where(function($q) use ($query) {
                $q->where('username', 'LIKE', '%' . $query . '%')
                    ->orWhere('name', 'LIKE', '%' . $query . '%')
                    ->orWhere('phone', 'LIKE', '%' . $query . '%')
                    ->orWhere('address', 'LIKE', '%' . $query . '%');
            });
        }

        $employee = $employeeQuery->paginate(30)->appends($request->except(['page', '_token']));

        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.employee.index', compact('employee', 'notification'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'gender' => 'required|string',
            'phone' => 'required|numeric',
            'address' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $filename = 'avatar.png';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '-' . rand(0, 99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profiles', $filename);
            }

            $nameParts = explode(' ', $request->name);
            $username = Str::slug($nameParts[0]) . rand(0, 999);
            $password = Str::slug($nameParts[0]) . rand(0, 999);

            $phone = $request->phone;
            if (substr($phone, 0, 1) !== '0') {
                $phone = '0' . $phone;
            }

            $employee->create([
                'username' => $username,
                'name' => $request->name,
                'password' => Hash::make($password),
                'image' => $filename,
                'gender' => $request->gender,
                'phone' => $phone,
                'address' => $request->address,
                'status' => 1
            ])->assignRole('employee');

            $message = "Halo " . strtoupper($request->name) . ", ini adalah Nama Pengguna dan Kata Sandi Anda.\n";
            $message .= "Nama Pengguna: $username\nKata Sandi: $password\n\n";
            $message .= "Anda dapat masuk ke situs web kami di " . url('/');

            $this->sendMessage($request->phone, $message);

            DB::commit();

            Alert::toast('<span class="toast-information">Karyawan berhasil dibuat</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat membuat karyawan: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $username)
    {
        $employee = User::where('username', $username)
            ->whereHas('roles', function ($query) {
                $query->where('name', 'employee');
            })->firstOrFail();

        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.employee.edit', compact('employee', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $username)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'gender' => 'required|string',
            'phone' => 'required|numeric',
            'address' => 'required|string',
            'status' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $employee = User::where('username', $username)->firstOrFail();

            $filename = $employee->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profiles', $filename);

                if ($employee->image !== 'avatar.png') {
                    File::delete(storage_path('app/public/profiles/' . $employee->image));
                }
            }

            $newUsername = $employee->username;
            $newPassword = $request->password ? Hash::make($request->password) : $employee->password;
            if ($employee->name !== $request->name) {
                $nameParts = explode(' ', $request->name);
                $newUsername = Str::slug($nameParts[0]) . rand(0, 999);
            }

            $phone = $request->phone;
            if (substr($phone, 0, 1) !== '0') {
                $phone = '0' . $phone;
            }

            $employee->update([
                'username' => $newUsername,
                'name' => $request->name,
                'password' => $newPassword,
                'image' => $filename,
                'gender' => $request->gender,
                'phone' => $phone,
                'address' => $request->address,
                'status' => $request->status
            ]);

            $nameChanged = $employee->wasChanged('name');
            $passwordChanged = $request->password;

            if ($nameChanged || $passwordChanged) {
                $message = "Hello " . strtoupper($request->name) . ", Ini adalah Nama Pengguna dan Kata Sandi Anda yang telah diperbarui.\n";
                $message .= "Nama Pengguna: $newUsername\n";
                if ($request->password) {
                    $message .= "Kata Sandi: " . $request->password . "\n\n";
                } else {
                    $message .= "Kata sandi tidak diubah\n\n";
                }
                $message .= "Anda dapat masuk ke situs web kami di " . url('/');

                $this->sendMessage($request->phone, $message);
            }

            DB::commit();

            Alert::toast('<span class="toast-information">Karyawan berhasil diperbarui</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat memperbarui karyawan: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $username)
    {
        try {
            DB::beginTransaction();

            $employee = User::where('username', $username)->firstOrFail();
            $imagePath = storage_path('app/public/profiles/' . $employee->image);
            if ($employee->image !== 'avatar.png' && File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $employee->delete();

            DB::commit();

            Alert::toast('<span class="toast-information">Karyawan berhasil dihapus</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menghapus karyawan: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    private function sendMessage($phone, $message)
    {
        $token = "yMo#effLUy4Vz3ZdVmgY";
        $curl = curl_init();

        $postData = json_encode([
            'target' => $phone,
            'message' => $message,
            'countryCode' => '62',
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
