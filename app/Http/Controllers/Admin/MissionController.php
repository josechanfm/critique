<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Mission;


class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Missions',[
            'configStages'=>Config::item('stages'),
            'missions'=>Mission::all()
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
    public function show(Mission $mission)
    {
        $mission->stages;
        return Inertia::render('Admin/MissionStages',[
            'mission'=>$mission
        ]);

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
    public function update(Request $request, Mission $mission)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function approve(Mission $mission)
    {
        $mission->current_stage = $mission->current_stage+1;
        $mission->update();
        return ;
    }

    public function regret(Mission $mission)
    {
        $mission->current_stage = $mission->current_stage-1;
        $mission->update();
        return ;
    }
}
