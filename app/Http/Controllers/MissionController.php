<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use Inertia\Inertia;
use App\Models\Config;
use App\Models\Task;
use App\Models\Stage;
use App\Models\Media;
use App\Models\Evaluation;

class MissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $stage = null;

    public function index()
    {
        $mission_id = $_GET['mission_id'];
        $mission = Mission::find($mission_id);
        // $mission=auth()->user()->mission();
        // dd('test');

        // 翻其他頁
        if (array_key_exists("page", $_GET) && $_GET['page'] != null) {
            $page = substr('0' . $_GET['page'] + 1, -2);
        } else {
            $page = substr('0' . $mission->current_stage + 1, -2);
        }

        $stage = $mission->stages->where('code', 'S' . $page)->first();

        if( $stage  ){

            foreach ($stage->media as $key => $m) {
                $find_media = Media::find($m->id)->getMedia('*')->first();
                if ($find_media) {
                    $stage->media[$key]->thumbnail = $find_media->getFullUrl();
                }
            }

            $task = Task::where('user_id', auth()->user()->id)->where('stage_id', $stage->id)->get();
        } 


        return Inertia::render('Stages/Stage' . $page, [
            'configStages' => Config::item('stages'),
            'mission' => $mission,
            'page' => $page,
            'stage' => $stage??[],
            'task' => $task??[],
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
        $items = $request->all();
        $stageCode = 'S' . substr('0' . $mission->current_stage + 1, -2);
        $stage = $mission->stages()->where('code', $stageCode)->first();

        foreach ($items as $item) {
            $item['user_id'] = auth()->user()->id;

            $currentStage = Stage::find($item['stage_id']);

            if (isset($item['stage_id']) && (int)(str_replace('S', '', $currentStage->code)) <= $mission->current_stage) {
                $stage = Stage::find($item['stage_id']);
            }

            if (array_key_exists('id', $item)) {

                Task::updateOrCreate(
                    ['id' => $item['id'], 'stage_id' => $item['stage_id'], 'user_id' => $item['user_id']],
                    ['title' => $item['title'], 'content' => $item['content']]
                );
                // Task::find($item['id'])->update($item);
            } else {
                //Task::create($items);
                $task = $stage->tasks()->create($item);
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

    public function report($mission_id)
    {

        $user_id = Auth()->user()->id;

        // 該 user 的 task
        $mission = Mission::with(['stages.tasks' => function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        }])->find($mission_id);

        return Inertia::render('MissionReport', [
            'mission' => $mission,
        ]);
    }

    public function evaluation($mission_id)
    {

        $user_id = Auth()->user()->id;

        $evaluation = Evaluation::where('user_id', $user_id)->where('mission_id', $mission_id)->with('user')->first();

        return Inertia::render('MissionEvaluation', [
            'mission_id' => $mission_id,
            'evaluation' => $evaluation
        ]);
    }

    public function evaluation_save(Request $request){

        $data = $request->all();

        Evaluation::create([
            'mission_id' => $data['mission_id'],
            'answers' => json_encode( $data['form'] ),
            'user_id' => Auth()->user()->id,
        ]);

        return redirect()->back();
    }
}
