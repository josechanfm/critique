<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$t('config')}}
        </h2>
    </template>

    <div class="container mx-auto pt-5">
        <div class="text-lg mb-4">
            <span>{{$t('current_stage')}}:</span>
        </div>
        <a-card :bordered="false" class="!border-t border-blue-700 w-full">
            <template #title>
                <div class="flex items-center">
                    <div class="p-3 w-12 text-center mx-2 bg-blue-300 text-white rounded-full">{{ stage['code'].substring(-1).replace("S", '') }}</div> {{ stage['title'] }}
                </div>
            </template>

            <div v-if="['S07', 'S13', 'S14'].includes( stage.code ) ">
                <div v-if="stage.media.length> 0" class="text-base my-2">Uploaded File: </div>
                <div class="flex flex-row gap-4">

                    <div class="flex flex-col gap-2">
                        视频
                        <a v-for="media in filterCollection( stage.media ,'video') " :href="media.original_url" class="px-4 underline text-blue-500">{{media.name}}</a>
                    </div>
                    <div class="flex flex-col gap-2">
                        档案
                        <a v-for="media in filterCollection( stage.media ,'file') " :href="media.original_url" class="px-4 underline text-blue-500">{{media.name}}</a>
                    </div>
                    <div class="flex flex-col gap-2">
                        最终方案
                        <a v-for="media in filterCollection( stage.media ,'finalFile') " :href="media.original_url" class="px-4 underline text-blue-500">{{media.name}}</a>
                    </div>

                </div>
            </div>
            
            <div class="flex flex-row " v-for="task in stage.tasks" v-if="stage.tasks.length>0">
                <div v-if="task.title == '1'"> 
                    <CheckOutlined class="text-green-500"/> 用戶已完成
                </div>
                <div v-else-if="task.title == '0'"> 
                    用戶未完成
                </div>
                <div v-else class="text-base py-1">
                    <span class="">{{ task.title }}</span> <br>
                    <span class="font-bold">{{ task.content }}</span>
                </div>
            </div>
        </a-card>
        <div class="my-4 flex justify-between">
            <a-popconfirm title="Are you sure approve to next stage?" ok-text="Yes" cancel-text="No" @confirm="approveToNextStage">
                <a-button type="primary">Approve to Next Stage</a-button>
            </a-popconfirm>
            <a-popconfirm title="Are you sure back to previous stage?" ok-text="Yes" cancel-text="No" @confirm="backToPreviousStage">
                <a-button type="default">Back to Previous Stage</a-button>
            </a-popconfirm>
        </div>
        <a-divider />
        <div class="text-lg mb-4">
            <span>{{$t('previous_stage')}}:</span>
        </div>

        <div class="flex flex-col-reverse gap-4">
            <template v-for="stage in mission.stages">
                <a-card :bordered="false" class="" v-if="stage.code.slice(-2) < (this.mission.current_stage+1)">
                    <template #title>
                        {{ stage['code'].substring(-1) }} : {{ stage['title'] }}
                    </template>
                    <div class="flex flex-row">
                        <div v-for="task in stage.tasks" v-if="stage.tasks.length>0">
                            {{ task.title }} <br>
                            {{ task.content }}
                        </div>
                        <div v-else>
                            No tasks
                        </div>
                    </div>
                </a-card>
            </template>
        </div>

    </div>
    <!-- Modal Start-->
    <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
        <a-form ref="modalRef" :model="modal.data" name="Teacher" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages">
            <a-form-item label="Code" name="code">
                <a-input type="input" v-model:value="modal.data.code" />
            </a-form-item>
            <a-form-item label="Title" name="title">
                <a-input type="input" v-model:value="modal.data.title" />
            </a-form-item>
            <div>
                {{ modal.data.tasks }}
            </div>
        </a-form>
        <template #footer>
            <a-button v-if="modal.mode == 'EDIT'" key="Update" type="primary" @click="updateRecord()">Update</a-button>
            <a-button v-if="modal.mode == 'CREATE'" key="Store" type="primary" @click="storeRecord()">Add</a-button>
        </template>
    </a-modal>
    <!-- Modal End-->
</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    notification
} from 'ant-design-vue';

import {
    defineComponent,
    reactive
} from "vue";
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        notification,
		...AntdIcons,
    },
    props: ["stage", "mission"],
    data() {
        return {

            modal: {
                isOpen: false,
                data: {},
                title: "Modal",
                mode: "",
            },
            teacherStateLabels: {},
            columns: [{
                title: "Code",
                i18n: "code",
                dataIndex: "code",
            }, {
                title: "Title",
                i18n: "title",
                dataIndex: "title",
            }, {
                title: "Start",
                i18n: "started_at",
                dataIndex: "started_at",
            }, {
                title: "Operation",
                i18n: "operation",
                dataIndex: "operation",
                key: "operation",
            }, ],
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
            labelCol: {
                style: {
                    width: "150px",
                },
            },
        };
    },
    created() {

    },
    methods: {
        filterCollection(media, collection_name) {
            return media.filter(x => x.collection_name == collection_name)
        },
        approveToNextStage() {
            this.$inertia.get(route("admin.missions.approve", this.stage.mission_id), {
                onSuccess: (page) => {
                    console.log(page)
                    notification.open({
                        message: 'Finish',
                    });
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        },
        backToPreviousStage() {

            this.$inertia.get(route("admin.missions.regret", this.stage.mission_id), {
                onSuccess: (page) => {
                    notification.open({
                        message: 'Finish',
                    });
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        },
        createRecord() {
            this.modal.data = {};
            this.modal.mode = "CREATE";
            this.modal.title = "create";
            this.modal.isOpen = true;
        },
        editRecord(record) {
            this.modal.data = {
                ...record
            };
            this.modal.data.content = JSON.stringify(record.content);
            this.modal.mode = "EDIT";
            this.modal.title = "edit";
            this.modal.isOpen = true;
        },
        storeRecord() {
            this.$refs.modalRef
                .validateFields()
                .then(() => {
                    this.$inertia.post(route("admin.configs.store"), this.modal.data, {
                        onSuccess: (page) => {
                            this.modal.data = {};
                            this.modal.isOpen = false;
                        },
                        onError: (err) => {
                            console.log(err);
                        },
                    });
                })
                .catch((err) => {
                    console.log(err);
                });
        },
        updateRecord() {
            console.log(this.modal.data);
            this.$inertia.patch(
                route("admin.configs.update", this.modal.data.id),
                this.modal.data, {
                    onSuccess: (page) => {
                        this.modal.data = {};
                        this.modal.isOpen = false;
                        console.log(page);
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
        },
    },
};
</script>
