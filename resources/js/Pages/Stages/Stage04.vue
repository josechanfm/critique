<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page.props.lang == "en" ? mission.title_en : mission.title }}
        </h2>
    </template>
    <StageHeader :mission="mission" :current="mission.current_stage" :steps="configStages" :page="page" />

    {{items}}
    <div class="container mx-auto pt-5">
        <div class="bg-white flex w-40 justify-center p-3 my-2 rounded shadow">{{ configStages[Number(page)-1].label }}</div>
        <div class="flex flex-row">
            <div class="bg-white rounded shadow w-48 p-4" v-if="stage.remark">
                {{ stage.remark }}
            </div>
            <div class="bg-white relative shadow rounded-lg flex-1 ">
                <a-form :model="items" name="fund" :label-col="labelCol" autocomplete="off" :rules="rules" :validate-messages="validateMessages" @finish="onFinish" enctype="multipart/form-data">
                    <div class="md:p-5 p-4">
                        <template v-for="(c,idx) in stage.content">
                            <label class="font-bold">{{ c.title }}</label>
                            <p>{{ c.note }}</p>
                            <a-input hidden type="input" v-if="items[idx]" v-model:value="items[idx].title" :placeholder="c.placeholder" />
                            <a-input type="input" v-model:value="items[idx].content" :placeholder="c.placeholder" />
                            <a-divider />
                        </template>
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
    props: ["configStages", "mission", "stage", "page" , "task"],
    data() {
        return {
            current: 1,
            items: [{
                    title: null,
                    content: null
                },
                {
                    title: null,
                    content: null
                },
                {
                    title: null,
                    content: null
                },
                {
                    title: null,
                    content: null
                },
                {
                    title: null,
                    content: null
                },
                {
                    title: null,
                    content: null
                }
            ],
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
        console.log(this.$router)
    },
    mounted() {
        if (this.task.length > 0) {
            this.items = this.task
            // this.items = this.stage.tasks
        }

        console.log( this.stage.content )

        this.stage.content.map((x, index) => {
            console.log( this.items[index] )
            if( x.title !== undefined && this.items[index]){
                this.items[index].title = x.title
            }
        })

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
        onFinish() {
            this.$inertia.patch(
                route("missions.update", this.mission.id), this.items, {
                    onSuccess: (page) => {
                        // this.items = this.stage.tasks
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
