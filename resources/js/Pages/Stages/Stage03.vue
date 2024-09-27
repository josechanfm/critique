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
        <div class="bg-white relative shadow rounded-lg md:p-3 p-4">

            <div class="flex flex-col">
                
                <div class="flex items-center gap-4" v-for="(media,index) in stage.media">
                    <div class="flex items-center justify-center h-full w-32 rounded-lg  text-center bg-gray-500 black-black text-lg text-white"><div>困境{{index+1}}:</div></div>
                    <div   class=" flex items-center h-48 cursor-pointer underline text-blue-500 text-lg  ">
                        <div @click="viewDescription(media)" v-if="isImageFile(media.file_name)" >
                            <img :src="media.original_url"  class="h-32 object-contain " />
                            <div class="text-center">{{media.title}}</div>
                        </div>
                        <div v-else><a  download :href="media.original_url" style="height:100%" >{{media.file_name}}</a> </div>
                    </div>
                    
                    <div @click="viewDescription(media)">查看</div>
                </div>
            </div>

            <div class="mt-8">
                <a-form :model="items" name="fund" :label-col="{ span: 10 }" autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFinish" enctype="multipart/form-data">
                    <a-form-item :label="$t('finish')" name="entity">
                        <a-checkbox v-model:checked="items[0].title" value="1"></a-checkbox>
                    </a-form-item>

                    <div class="flex flex-row item-center justify-center gap-5 pt-5">
                        <a-button @click="goBack()">{{ $t('go_back') }}</a-button>
                        <a-button type="primary" html-type="submit" :disabled="checkEditable()">{{ $t('submit') }}</a-button>
                    </div>
                </a-form>
            </div>
        </div>

        <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
            <div class="p-4 text-base ">
                {{ modal.description }}
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

export default {
    components: {
        AdminLayout,
        StageHeader,
        ChatBlog
    },
    props: ["configStages", "mission", "stage", "page"],
    data() {
        return {

            modal: {
                isOpen: false,
                description: "",
                title: "Description",
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
        isImageFile(fileName) {
            const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.svg'];
            const extension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2).toLowerCase();
            return imageExtensions.includes(`.${extension}`);
        },

        viewDescription(data) {
            this.modal.description = data.description;
            this.modal.title = data.title;
            this.modal.isOpen = true;
        },
        goBack() {
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
