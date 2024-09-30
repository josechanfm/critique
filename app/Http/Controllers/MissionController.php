<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Task;
use App\Models\Stage;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $stage=null;

    public function index()
    {
        $mission_id = $_GET['mission_id'];
        $mission = Mission::find($mission_id);
        // $mission=auth()->user()->mission();
        //dd(session('applocale'));

        // 翻其他頁
        if( array_key_exists("page",$_GET ) && $_GET['page'] != null ){
            $page = substr('0'.$_GET['page']+1,-2);
        }else{
            $page = substr('0'.$mission->current_stage+1,-2);
        }

        $stage=$mission->stages->where('code','S'.$page)->first();

        return Inertia::render('Stages/Stage'.$page,[
            'configStages'=>Config::item('stages'),
            'mission'=>$mission,
            'page'=>$page,
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
        $stageCode='S'.substr('0'.$mission->current_stage+1,-2);
        $stage=$mission->stages()->where('code',$stageCode)->first();

        foreach($items as $item){
            
            if( isset( $item['stage_id']) && $item['stage_id'] <= $mission->current_stage ){
                $stage=Stage::find($item['stage_id']);
            }
            
            if(array_key_exists('id', $item)){
                
                Task::updateOrCreate(
                    ['id' => $item['id'], 'stage_id' => $item['stage_id']],
                    ['title' => $item['title'], 'content'=> $item['content']]
                );
                // Task::find($item['id'])->update($item);
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
