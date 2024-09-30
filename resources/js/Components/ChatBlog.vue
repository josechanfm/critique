<template>
<div class="bg-white shadow-md rounded-lg chat-blog" style="">
    <div class="border-b-2 border-green-700 ">
        <a-page-header :title="$t('chat_blog')" class="py-2 " />
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 w-full py-4">
        <div>
            <a-form :model="form" name="basic" :label-col="{ span: 4 }" :wrapper-col="{ span: 16 }" autocomplete="off" enctype="multipart/form-data">

                <a-form-item :wrapper-col="{ offset: 3, span: 8 }">
                    <div class="font-bold">
                        {{$t('chat_blog_message')}}：
                    </div>
                </a-form-item>
                <a-form-item :label="$t('chat')" name="content" class="p-2">
                    <a-textarea :rows="5" v-model:value="form.content" />
                </a-form-item>

                <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                    <a-button type="primary" @click="onFinish">{{ $t('submit') }}</a-button>
                </a-form-item>
            </a-form>
        </div>
        <div class="flex flex-col gap-6 shadow-m py-4 px-2 bg-slate-200 h-[500px] overflow-x-hidden relative rounded-lg grass-background " >
            
            <div class=" rounded shadow px-3 text-base w-[80%] z-10 relative" v-for="(blog,index) in blogs" :key="index" :class="$page.props.auth.user.id == blog.user.id?'ml-auto  bg-green-300 float-right':'bg-white' " style="border-left:1px solid lightgray">
                
                <div class="bg-slate-100 m-2 rounded-lg px-2" v-if="blog.parent">
                    →　{{ blog.parent.content }} <span class="text-slate-500 text-sm">@{{ blog.parent.user.name }} &nbsp; {{ blog.parent.created_at?displayDate(blog.parent.created_at):"" }}</span>
                </div>
                <div class="font-bold ">
                    {{ blog.user.name }} <span class="text-xs text-slate-500">{{ blog.created_at?displayDate(blog.created_at):"" }}</span>
                </div>

                <div class="my-2">
                    {{ blog.content }} &nbsp; <a class="cursor-pointer underline text-blue-500 hover:text-blue-600" @click="replyBlog(blog)">回复</a>

                    <!-- Blog's children -->
                    <!-- <div class="flex gap-2" v-for="child in blog.children">

                        └ {{ child.content }} <span class="text-slate-500">@{{ child.user.name }} &nbsp; {{ child.created_at?displayDate(child.created_at):"" }}</span>
                    </div> -->

                    <!-- Reply Input -->
                    <template v-if="blog.reply && Object.keys(blog.reply).length !== 0">
                        <div class="flex gap-2 ">
                            → <a-input type="input" v-model:value="blog.reply.content"></a-input>
                        </div>
                        <div class="mx-6 flex">
                            <a-button size="small" class="my-1 !text-sm" type="primary" @click="submitReply(blog)">回复</a-button>
                            <a-button size="small" class="mx-1 my-1 !text-sm" type="default" @click="cancelReply(blog)">取消</a-button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
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

        submitReply(blog) {
            this.$inertia.post(route("blogs.store"), blog.reply, {
                onSuccess: (page) => {
                    this.getBlog()
                },
                onError: (error) => {
                    console.log(error);
                },
            });
        },
        cancelReply(blog) {
            blog.reply = {}
        },
        replyBlog(blog) {
            blog.reply = {
                blog_id: blog.id,
                stage_id: this.stage.id,
                content: ""
            }
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

<style>

.chat-blog{
    background: rgb(255,255,255);
    background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(241,255,239,0.8379726890756303) 49%, rgba(227,255,241,0.7539390756302521) 100%);
}
.grass-background{
    
    content: "";
    background-size: cover;
    background-image: url('https://png.pngtree.com/background/20230413/original/pngtree-grassland-outdoor-verdant-picture-image_2423119.jpg');
}

</style>