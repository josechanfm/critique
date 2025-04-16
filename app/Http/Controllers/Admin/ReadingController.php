<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Reading;

class ReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Reading $reading)
    {
        return Inertia::render('Admin/Reading',[
            'readings' => Reading::all()
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

    public function upload(Request $request, Reading $reading){
        
        // dd( $request->all()['file'] );
        // $reading->addMedia($request->file('file'))->toMediaCollection($request->collection); // Replace 'your_collection_name' with your actual collection name

        if( $request->files ){
            foreach($request->files as $file){
                $reading->addMedia($file['originFileObj'])->toMediaCollection($request->collection);    
            }
        }
    }
}
