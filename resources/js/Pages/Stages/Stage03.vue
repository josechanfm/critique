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

            <div class="flex flex-col md:p-5 p-4">
                <div class="flex items-center gap-4" v-for="(media,index) in stage.media">
                    <div class="flex items-center justify-center h-full w-32 rounded-lg  text-center bg-gray-500 black-black text-lg text-white">
                        <div>困境{{index+1}}:</div>
                    </div>
                    <div class=" flex items-center h-48 cursor-pointer underline text-blue-500 text-lg  ">
                        <div @click="viewDescription(media)" v-if="isImageFile(media.file_name)">
                            <img :src="media.original_url" class="h-32 object-contain " />
                            <div class="text-center">{{media.title}}</div>
                        </div>
                        <div v-else><a download :href="media.original_url" style="height:100%">{{media.file_name}}</a> </div>
                    </div>

                    <div class="cursor-pointer text-blue-500 hover:text-blue-600" @click="viewDescription(media)">查看</div>
                </div>
            </div>
            <a-form :model="items" name="fund" :label-col="{ span: 10 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFinish" enctype="multipart/form-data">

                <div class="mt-8 md:p-1 px-6">
                    <a-form-item :label="$t('finish')" name="entity">
                        <a-checkbox v-model:checked="items[0].title" value="1"></a-checkbox>
                    </a-form-item>
                </div>

                <div class="flex flex-row item-center justify-center gap-5 py-2 bg-slate-200/50 border-t-2">
                    <a-button @click="goBack()">
                        <ArrowLeftOutlined />{{ $t('go_back') }}</a-button>
                    <a-button type="primary" html-type="submit" :disabled="checkEditable()">{{ $t('submit') }}
                        <CheckOutlined />
                    </a-button>
                </div>
            </a-form>
        </div>

        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
            <div class="p-4 text-base ">

                <img :src="modal.media.original_url" class="h-48 object-contain " />

                {{ modal.media.description }}
            </div>
        </a-modal>

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
import ChatBlog from '@/Components/ChatBlog.vue';
import {
    notification
} from 'ant-design-vue';
import * as AntdIcons from '@ant-design/icons-vue';

export default {
    components: {
        AdminLayout,
        StageHeader,
        ChatBlog,
        ...AntdIcons,
    },
    props: ["configStages", "mission", "stage", "page"],
    data() {
        return {

            modal: {
                isOpen: false,
                mode: "",
            },
            current: 1,
            items: [{
                title: null,
                content: '已完成'
            }],
            rules: {
                title: {
                    required: true
                },
                content: {
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
        isImageFile(fileName) {
            const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.svg'];
            const extension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2).toLowerCase();
            return imageExtensions.includes(`.${extension}`);
        },

        viewDescription(data) {
            this.modal.media = data;
            this.modal.isOpen = true;
        },
        goBack() {
            window.history.back()
        },

        updateItemData() {
            if (this.stage) {
                if (this.stage.tasks.length > 0) {
                    this.items = this.stage.tasks
                    this.items[0].title = this.stage.tasks[0].title == 1 ? true : false;
                }
            }
        },
        checkEditable() {
            return this.mission.current_stage + 1 < (Number(this.page))
        },
        onFinish() {
            this.$inertia.patch(
                route("missions.update", this.mission.id), this.items, {
                    onSuccess: (page) => {
                        this.updateItemData();
                        console.log(page);
                        notification.open({
                            message: 'Finish',
                        });
                    },
                    onError: (error) => {
                        console.log(error);
                    },
                }
            );
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
