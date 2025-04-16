<?php

namespace App\Http\Controllers;

use App\Models\TemplateStage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reading;

class ReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Reading', [
            'readings' => Reading::all(),
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
}
