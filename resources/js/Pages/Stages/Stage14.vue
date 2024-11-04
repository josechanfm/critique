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
            <div class="grid md:grid-cols-3 grid-cols-1 md:p-7 p-6" justify="space-between" align="bottom">
                <div>
                    <div class="text-wrap px-4 py-2 font-bold text-lg">上传实施的视频资料，限制大小为50M-500M</div>
                    <div class="px-4 text-sm">可将影片上传至云端硬碟(如百度云、阿里云、google drive)，并输入链接</div>
                    <div class="flex flex-row w-[80%]">
                        <div class="flex flex-col flex-1">
                            <a-input class="my-1" type="input" v-for="v in video" v-model:value="v.content" :placeholder="stage.content.inputPlaceholder" />
                        </div>
                        <a @click="addNewVideo" class="mt-1 text-red-500 text-lg px-2" href="#">+</a>
                    </div>
                    <!-- <ol class="px-2">
                        <li v-for="video in stage.media.filter(m=>m.collection_name=='video')">{{ video.file_name }} <a class="text-red-500 cursor-pointer" @click="deleteMedia(video.id, 'video')">X</a></li>
                    </ol>
                    <a-upload key="video" v-model:file-list="videoList" :before-upload="beforeVideoUpload" :on-change="handleChangeVideo" :multiple="true" :show-upload-list="true" :custom-request="(options) => fileUploader(options, { uploadType: 'video' })">
                        <a-button :disabled="checkEditable()" class="!mx-6">
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload> -->
                </div>
                <div :span="8">
                    <div class="text-wrap p-4 font-bold text-lg">上传 评估结果 （Word 或 PDF 版本均可以）</div>

                    <ol class="px-2">
                        <li v-for="file in stage.media.filter(m=>m.collection_name=='file')">{{ file.file_name }} <a class="text-red-500 cursor-pointer">X</a></li>
                    </ol>
                    <a-upload key="file" v-model:file-list="fileList" :before-upload="beforeUpload" :on-change="handleChangeFile" :multiple="true" :show-upload-list="true" :custom-request="(options) => fileUploader(options, { uploadType: 'file' })">
                        <a-button :disabled="checkEditable()">
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </div>
                <div :span="8">
                    <div class="text-wrap p-4 font-bold text-lg">上传完善后的最终方案（Word 或 PDF 版本均可以）</div>

                    <ol class="px-2">
                        <li v-for="file in stage.media.filter(m=>m.collection_name=='finalFile')">{{ file.file_name }} <a class="text-red-500 cursor-pointer">X</a></li>
                    </ol>
                    <a-upload key="finalFile" v-model:file-list="finalFileList" :before-upload="beforeUpload" :on-change="handleChangeFinalFile" :multiple="true" :show-upload-list="true" :custom-request="(options) => fileUploader(options, { uploadType: 'finalFile' })">
                        <a-button :disabled="checkEditable()">
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </div>
            </div>

            <a-form :model="items" name="fund" :label-col="{ span: 10 }" autocomplete="off" @finish="onFinish" enctype="multipart/form-data">
                <a-form-item :label="$t('read_finish')" name="entity">
                    <a-checkbox v-model:checked="items[0].title" value="1"></a-checkbox>
                </a-form-item>
                <div class="flex flex-row item-center justify-center gap-5 py-2 bg-slate-200/50 border-t-2">
                    <a-button @click="goBack()">
                        <ArrowLeftOutlined />{{ $t('go_back') }}</a-button>
                    <a-button type="primary" html-type="submit" :disabled="checkEditable()">{{ $t('submit') }}
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

import {
    notification
} from 'ant-design-vue';
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        StageHeader,
        notification,
        ...AntdIcons,
    },
    props: ["configStages", "mission", "stage", "page", "task"],
    data() {
        return {
            current: 1,
            videoList: [],
            fileList: [],
            finalFileList: [],

            items: [{
                title: false,
                content: '已完成'
            }],
            video: [],
        };
    },
    created() {
        this.addNewVideo()
        let exist = this.task.filter( x => x. title == "video")

        if( exist.length > 0 ){
            this.video = exist
        }
    },
    mounted() {
        this.updateItemData()

        if (this.stage) {

            this.items = this.items.map(item => {
                return {
                    ...item, // 確保保留原對象的所有屬性
                    stage_id: this.stage.id // 添加 stage_id
                };
            });
        }
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
        addNewVideo() {
            this.video.push({
                stage_id: this.stage.id,
                title: 'video',
                content: '',
            })
        },
        goBack() {
            window.history.back()
        },
        checkEditable() {
            return this.mission.current_stage + 1 < (Number(this.page))
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
            this.video.map(x => {
                if (x.content != '') this.items.push(x)
            })
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

        beforeVideoUpload(file) {
            if (file.size >= 550) {
                notification.error({
                    message: '视频大小过大',
                });
                return false
            }
            return true
        },
        beforeUpload(file) {
            
        },
        handleChangeVideo(newVideoList) {
            this.videoList = newVideoList.videoList;
        },
        handleChangeFile(newFileList) {
            this.fileList = newFileList.fileList;
        },
        handleChangeFinalFile(newFinalFileList) {
            this.finalFileList = newFinalFileList.fileList;
        },
        fileUploader({
            file,
            onSuccess,
            onError
        }, {
            uploadType
        }) {
            const formData = new FormData();
            formData.append('uploadType', uploadType);
            formData.append('file', file);
            formData.append('finalFile', file);

            axios.post(route('mission.stage.upload', this.stage.id), formData)
                .then(response => {
                    console.log(response.data);
                    onSuccess(response.data);

                    notification.open({
                        message: 'Finish',
                    });
                })
                .catch(error => {
                    onError(error);
                });
        },
        deleteMedia(media_id, mediaType) {
            this.$inertia.get(route('mission.stage.deleteUpload', {
                "stage": this.stage.id,
                "media_id": media_id,
                "mediaType": mediaType
            }), {
                onSuccess: (page) => {
                    console.log(page);
                    notification.open({
                        message: 'Finish',
                    });
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        }
    },
};
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
