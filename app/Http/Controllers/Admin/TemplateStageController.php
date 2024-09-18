<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TemplateStage;
use App\Models\File;

class TemplateStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //dd(TemplateStage::all());
        return Inertia::render('Admin/TemplateStages',[
            'files'=>File::all(),
            'stages'=>TemplateStage::with('media')->get()
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
        $templateStage = TemplateStage::create( $request->all() );
        
        if($request->file('files')){
            foreach($request->file('files') as $file){
                //dd($file['originFileObj']);
                $templateStage->addMedia($file['originFileObj'])->toMediaCollection('templateStage');    
            }
        }
        return redirect()->back();
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
    public function update(Request $request, TemplateStage $templateStage)
    {


        

        if( array_key_exists('media', $request->all()) ){
            // 刪除
            $keepMediaIds =  array_column($request->all()['media'] , 'id');
            foreach ($templateStage->getMedia('templateStage') as $media) {
                if (!in_array($media->id, $keepMediaIds)) {
                    $media->delete();
                }
            }
        }

        $templateStage->update($request->all());
        if($request->file('files') ){
            foreach($request->file('files') as $file){
                //dd($file['originFileObj']);
                $templateStage->addMedia($file['originFileObj'])->toMediaCollection('templateStage');    
            }
        }


        $files=$templateStage->media()->where('collection_name','file')->select('name','file_name as path')->get()->map(function ($item) {
            return [
                'name' => $item->name,
                'path' => 'images/'.$item->file_name, // or whatever custom logic you need
            ];
        });
        $videos=$templateStage->media()->where('collection_name','video')->get()->map(function ($item) {
            return [
                'name' => $item->name,
                'path' => 'images/'.$item->file_name, // or whatever custom logic you need
            ];
        });

        $myFiles=['videos'=>$videos, 'files'=>$files];
        $templateStage->description=json_encode($myFiles);
        $templateStage->save();
        // dd(json_encode($myFiles));
        // dd($files, $videos);


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function deleteMedia(){

    }
}
