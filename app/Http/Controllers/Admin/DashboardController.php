<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dua;
use App\Models\Lecture;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index():View
    {

        if (is_null(Auth::user()) or !Auth::user()->can('dashboard.view')) {
        abort(403, 'Sorry!! You are Unauthorized To Access');
    }

        $data =[
          'dua'=> Dua::count(),
          'lecture'=> Lecture::count(),
          'user'=> User::count(),
          'plans'=> Subscription::count(),
        ];
        return view('admin.dashboard',$data);
    }
}
