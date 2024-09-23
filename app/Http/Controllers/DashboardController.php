<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\User;
use App\Models\Mission;

class DashboardController extends Controller
{
    public function index(){

        $user = auth()->user();
        $user->missions;
        return Inertia::render('Dashboard', [
            'user' => $user,
        ]);
    }
}
