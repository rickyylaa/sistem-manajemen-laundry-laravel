<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {
        $this->middleware(function($request,$next){
            if (session('success')) {
                Alert::toast(session('success'));
            }
            if (session('error')) {
                Alert::toast(session('error'));
            }
            return $next($request);
        });
    }
}
