<template>
<AdminLayout title="总览">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            评量表
        </h2>
    </template>

    <div class="py-4">
        <div class="max-w-7xl mx-auto ">
            <a class="bg-white border shadow p-2 rounded" :href="route('dashboard')">← 返回</a>
            <div class="my-4 flex flex-col gap-2">
                <div class="px-2 py-4 bg-white shadow-sm rounded-lg my-2">
                    这份批判性思维自我评估量表共包括 17个题目, 采用 Likert 7点量表计分, 从“完全不同意”到“完全同意”, 分别计为 1~7 分。请您根据您自身的实际情况进行作答。
                </div>

                <a v-if="evaluation" class="cursor-pointer w-32 text-center px-4 py-2 rounded-lg shadow bg-blue-600 hover:bg-blue-700 text-white" @click="generateEvaluationReport(evaluation)">
                    下载报告 <DownloadOutlined />
                </a>

                <div v-if="evaluation" class="shadow-lg rounded bg-white "> 
                    <div class="p-3  ">
                        批判性思维评估量表已完成
                    </div>

                    <div class="py-2 px-2 bg-white ">
                        <div class="bg-slate-100 p-4">
                            <div class="font-bold text-base">{{ evaluation.user.name }} &nbsp; {{ evaluation.user.email }}</div>
                            <div v-for="(ans,index) in JSON.parse(evaluation.answers) " class="flex flex-col px-4 py-2">
                                <div>
                                    {{ ans.label }}
                                </div>
                                <div class="font-bold text-base px-4"> {{ displayOption(ans.value) }} </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else>
                    <a-form :model="evaluationForm" ref="form" name="evaluation" autocomplete="off" enctype="multipart/form-data" layout="vertical">
                        <div class="py-2 px-4 bg-white">
                            <div class="py-2 px-4 text-xl font-bold border-b-2 mb-4 ">
                                批判性思维评估量表
                            </div>
                            
                            <a-form-item v-for="(item, index) in items" :name="item.name" :key="index" :label="item.label" :rules="[{ required: true, message: '必填欄位' }]">
                                <!-- <a-radio-group v-model:value="form[item.name]"> -->
                                <a-radio-group v-model:value="evaluationForm[item.name]">
                                    <a-radio class="p-2" v-for="o in options" :value="o.value" >{{o.label}}</a-radio>
                                </a-radio-group>
                            </a-form-item>
                        </div>

                        <div class="flex flex-row item-center justify-center gap-5 py-2 bg-slate-200/50 border-t-2 ">
                            <a-button type="primary" @click="onFinish">{{ $t('submit') }}
                                <CheckOutlined />
                            </a-button>
                            <span v-if="errorMessage" class="text-red-600">{{ errorMessage }}</span>
                        </div>
                    </a-form>
                </div>
            </div>
        </div>
    </div>
</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        ...AntdIcons,
    },
    props: ['mission_id', 'evaluation'],
    data() {
        return {
            form: {},
            items: [{
                    label: '1. 做事情之前我清楚地知道自己的目的是什么。',
                    value: '',
                    name: "q1"
                },
                {
                    label: '2. 我喜欢按照一定的步骤去解决问题。',
                    value: '',
                    name: "q2"
                },
                {
                    label: '3. 我做决策时经常考虑父母长辈的意见。',
                    value: '',
                    name: "q3"
                },
                {
                    label: '4. 面对问题时我会根据不同的情境做出不同的决定。',
                    value: '',
                    name: "q4"
                },
                {
                    label: '5. 我能够区分生活中哪些事情对我当前是重要的。',
                    value: '',
                    name: "q5"
                },
                {
                    label: '6. 除了与课程有关的阅读外，我也会阅读很多课外的东西。',
                    value: '',
                    name: "q6"
                },
                {
                    label: '7. 我会仔细考虑“存在必有其合理性”这句话的可靠性',
                    value: '',
                    name: "q7"
                },
                {
                    label: '8.我可以与拥有不同观点的人进行交流。',
                    value: '',
                    name: "q8"
                },
                {
                    label: '9.我不能容忍别人不同意我的观点或想法。',
                    value: '',
                    name: "q9"
                },
                {
                    label: '10.中途改变自己的主意是很丢脸的事情。',
                    value: '',
                    name: "q10"
                },
                {
                    label: '11.做事时除了运用当前的方法，我不会再考虑其他方法。',
                    value: '',
                    name: "q11"
                },
                {
                    label: '12.与别人产生意见分歧会影响到我们之间的交流。',
                    value: '',
                    name: "q12"
                },
                {
                    label: '13.面对复杂问题时我常常无从下手。',
                    value: '',
                    name: "q13"
                },
                {
                    label: '14.我经常与别人分享我对事物的看法。',
                    value: '',
                    name: "q14"
                },
                {
                    label: '15.看到一个新产品的说明书复杂难懂时我倾向于实际操作作。',
                    value: '',
                    name: "q15"
                },
                {
                    label: '16.我解决问题时经常会有替代方案。',
                    value: '',
                    name: "q16"
                },
                {
                    label: '17.同龄人遇到难以决断的问题时喜欢找我商量。',
                    value: '',
                    name: "q17"
                },
            ],
            options: [{
                    label: '完全不同意',
                    value: 1
                },
                {
                    label: '不同意',
                    value: 2
                },
                {
                    label: '有些不同意',
                    value: 3
                },
                {
                    label: '中立',
                    value: 4
                },
                {
                    label: '有些不同意',
                    value: 5
                },
                {
                    label: '同意',
                    value: 6
                },
                {
                    label: '完全同意',
                    value: 7
                },
            ],
            labelCol: {
                style: {
                    width: "150px",
                },
            },
            errorMessage: '', // 用于存储错误信息

            evaluationForm:{},
            optionItem:[],
        }
    },
    created(){
        // this.optionItem = items.map( x => )
    },
    methods: {
        onFinish() {
            this.errorMessage = '';

            this.$refs.form.validate().then(() => {

                this.handleSubmit()
            }).catch(() => {
                // 如果验证失败，设置错误信息
                this.errorMessage = '请填写所有必填项。';
            });
        },
        handleSubmit() {

            for (const item of this.items) {
                if (this.evaluationForm[item.name] !== undefined) {
                    item.value = this.evaluationForm[item.name]; // 更新 value
                }
            }

            this.$inertia.post(
                route("missions.evaluation.save"), {
                    mission_id: this.mission_id,
                    form: this.items
                }, {
                    onSuccess: (page) => {
                        // this.items = this.stage.tasks
                        console.log(page);
                        location.reload();
                        // notification.open({
                        //     message: 'Finish',
                        // });
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
        generateEvaluationReport(evaluation){
            
            window.location.href = route("missions.evaluation.report" , {
                    evaluation: this.evaluation.id,
                })
        },
        displayOption(value) {
            return this.options.find(x => x.value == value)?.label
        }
    }
}
</script>

<style>
.ant-form-item-label>label {
    font-size: 16px !important;
    font-weight: bold;
}
</style>
