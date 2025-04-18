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
        <div class="bg-white relative shadow rounded-lg">
            <a-row justify="space-between" align="bottom" class=" md:p-7 p-6">
                <a-col :span="12">
                    <div class="pb-4"> {{ stage.content.note }} </div>

                    <a-page-header class="py-3" title="上传 Word 或PDF 的方案" />
                    <ol class="px-4">
                        <li v-for="file in stage.media.filter(m=>m.collection_name=='file')">{{ file.file_name }} <a class="text-red-500" @click="deleteMedia(file.id, 'file')">X</a></li>
                    </ol>
                    <a-upload key="file" v-model:file-list="fileList" :before-upload="beforeUpload" :on-change="handleChangeFile" :multiple="true" :show-upload-list="true" :custom-request="(options) => fileUploader(options, { uploadType: 'file' })">
                        <a-button :disabled="checkEditable()" class="!mx-6">
                            <upload-outlined></upload-outlined>
                            Upload
                        </a-button>
                    </a-upload>
                </a-col>
            </a-row>

            <a-form :model="items" name="fund" :label-col="{ span: 10 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFinish" enctype="multipart/form-data">
                <a-form-item :label="$t('finish')" name="entity">
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
        <div class="my-4">
            <ChatBlog :stage="stage" />
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
import ChatBlog from '@/Components/ChatBlog.vue';
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        StageHeader,
        UploadOutlined,
        ChatBlog,
        ...AntdIcons,
    },
    props: ["configStages", "mission", "stage", "page", "task"],
    data() {
        return {
            current: 1,
            fileList: [],
            items: [{
                title: false,
                content: '已完成'
            }],
        };
    },
    created() {

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

        beforeUpload(file) {
            
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
