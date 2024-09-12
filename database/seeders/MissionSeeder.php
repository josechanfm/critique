<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mission;
use App\Models\Config;
use App\Models\TemplateStage;

class MissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mission=Mission::create([
            'title'=>'Team 1 on mission',
            'description'=>'Mission for team 1',
            'current_stage'=>0,
            'started_at'=>'2024-09-01'
        ]);
        $mission->members()->sync([1,2,3]);
        //$stages=Config::item('stages');
        $stages=TemplateStage::all();
        foreach($stages as $stage){
            $mission->stages()->create([
                'code'=>$stage->code,
                'title'=>$stage->title,
                'content'=>$stage->content
            ]);
        }
        
    }
}
