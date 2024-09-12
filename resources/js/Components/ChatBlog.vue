<template>
<div class="grid grid-cols-2 w-full bg-white py-4">
    <div>
        <a-form :model="form" name="basic" :label-col="{ span: 4 }" :wrapper-col="{ span: 16 }" autocomplete="off" enctype="multipart/form-data">

            <a-form-item label="Chat" name="content">
                <a-textarea :rows="5" v-model:value="form.content" />
            </a-form-item>

            <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                <a-button type="primary" @click="onFinish">{{ $t('submit') }}</a-button>
            </a-form-item>
        </a-form>
    </div>
    <div class="flex flex-col gap-6 shadow-m py-4 px-2 bg-slate-200">

        <template v-for="blog in blogs" >
            <div class=" rounded shadow px-3 text-base" v-if="blog.parent == null" style="border-left:1px solid lightgray" :class="$page.props.auth.user.id == blog.user.id?'bg-green-300':'bg-white' ">

                <div class="font-bold ">
                    {{ blog.user.name }} <span class="text-xs text-slate-500">{{ blog.created_at?displayDate(blog.created_at):"" }}</span>
                </div>

                <div class="my-2">
                    {{ blog.content }} &nbsp; <a class="cursor-pointer underline text-blue-500 hover:text-blue-600" @click="replyBlog(blog)">回复</a>
                    
                    <!-- Blog's children -->
                    <template v-if="blog.children.length>0">
                        <div class="flex gap-2" v-for="child in blog.children">
                            
                            └  {{ child.content }} <span class="text-slate-500">@{{ child.user.name }} &nbsp; {{ child.created_at?displayDate(child.created_at):"" }}</span>
                        </div>
                    </template>

                    <!-- Reply Input -->
                    <template v-if="blog.reply && Object.keys(blog.reply).length !== 0">
                        <div class="flex gap-2" >
                            └  <a-input v-model:value="blog.reply.content"></a-input>
                        </div>
                        <div class="mx-6">
                            <a-button size="small" class="my-1 !text-sm" type="primary" @click="submitReply(blog)">回复</a-button>
                            <a-button size="small" class="mx-1 my-1 !text-sm" type="default" @click="cancelReply(blog)">取消</a-button>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
</template>

<script>
import {
    notification
} from 'ant-design-vue';
import dayjs, {
    Dayjs
} from 'dayjs';
export default {
    components: {
        notification,
    },
    props: ["stage"],
    data() {
        return {
            form: {
                title: "",
                content: ""
            },
            blogs: [],
        };
    },
    created() {
        this.getBlog()
    },
    mounted() {

        if (this.stage) {
            this.form.stage_id = this.stage.id
        }
    },
    computed: {},
    methods: {
        submitReply(blog){
            this.$inertia.post(route("blogs.store"), blog.reply, {
                onSuccess: (page) => {
                    this.getBlog()
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        },
        cancelReply(blog){
            blog.reply = { }
        },
        replyBlog(blog){
            blog.reply = { blog_id: blog.id ,stage_id: this.stage.id, content: "" }
        },
        getBlog() {
            axios.get(route('blogs.getBlog', this.stage.id))
                .then(response => {
                    this.blogs = response.data
                })
        },
        onFinish() {

            this.$inertia.post(route("blogs.store"), this.form, {
                onSuccess: (page) => {
                    this.getBlog()
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        },
        displayDate(date) {
            return dayjs(date).format('YYYY-MM-DD HH:mm:ss')
        },
    },
};
</script>
