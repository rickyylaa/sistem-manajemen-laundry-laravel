<?php

namespace App\Http\Controllers\Laundry\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class RoleController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if (auth()->user()->hasRole(['admin'])) {
            Alert::toast('<span class="toast-information">Anda telah berhasil masuk</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect('admin/dashboard');
        } elseif (auth()->user()->hasRole(['owner'])) {
            Alert::toast('<span class="toast-information">Anda telah berhasil masuk</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect('owner/dashboard');
        } elseif (auth()->user()->hasRole(['employee'])) {
            Alert::toast('<span class="toast-information">Anda telah berhasil masuk</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect('employee/dashboard');
        } elseif (auth()->user()->hasRole(['customer'])) {
            Alert::toast('<span class="toast-information">Anda telah berhasil masuk</span>')->hideCloseButton()->padding('25px')->toHtml();
            return redirect('customer/dashboard');
        } else {
            return redirect('/');
        }
    }
}
