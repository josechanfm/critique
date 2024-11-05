<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\TemplateStage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stage $stage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stage $stage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(stage $stage)
    {
        //
    }
    public function upload(Stage $stage, Request $request){
        // Validate the request
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,rtf,mp4,mov,avi,mkv,wmv,flv,webm|max:512000',
        ]);
        
        if($request->uploadType=='file'){
            $stage->addMedia($request->file('file'))->toMediaCollection('file'); // Replace 'your_collection_name' with your actual collection name
            return response()->json(['success' => true, 'message' => 'File uploaded successfully']);
        }
        if($request->uploadType=='finalFile'){
            $stage->addMedia($request->file('finalFile'))->toMediaCollection('finalFile'); // Replace 'your_collection_name' with your actual collection name
            return response()->json(['success' => true, 'message' => 'File uploaded successfully']);
        }
        if($request->uploadType=='video'){
            $stage->addMedia($request->file('file'))->toMediaCollection('video'); // Replace 'your_collection_name' with your actual collection name
            return response()->json(['success' => true, 'message' => 'File uploaded successfully']);
        }
        
        // //$stage->addMedia
        // return redirect()->back();

        // dd($request->all());
    }

    public function deleteUpload(Stage $stage , $media_id, $mediaType){
        
        $media = $stage->getMedia($mediaType)->find($media_id);

        $media->delete();
    }
}
