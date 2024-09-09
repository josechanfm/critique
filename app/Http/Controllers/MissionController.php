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
    protected $stage=null;

    public function index()
    {
        //$mission=Mission::find(1);
        //dd($mission->members);
        //dd(auth()->user()->mission());
        //dd(Config::item('stages'));
        $mission=auth()->user()->mission();
        $page=substr('0'.$mission->current_stage+1,-2);
        $stage=$mission->stages->where('code','S'.$page)->first();
        //dd($mission);
        //dd($stage);
        //$this->{'stage'.$page}($mission,$page);
        return Inertia::render('Stages/Stage'.$page,[
            'configStages'=>Config::item('stages'),
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

    public function stage01(){

    }
    public function stage02($mission){
        
    }
    public function stage03(){

    }
}
