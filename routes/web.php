<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/language/{language}', function ($language) {
    Session::put('applocale', $language);
    return Redirect::back();
})->name('language');


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('missions',App\Http\Controllers\MissionController::class)->names('missions');
    Route::post('mission/stage/{stage}/upload',[App\Http\Controllers\StageController::class,'upload'])->name('mission.stage.upload'); 

 
});
Route::group([
    'prefix' => '/admin',
    'middleware' => [
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
        'role:admin'
    ]
], function () {
    Route::get('/',[App\Http\Controllers\Admin\DashboardController::class,'index'])->name('admin.dashboard');
    Route::resource('configs',App\Http\Controllers\Admin\ConfigController::class)->names('admin.configs');
    Route::resource('template_stages',App\Http\Controllers\Admin\TemplateStageController::class)->names('admin.templateStages');
    Route::resource('missions',App\Http\Controllers\Admin\MissionController::class)->names('admin.missions');
    Route::get('missions/approve/{mission}',[App\Http\Controllers\Admin\MissionController::class,'approve'])->name('admin.missions.approve');
    Route::resource('mission/{mission}/stages',App\Http\Controllers\Admin\StageController::class)->names('admin.mission.stages');
});

