<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { triggerFocus } from 'ant-design-vue/es/vc-input/utils/commonUtils';
import { VueEcharts } from 'vue3-echarts';

export default {
    components: {
        AdminLayout,
        VueEcharts,
    },
    props: ['evaluations'],
    created(){
        this.setBarData()
    },  
    data() {
        return {

            options: [
                { label: '完全不同意', value: 1 },
                { label: '不同意', value: 2 },
                { label: '有些不同意', value: 3 },
                { label: '中立', value: 4 },
                { label: '有些不同意',  value: 5 },
                { label: '同意', value: 6 },
                { label: '完全同意',  value: 7 },
            ],
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
            barChartData:[0, 0, 0, 0, 0],
            barFilter: "" ,
            barTitle: "" ,
        }
    },
    computed:{
        barChat() {
            return  {
                title: {
                    show: true,
                    text: this.barTitle,
                    left: 'center'
                },
                xAxis: {
                    type: 'category',
                    data: ['完全不同意', '不同意', '有些不同意', '中立', '有些不同意']
                },
                yAxis: {
                    minInterval: 1,
                    type: 'value'
                },
                series: [
                    {
                        data: this.barChartData,
                        type: 'bar'
                    },
                ]
            }
        },
    },
    methods: {
        setBarFilter(name){
            this.barFilter = (this.barFilter === name) ? "" : name;

            this.setBarData()
        },
        setBarData(){
            this.barChartData = [0, 0, 0, 0, 0];
            this.evaluations.forEach( (eva)=>{

                JSON.parse(eva.answers).forEach( (ans)=>{

                    if( this.barFilter ){

                        if( this.barFilter == ans.name ){
                            this.barTitle = ans.label
                            
                            console.log(ans)
                            this.barChartData[ ans.value - 1] ++
                        }
                    }else{

                        this.barChartData[ ans.value - 1] ++
                    }

                })
            })
            console.log(this.barChartData)
        },
        displayOption(value) {
            return this.options.find(x => x.value == value)?.label
        }
    }
}
</script>

<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </template>

    <div class="py-8">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a class="bg-white border shadow p-2 rounded" :href="route('admin.missions.index')">← 返回</a>
            </div>

        <div class="py-2 px-4 text-xl font-bold border-b-2 my-2 ">
                评估量表
            </div>
            
            <div class="bg-white p-2 shadow-md rounded-lg my-4 ">
                <div class="pb-6">
                    <div class="font-bold text-lg my-2">篩選：</div>
                    <a-button v-for="item in items" class="m-2 text-xs md:text-base shadow" @click="setBarFilter(item.name)" :class="barFilter == item.name?' bg-blue-500 text-white':''"> {{ item.label }} </a-button>
                </div>
                <vue-echarts class="w-full" :option="barChat" style="height: 600px" ref="chart" />
            </div>

            <div class="border-b-2 font-bold text-lg my-2">詳細：</div>
            <div class="grid grid-cols-2 w-full">
                <div v-for="evaluation in evaluations">
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
            </div>
        </div>
    </div>
</AdminLayout>
</template>
