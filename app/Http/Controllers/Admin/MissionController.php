<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Mission;
use App\Models\TemplateStage;
use App\Models\Stage;
use App\Models\User;
use App\Models\Media;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Missions',[
            'users' => User::all(),
            'configStages'=>Config::item('stages'),
            'missions'=>Mission::with('users')->get()
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
        $data = $request->all();
        $data['current_stage'] = 0;
        $mission = Mission::create($data);

        // 拿template stage Clone一份
        $stages = TemplateStage::whereBetween('code', ['S01', 'S15'])->get();
        $stagesWithMission = $stages->map(function($stage) use ($mission) {
            $stage['mission_id'] = $mission->id; // 添加 mission_id
            return $stage; // 返回修改後的項目
        });

        $sourceStage = Stage::where('code', 'S03')->first();

        foreach ($stagesWithMission->toArray() as $stageData) {
            if( $stageData['code'] == 'S03' ){
                $mediaItems = $sourceStage->media; 

                $stage = Stage::create($stageData);

                foreach ($mediaItems as $media) {
                    $media->copy($stage); 
                }
            }else{
                Stage::create($stageData);
            }
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Mission $mission)
    {
        $mission->stages;
        // array_map( function($m){
        //     $m->media;
        // }, $mission->stages ) ;
        
        foreach($mission->stages as $index => $s){
            foreach( $s->media as $key => $m ){
                $media = Media::find($m->id)->getMedia('*')->first();
                if( $media){
                    $mission->stages[$index]->media[$key]->thumbnail_id = $media->id;
                    $mission->stages[$index]->media[$key]->thumbnail_name = $media->file_name;
                    $mission->stages[$index]->media[$key]->thumbnail_path = $media->getFullUrl();
                }
            }
        };

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
        $mission->update($request->all());
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function updateMissionUser(Request $request, Mission $mission){

        $mission->users()->sync( $request->all() );

        return redirect()->back();
    }

    public function changeStatus(Mission $mission){
        // 全部關閉
        Mission::query()->update(['status' => 0]);

        // Active 
        $mission->update(['status'=>1]);

        return redirect()->back();
    }

    public function approve(Mission $mission)
    {
        if( $mission->current_stage == '14' ){
            return ;
        }
        $mission->current_stage = $mission->current_stage+1;
        $mission->update();
        return ;
    }

    public function regret(Mission $mission)
    {
        $stage = $mission->current_stage-1;
        $mission->current_stage = $stage < 0? '0' : $stage;
        $mission->update();
        return ;
    }
}
