<?php

namespace App\Http\Controllers\Laundry\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.profile.index', compact('user', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $username)
    {
        $request->validate([
            'username' => 'required|string',
            'name' => 'required|string',
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,gif,webp|max:5000',
            'gender' => 'required|string',
            'phone' => 'required|numeric',
            'address' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('username', $username)->firstOrFail();

            if ($request->username !== $username) {
                $existingUser = User::where('username', $request->username)->first();
                if ($existingUser) {
                    Alert::toast('<span class="toast-information">Nama pengguna sudah diambil</span>')->hideCloseButton()->padding('25px')->toHtml();
                    return redirect(route('admin.profile', $user->username));
                }
            }

            $filename = $user->image;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '-' . rand(0,99999) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/profiles', $filename);

                if ($user->image !== 'avatar.png') {
                    File::delete(storage_path('app/public/profiles/' . $user->image));
                }
            }

            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'image' => $filename,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            DB::commit();

            Alert::toast('<span class="toast-information">Profil berhasil diperbarui</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect(route('admin.profile', $user->username));
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect(route('admin.profile', $user->username));
        }
    }
}
