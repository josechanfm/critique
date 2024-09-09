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
                    ["value"=>"S01","label"=>"准备阶段"],
                    ["value"=>"S02","label"=>"大的想法"],
                    ["value"=>"S03","label"=>"选择困境"],
                    ["value"=>"S04","label"=>"重要问题"],
                    ["value"=>"S05","label"=>"挑战提议"],
                    ["value"=>"S06","label"=>"指导性问题"],
                    ["value"=>"S07","label"=>"指导性资源"],
                    ["value"=>"S08","label"=>"交流信息"],
                    ["value"=>"S09","label"=>"整合信息"],
                    ["value"=>"S10","label"=>"反思"],
                    ["value"=>"S11","label"=>"决定和讨论"],
                    ["value"=>"S12","label"=>"构思方案"],
                    ["value"=>"S13","label"=>"制定方案"],
                    ["value"=>"S14","label"=>"实施和评估方案"]
                ]
            ]
        );
    
    }
}
