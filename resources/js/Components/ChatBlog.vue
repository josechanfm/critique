<template>
<div class="grid grid-cols-2 w-full">
    <div>
        <a-card class="">
            <template #title>Chat</template>

            <a-form :model="form" name="basic" :label-col="{ span: 4 }" :wrapper-col="{ span: 16 }" autocomplete="off"  
            enctype="multipart/form-data">
                <a-form-item label="Title" name="title" :rules="[{ required: true, message: 'Please input the title!' }]">
                    <a-input v-model:value="form.title" />
                </a-form-item>

                <a-form-item label="Content" name="content" >
                    <a-textarea :rows="5" v-model:value="form.content" />
                </a-form-item>

                <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                    <a-button type="primary" @click="onFinish">{{ $t('submit') }}</a-button>
                </a-form-item>
            </a-form>
        </a-card>
    </div>
    <div>
        {{ blogs }}
    </div>
</div>
</template>

<script>
import { notification } from 'ant-design-vue';
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
            blogs:[],
        };
    },
    created() {
        this.getBlog()
    },
    mounted() {

        if( this.stage ){
            this.form.stage_id = this.stage.id
        } 
    },
    computed: {},
    methods: {
        getBlog(){
            axios.get(route('blogs.getBlog', this.stage.id))
                .then(response => {
                    this.blogs = response.data
                })
        },
        onFinish() {

            this.$inertia.post( route("blogs.store"), this.form, {
                    onSuccess: (page) => {
                        console.log(page);
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
