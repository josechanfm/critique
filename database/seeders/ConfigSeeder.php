<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::create(
            ['key'=>'stages','content'=>
                [
                    ["value"=>"S01","title"=>"准备阶段"],
                    ["value"=>"S02","title"=>"大的想法"],
                    ["value"=>"S03","title"=>"选择困境"],
                    ["value"=>"S04","title"=>"重要问题"],
                    ["value"=>"S05","title"=>"挑战提议"],
                    ["value"=>"S06","title"=>"指导性问题"],
                    ["value"=>"S07","title"=>"指导性资源"],
                    ["value"=>"S08","title"=>"交流信息"],
                    ["value"=>"S09","title"=>"整合信息"],
                    ["value"=>"S10","title"=>"反思"],
                    ["value"=>"S11","title"=>"决定和讨论"],
                    ["value"=>"S12","title"=>"构思方案"],
                    ["value"=>"S13","title"=>"制定方案"],
                    ["value"=>"S14","title"=>"实施和评估方案"]
                ]
            ]
        );
    
    }
}
