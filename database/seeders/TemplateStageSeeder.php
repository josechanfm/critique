<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TemplateStage;

class TemplateStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            ["code"=>"S01","title"=>"准备阶段"],
            ["code"=>"S02","title"=>"大的想法"],
            ["code"=>"S03","title"=>"选择困境"],
            ["code"=>"S04","title"=>"重要问题"],
            ["code"=>"S05","title"=>"挑战提议"],
            ["code"=>"S06","title"=>"指导性问题"],
            ["code"=>"S07","title"=>"指导性资源"],
            ["code"=>"S08","title"=>"交流信息"],
            ["code"=>"S09","title"=>"整合信息"],
            ["code"=>"S10","title"=>"反思"],
            ["code"=>"S11","title"=>"决定和讨论"],
            ["code"=>"S12","title"=>"构思方案"],
            ["code"=>"S13","title"=>"制定方案"],
            ["code"=>"S14","title"=>"实施和评估方案"]
        ];
        foreach($data as $d){
            TemplateStage::create($d);
        }
    }
}
