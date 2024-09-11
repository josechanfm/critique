<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Task;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $stage=null;

    public function index()
    {
        
        $mission=auth()->user()->mission();
        $page=substr('0'.$mission->current_stage+1,-2);
        $stage=$mission->stages->where('code','S'.$page)->first();
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
    public function update(Request $request, Mission $mission)
    {
        $items=$request->all();
        // dd($items);
        $stageCode='S'.substr('0'.$mission->current_stage+1,-2);
        $stage=$mission->stages()->where('code',$stageCode)->first();
        foreach($items as $item){
            // Task::updateOrCreate(
            //     ['stage_id' => $stage->id],
            //     ['title' => $item['title'], 'content'=> $item['content']]
            // );
            if(array_key_exists('id', $item)){
                Task::find($item['id'])->update($item);
            }else{
                //Task::create($items);
                $stage->tasks()->create($item);
            }
            
        };
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
