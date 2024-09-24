<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Mission;
use App\Models\Stage;
use App\Models\File;
use App\Models\Media;

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
            'files'=>File::all(),
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
    public function update(Request $request, Mission $mission,  Stage $stage)
    {
        //
        if( array_key_exists('media', $request->all()) ){
            // 刪除
            $keepMediaIds =  array_column($request->all()['media'] , 'id');
            foreach ($stage->getMedia('stage') as $media) {
                if (!in_array($media->id, $keepMediaIds)) {
                    $media->delete();
                }
            }
        }
        
        $stage->update($request->all());
        if($request->file('files') ){
            foreach($request->file('files') as $file){
                $stage->addMedia($file['originFileObj'])->toMediaCollection('stage');    
            }
        }
        
        foreach($request->all()['media'] as $value){
            Media::find($value['id'])->update([
                'title' => $value['title'],
                'link' => $value['link'],
            ]);
        }

        $files=$stage->media()->where('collection_name','stage')->select(['name','file_name','original_url'])->get()->map(function ($item) {
            return [
                'name' => $item->name,
                'path' => 'images/'.$item->file_name, // or whatever custom logic you need
            ];
        });

        $myFiles=['files'=>$files];
        $stage->content=($myFiles);
        $stage->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
