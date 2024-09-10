<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Mission;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Mission $mission)
    {
        $mission->stages;
        $stageCode='S'.substr('0'.$mission->current_stage+1,-2);
        //dd($stageCode);
        $stage=$mission->stages()->where('code',$stageCode)->first();
        return Inertia::render('Admin/StageTasks',[
            'mission'=>$mission,
            'stage'=>$stage
        ]);
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
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
