<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Mission;

class DashboardController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Dashboard',[
            'configStages'=>Config::item('stages'),
            'missions'=>Mission::all()
        ]);
    }
}
