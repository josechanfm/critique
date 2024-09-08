<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use Inertia\Inertia;
use App\Models\Config;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$mission=Mission::find(1);
        //dd($mission->members);
        //dd(auth()->user()->mission());
        $mission=auth()->user()->mission();
        $page='Stages/Stage'.substr('0'.$mission->current_stage+1,-2);
        return Inertia::render($page,[
            'configStages'=>Config::item('stages'),
            'mission'=>$mission
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
