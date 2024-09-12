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
            ["code"=>"S01","title"=>"准备阶段","content"=>
                [
                'videos' => 
                  [
                    ['name' => 'video1', 'path' => 'images/site_logo.png'],
                    ['name' => 'video2', 'path' => 'images/site_logo.png']
                  ],
                'files' => 
                  [
                    ['name' => 'file1', 'path' => 'images/site_logo.png'],
                    ['name' => 'file2', 'path' => 'images/site_logo.png']
                  ]
                ]
            ],
            ["code"=>"S02","title"=>"大的想法","content"=>
                [
                    'note' => '请根据准备阶段您已经浏览过的资料，确定一个广泛主题。',
                    'inputCaption' => '确定您的主题', 
                    'inputPlaceholder' => '请输入您确定的主题，不少于20字。'
                ]
            ],
            ["code"=>"S03","title"=>"选择困境","content"=>
              [
                [
                  'image' => 'images/site-logo.png',
                  'title' => '困境1 标题'
                ],
                [
                  'image' => 'images/site-logo.png',
                  'title' => '困境2 标题'
                ],
                [
                  'image' => 'images/site-logo.png',
                  'title' => '困境3 标题'
                ]
              ]
            ],
            ["code"=>"S04","title"=>"重要问题","content"=>
              [
                [
                  'title' => '第一步：理解困境',
                  'placeholder' => '请输入您对困境矛盾部分的理解，不少于50字。',
                  'note' => '提示：您认为这个困境矛盾的地方在哪里？'
                ],
                [
                  'title' => '第二步：第一次反思',
                  'placeholder' => '请输入您的反思，不少于50字。',
                  'note' => '提示：我是如何与这种两难境地联系在一起的？我有什么看法？我为什么会这么想？'
                ],
                [
                  'title' => '第三步：初步的决定',
                  'placeholder' => '请输入您的决定，不少于50字。',
                  'note' => '提示：你赞同的哪一种观点？'
                ],
                [
                  'title' => '第四步：初次的讨论',
                  'placeholder' => '请输入您讨论结果，不少于50字。',
                  'note' => '提示：请输入您赞同的理由'
                ],
                [
                  'title' => '第五步：第二次反思',
                  'placeholder' => '请输入您的反思，不少于50字。',
                  'note' => '提示：我现在是如何思考这个问题的？我现在有什么看法？我还有什么问题？'
                ],
                [
                  'title' => '第六步：明确问题',
                  'placeholder' => '请输入您确定的核心问题，不少于50字。',
                  'note' => '提示：再次确定我支持的观点'
                ]
              ]
            ],
            ["code"=>"S05","title"=>"挑战提议","content"=>
              [
                'note' => '提示：挑战提议指的是一个具体的、可操作的行动计划（如确定的一个重要问题是“为什么健康对我个人很重要？”一个相应的挑战提议可以这样写：“设计一个健康生活计划，并在接下来的一个月内实施它，记录你的体验和结果。', 
                'placeholder' => '请输入您的挑战提议，不少于20字。'
              ]
            ],
            ["code"=>"S06","title"=>"指导性问题","content"=>
              [
                'note' => '提示：基于已经制定的挑战提议，为确保提出的挑战提议能够顺利被完成，请将这个挑战的提议拆分为一系列子问题，并将这些问题以一个详细的问题清单的形式表现出来。如下表所示：', 
                'placeholder' => '请输入您的挑战问题，不少于50字。', 
                'table' => '<table><tr><th>视角</th><th>具体的问题</th></tr><tr><td>饮食</t><td>如何调整我的饮食结构？</td></tr><tr><td>运动</td><td>我会选择并且打算做哪些类型的运动来增加运动量？</td></tr><tr><td>睡眠</td><td>如何才能提升我的睡眠质量？</td></tr><tr><td>生活习惯</td><td>我是否有一些不良的生活习惯？我应该如何改变这些习惯？</td></tr></table>'
              ]
            ],
            ["code"=>"S07","title"=>"指导性资源","content"=>""
            ],
            ["code"=>"S08","title"=>"交流信息","content"=>""
            ],
            ["code"=>"S09","title"=>"整合信息","content"=>""
            ],
            ["code"=>"S10","title"=>"反思","content"=>""
            ],
            ["code"=>"S11","title"=>"决定和讨论","content"=>""
            ],
            ["code"=>"S12","title"=>"构思方案","content"=>""
            ],
            ["code"=>"S13","title"=>"制定方案","content"=>""
            ],
            ["code"=>"S14","title"=>"实施和评估方案","content"=>""
            ]
        ];
        foreach($data as $d){
            TemplateStage::create($d);
        }
    }
}
