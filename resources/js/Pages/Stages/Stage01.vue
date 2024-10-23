<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page.props.lang == "en" ? mission.title_en : mission.title }}
        </h2>
    </template>
    <StageHeader :mission="mission" :current="mission.current_stage" :steps="configStages" :page="page" />

    <div class="container mx-auto pt-5">
        <div class="bg-white flex w-40 justify-center p-3 my-2 rounded shadow">{{ configStages[Number(page)-1].label }}</div>
        <div class="bg-white relative shadow rounded-lg ">
            <div class="grid md:grid-cols-2 grid-cols-1 gap-4 md:p-5 p-4">
                <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                    <div class="min-h-36 ">
                        <div class="mx-4 my-2 text-lg font-bold">{{$t('video_title')}}</div>
                        <ol>
                            <li v-for="video in categorizeVideos(stage.content.files)" class="flex items-center">
                                <img class="w-48" v-if="stage.media.find( x => x.name == video.name )" :src=" route('download' , {path : stage.media.find( x => x.name == video.name ).thumbnail })" />

                                <div class="text-wrap flex md:flex-row flex-col items-center">
                                    <span class="font-semibold text-base mx-2">{{ video.name }}</span>
                                    <a download :href="route('download' , {path : video.path} ) " target="_blank" class="text-blue-500 underline">{{$t('download')}}
                                        <ArrowDownOutlined /></a>
                                </div>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="px-4 py-2 shadow-md border rounded-lg bg-slate-100/50">
                    <div class="min-h-40 ">
                        <div class="mx-4 my-2 text-lg font-bold">{{$t('file_title')}}</div>
                        <ol>
                            <li v-for="file in categorizeFiles(stage.content.files)" class="flex items-center">

                                <img class="w-48" v-if="stage.media.find( x => x.name == file.name )" :src="route('download' , {path : stage.media.find( x => x.name == file.name ).thumbnail } ) " />
                                <!-- <div v-if="isImageFile(file.path)" class="flex gap-4">
                                    <img :src='file.path' class="min-w-24 max-w-48"/> <span class="py-4">{{ file.name }}</span>
                                </div> -->
                                <div  class="text-wrap flex md:flex-row flex-col items-center">
                                    <span class="font-bold text-base mx-2 ">{{ file.name }}</span>
                                    <a :href="route('download' , {path :  file.path} )" download class="text-blue-500 underline">{{$t('download')}}
                                        <ArrowDownOutlined /> </a>
                                </div>
                                <br/>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <a-form :model="items" name="fund" :label-col="{ span: 10 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFinish" enctype="multipart/form-data">

                <div class="md:p-1 px-6">
                    <a-form-item :label="$t('finish')" name="entity">
                        <a-checkbox v-model:checked="items[0].title" value="1"></a-checkbox>
                    </a-form-item>
                </div>
                <div class="flex flex-row item-center justify-center gap-5 py-2 bg-slate-200/50 border-t-2">
                    <a-button @click="goBack()">
                        <ArrowLeftOutlined />{{ $t('go_back') }}</a-button>
                    <a-button type="primary" html-type="submit" >{{ $t('submit') }}
                        <CheckOutlined />
                    </a-button>
                </div>
            </a-form>
        </div>
    </div>

</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    defineComponent,
    reactive
} from "vue";
import StageHeader from "@/Pages/Stages/StageHeader.vue";
import * as AntdIcons from '@ant-design/icons-vue';
import {
    notification
} from 'ant-design-vue';

export default {
    components: {
        AdminLayout,
        StageHeader,
        ...AntdIcons,
    },
    props: ["configStages", "mission", "stage", "page", "task"],
    data() {
        return {
            current: 1,
            items: [{
                title: false,
                content: '已完成'
            }],
            fileList: [],
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    type: "email"
                },
                password: {
                    required: true
                },
            },
            validateMessages: {
                required: "${label} is required!",
                types: {
                    email: "${label} is not a valid email!",
                    number: "${label} is not a valid number!",
                },
                number: {
                    range: "${label} must be between ${min} and ${max}",
                },
            },
        };
    },
    created() {

    },
    mounted() {
        this.updateItemData()
        
        // if (this.stage.tasks.length > 0) {
        //     this.items = this.task
        //     // this.items = this.stage.tasks
        // }

        if (this.stage) {

            this.items = this.items.map(item => {
                return {
                    ...item, // 確保保留原對象的所有屬性
                    stage_id: this.stage.id // 添加 stage_id
                };
            });
        }
        // console.log(this.stage.tasks)
        // if (this.stage.tasks.length > 0) {
        //     this.items = this.stage.tasks
        //     this.items[0].title = this.stage.tasks[0].title? true :false
        // }
    },
    computed: {
        containerStyle() {
            return {
                maxWidth: '100%', // Ensures the container does not exceed screen width
                overflow: 'hidden', // Prevents overflow of content
                padding: '20px',
                boxSizing: 'border-box',
            };
        },
        stepsStyle() {
            return {
                display: 'flex',
                justifyContent: 'space-between', // Distributes steps evenly
                flexWrap: 'nowrap', // Prevents wrapping to the next line
                height: '120px'
            };
        },
    },
    methods: {
        
        isImageFile(fileName) {
            const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.svg'];
            const extension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2).toLowerCase();
            return imageExtensions.includes(`.${extension}`);
        },

        categorizeFiles(data) {
            return data.filter(item => !this.isVideo(item.path))
        },
        categorizeVideos(data) {
            return data.filter(item => this.isVideo(item.path))
        },
        goBack() {
            window.history.back()
        },
        checkEditable() {
            return this.mission.current_stage + 1 <= (Number(this.page))
        },
        updateItemData() {
            if (this.stage) {
                if (this.task.length > 0) {
                    // this.items = this.stage.tasks
                    this.items = this.task
                    this.items[0].title = this.task[0].title == 1 ? true : false;
                }
            }
        },
        onFinish() {
            this.$inertia.patch(
                route("missions.update", this.mission.id), this.items, {
                    onSuccess: (page) => {
                        this.updateItemData();
                        //console.log(page);
                        notification.open({
                            message: 'Finish',
                        });
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
        isVideo(path) {
            const videoExtensions = ['.mp4', '.mkv', '.webm', '.avi', '.mov']; // Add other video formats as needed
            return videoExtensions.some(ext => path.endsWith(ext));
        },
    }
}
</script>

<style>
.rotated-title {
    display: inline-block;
    /* Allows the transform to work correctly */
    transform: rotate(45deg);
    /* Rotates the title 45 degrees clockwise */
    white-space: nowrap;
    /* Prevents wrapping of the title text */
    font-size: 12px;
    /* Adjust font size as needed */
    margin-top: 10px;
    /* Space above the title */
    transform-origin: left bottom;
    /* Sets the origin for the rotation */
    margin-left: -40px;
    /* Adjust this value based on the design */
}
</style>
