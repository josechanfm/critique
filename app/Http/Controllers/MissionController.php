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
use App\Exports\EvaluationsExport;
use Maatwebsite\Excel\Facades\Excel;

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
    public function evaluation_report(Evaluation $evaluation)
    {
        $answers = json_decode( $evaluation->answers );
        
        $group1 = []; $group2 = []; $group3 = [];
        $group1_score = 0; $group2_score = 0; $group3_score = 0;
        $group1_message = ''; $group2_message = ''; $group3_message = '';
        $options = [ 
            1 => '完全不同意',
            2 => '不同意',
            3 => '有些不同意',
            4 => '中立',
            5 => '有些不同意',
            6 => '同意',
            7 => '完全同意',
        ];

        foreach ($answers as $answer) {
            if (in_array($answer->name, ['q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q7', 'q8'])) {
                $group1[] = ['label'=>$answer->label, 'value'=>$options[$answer->value]] ;
                $group1_score += (int) $answer->value;
            } elseif (in_array($answer->name, ['q9', 'q10', 'q11', 'q12', 'q13'])) {
                $group2[] = ['label'=>$answer->label, 'value'=>$options[$answer->value]] ;
                $group2_score += (int) $answer->value;
            } elseif (in_array($answer->name, ['q14', 'q15', 'q16', 'q17'])) {
                $group3[] = ['label'=>$answer->label, 'value'=>$options[$answer->value]] ;
                $group3_score += (int) $answer->value;
            }
        }

        if( $group1_score < 56){
            $group1_message = '在整个批判性思维训练步骤中需要注重第4步和第6步的练习。';
        }
        if( $group1_score == 56){
            $group1_message = '您具有较强的分析技能，在整个批判性思维训练步骤注重第4步和第6步的练习可更加强化您的分析技能。';
        }
        if( $group2_score > 5){
            $group2_message = '在整个批判性思维训练步骤中需要注重第4步，第6步，第8步，第9步和第11步的练习。';
        }
        if( $group2_score == 5){
            $group2_message = '您具有较强的开放性技能，在整个批判性思维训练步骤注重第4步，第6步，第8步，第9步和第11步的练习可更加强化您的开放性的技能。';
        }
        if( $group3_score < 28){
            $group3_message = '在整个批判性思维训练步骤中您都需要保持运用批判性思维的意识。';
        }
        if( $group3_score == 28){
            $group3_message = '您具有较强的运用批判性思维的意识，在整个批判性思维训练步骤中保持运用批判性思维的意识，可以提升您的批判性思维水平。';
        }

        $result = [ 
            ['批判性思维评估结果', '',''], 
            ['您的批判性思维最终得分为'.$group1_score+$group2_score+$group3_score.'分', '',''], 
            ['分析技能维度', '您的选项',''], 
            ...$group1,
            ['这个维度得分', $group1_score,''], 
            ['推荐训练步骤', $group1_message,''], 
            [''],
            ['开放性维度', '您的选项',''], 
            ...$group2,
            ['这个维度得分', $group2_score,''], 
            ['推荐训练步骤', $group2_message,''], 
            [''],
            ['运用批判性思维倾向维度', '您的选项',''], 
            ...$group3,
            ['这个维度得分', $group3_score,''], 
            ['推荐训练步骤', $group3_message,''], 
        ];
        
        $instance=new EvaluationsExport();
        $instance->set_export_data($result);
        return Excel::download($instance, '评量表报告.xlsx');
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
