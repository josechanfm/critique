<template>
<AdminLayout>

    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            阅读
        </h2>
    </template>
    <div class="container mx-auto pt-5">
        <div class="">
            
            <div class="flex flex-col gap-4">
                <div class="bg-white relative shadow rounded-lg overflow-x-auto px-6 py-4" v-for="(r, index ) in readings">
                    <div class="text-base font-bold border-b-2 my-4">{{ r.title }}</div>
                    <div class="grid grid-cols-2">
                        <div>
                            <div>上传影片:</div>
                            <div class="my-2 " v-for="media in r.media.filter( x => x.collection_name == 'video')">
                                <a class="!text-blue-500 hover:text-blue-600" :href="media.original_url">{{ media.file_name }}</a>
                            </div>
                            <div class="flex gap-2 flex-col">
                                <a-upload  :before-upload="beforeUpload" :on-change="handleChangeVideo" :multiple="false" :show-upload-list="true" :custom-request="dummyRequest">
                                    <a-button @click="setUpload(r)">
                                        <UploadOutlined />
                                        Click to upload
                                    </a-button><br>
                                </a-upload>
                                <a-button class="w-32" type="primary" @click="uploadVideo()">Confirm</a-button>
                            </div>
                        </div>
                        <div>
                            <div>上传档案:</div>
                            <div class="my-2 " v-for="media in r.media.filter( x => x.collection_name == 'file')">
                                <a class="!text-blue-500 hover:text-blue-600" :href="media.original_url">{{ media.file_name }}</a>
                            </div>
                            <div class="flex gap-2 flex-col">
                                <a-upload  :before-upload="beforeUpload" :on-change="handleChangeFile" :multiple="false" :show-upload-list="true" :custom-request="dummyRequest">
                                    <a-button @click="setUpload(r)">
                                        <UploadOutlined />
                                        Click to upload
                                    </a-button><br>
                                </a-upload>
                                <a-button class="w-32" type="primary" @click="uploadFile()">Confirm</a-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import dayjs, {
    Dayjs
} from 'dayjs';

import {
    defineComponent,
    reactive
} from "vue";
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        ...AntdIcons,
    },
    props: ["readings"],
    data() {
        return {
            activeKey: 0,
            videoList:[],
            fileList:[],
            reading:[],
        }
    },
    methods:{
        setUpload(reading){
            this.reading = reading
        },
        handleChangeVideo(newFileList) {
            this.videoList = newFileList.fileList;
        },
        beforeUpload(file) {
            return true
        },
        handleChangeFile(newFileList) {
            this.fileList = newFileList.fileList;
        },
        dummyRequest({
            file,
            onSuccess
        }) {
            setTimeout(() => {
                onSuccess(file);
            }, 0);
        },
        uploadVideo(){
            this.$inertia.post(route("admin.readings.upload", { collection:'video', reading: this.reading }), this.videoList ,{
                onSuccess: (page) => {
                    location.reload()
                },
                onError: (err) => {
                    console.log(err);
                },
            });
        },
        uploadFile(){
            this.$inertia.post(route("admin.readings.upload", { collection:'file', reading: this.reading }), this.fileList, {
                onSuccess: (page) => {
                    location.reload()
                },
                onError: (err) => {
                    console.log(err);
                },
            });
        },
    }
}
</script>
