<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
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

Route::resource('blogs',App\Http\Controllers\BlogController::class)->names('blogs'); 
Route::get('blogs/getBlog/{stage_id}',[App\Http\Controllers\BlogController::class,'getBlog'])->name('blogs.getBlog'); 

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
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class,'index'])->name('dashboard');
    
    Route::resource('missions',App\Http\Controllers\MissionController::class)->names('missions');
    
    Route::post('mission/stage/{stage}/upload',[App\Http\Controllers\StageController::class,'upload'])->name('mission.stage.upload'); 
    Route::get('mission/stage/{stage}/{media_id}/{mediaType}/delete',[App\Http\Controllers\StageController::class,'deleteUpload'])->name('mission.stage.deleteUpload'); 

 
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
    Route::resource('stages',App\Http\Controllers\Admin\StageController::class)->names('admin.stages');
    Route::post('stages/update_media_thumbnail/{media}/{stage}',[App\Http\Controllers\Admin\StageController::class, 'updateMediaThumbnail'])->name('admin.stages.updateMediaThumbnail');
    Route::get('stages/delete_media_thumbnail/{media_id}',[App\Http\Controllers\Admin\StageController::class, 'deleteMediaThumbnail'])->name('admin.stages.deleteMediaThumbnail');
    Route::post('missions/update_mission_user/{mission}',[App\Http\Controllers\Admin\MissionController::class,'updateMissionUser'])->name('admin.missions.updateMissionUser');
    Route::get('missions/change_status/{mission}',[App\Http\Controllers\Admin\MissionController::class,'changeStatus'])->name('admin.missions.change_status');
    Route::get('missions/approve/{mission}',[App\Http\Controllers\Admin\MissionController::class,'approve'])->name('admin.missions.approve');
    Route::get('missions/regret/{mission}',[App\Http\Controllers\Admin\MissionController::class,'regret'])->name('admin.missions.regret');
    Route::resource('mission/{mission}/stages',App\Http\Controllers\Admin\StageController::class)->names('admin.mission.stages');
    Route::resource('files',App\Http\Controllers\Admin\FileController::class)->names('admin.files');
});

Route::get('/download', function ( Request $request) {
    $path = str_replace( url('/')  , '', $request->query('path') );
    $path = str_replace( 'storage'  , 'public', $path );
    return Storage::response( $path );
})->name('download');