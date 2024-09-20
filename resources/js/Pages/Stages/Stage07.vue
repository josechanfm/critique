<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ $page.props.lang == "en" ? mission.title_en : mission.title }} 
        </h2>
    </template>
    <StageHeader :current="mission.current_stage" :steps="configStages" :page="page"/>

    <div class="container mx-auto pt-5">
        <div class="bg-white flex w-40 justify-center p-3 my-2 rounded shadow">{{ configStages[Number(page)-1].label }}</div>
        <div class="bg-white relative shadow rounded-lg md:p-5 p-4">
            <a-row justify="space-between" align="bottom" class="p-2">
                <a-col :span="12">
                    <a-page-header class="py-3" title="上传视频资料" />
                    <ol>
                        <li v-for="video in stage.media.filter(m=>m.collection_name=='video')">
                            {{ video.file_name }}
                            <a v-if="!checkEditable()" class="text-red-500" @click="deleteMedia(video.id, 'video')">X</a>
                        </li>
                    </ol>
                    <a-upload key="video" v-model:file-list="videoList" :before-upload="beforeUpload" :on-change="handleChangeVideo" :multiple="true" :show-upload-list="true" :custom-request="(options) => fileUploader(options, { uploadType: 'video' })">
                        <a-button :disabled="checkEditable()" class="!mx-6">
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </a-col>
                <a-col :span="12">
                    <a-page-header class="py-3" title="上传PDF资料" />
                    <ol>
                        <li v-for="file in stage.media.filter(m=>m.collection_name=='file')">
                            {{ file.file_name }} 
                            <a v-if="!checkEditable()" class="text-red-500">X</a>
                        </li>
                    </ol>
                    <a-upload key="file" v-model:file-list="fileList" :before-upload="beforeUpload" :on-change="handleChangeFile" :multiple="true" :show-upload-list="true" :custom-request="(options) => fileUploader(options, { uploadType: 'file' })">
                        <a-button :disabled="checkEditable()" >
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </a-col>
                <div class="flex item-center justify-center gap-5 mt-4 pt-5 mx-auto">
                    <a-button @click="goBack()">{{ $t('go_back') }}</a-button>
                </div>
            </a-row>
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
    UploadOutlined
} from '@ant-design/icons-vue';

import {
    notification
} from 'ant-design-vue';

export default {
    components: {
        AdminLayout,
        StageHeader,
        UploadOutlined,
        notification
    },
    props: ["configStages", "mission", "stage", "page"],
    data() {
        return {
            current: 1,
            videoList: [],
            fileList: [],
        };
    },
    created() {

    },
    mounted() {
        if (this.stage.tasks.length > 0) {
            this.items = this.stage.tasks
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

        goBack(){
      window.history.back()
    },
        checkEditable() {
            return this.mission.current_stage + 1 !== (Number(this.page))
        },
        onFinish() {
            this.$inertia.patch(
                route("missions.update", this.mission.id), this.items, {
                    onSuccess: (page) => {
                        this.items = this.stage.tasks
                        console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
        beforeUpload(file) {
            const isValid = file.type === 'image/jpeg' || file.type === 'image/png';
            if (!isValid) {
                message.error('You can only upload JPG/PNG file!');
            }
            return isValid;
        },
        handleChangeVideo(newVideoList) {
            this.videoList = newVideoList.videoList;
        },
        handleChangeFile(newFileList) {
            this.fileList = newFileList.fileList;
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
