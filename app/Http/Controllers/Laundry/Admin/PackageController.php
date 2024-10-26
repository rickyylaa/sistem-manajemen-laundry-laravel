<?php

namespace App\Http\Controllers\Laundry\Admin;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Services\Notification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $package = Package::orderBy('id', 'ASC')->paginate(10);
        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.package.index', compact('package', 'notification'));
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
    public function store(Request $request, Package $package)
    {
        $request->validate([
            'type' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $package->create([
                'type' => $request->type,
                'price' => $request->price,
                'status' => $request->status
            ]);

            DB::commit();

            Alert::toast('<span class="toast-information">Paket berhasil dibuat</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat membuat paket: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
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
    public function edit(string $id)
    {
        $package = Package::findOrFail($id);
        $notification = Notification::getNotifications();
        return view('laundry.admin.pages.package.edit', compact('package', 'notification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $package = Package::findOrFail($id);

            $package->update([
                'type' => $request->type,
                'price' => $request->price,
                'status' => $request->status
            ]);

            DB::commit();

            Alert::toast('<span class="toast-information">Paket berhasil diperbarui</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat memperbarui paket: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $package = Package::findOrFail($id);
            $package->delete();

            DB::commit();

            Alert::toast('<span class="toast-information">Paket berhasil dihapus</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Alert::toast('<span class="toast-information">Terjadi kesalahan saat menghapus paket: ' . $e->getMessage() . '</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect()->back();
        }
    }
}
