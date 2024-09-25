<template>
    <AdminLayout title="Dashboard">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{$t('config')}}
        </h2>
      </template>
      <div class="container mx-auto pt-5">
        
        <a-button @click="createRecord()" type="primary">
          {{$t('create')}}
        </a-button>
        <div class="bg-white relative shadow rounded-lg overflow-x-auto">
          <div class="text-base p-3">current stage: {{ mission.current_stage }}</div>
          <a-table :dataSource="mission.stages" :columns="columns" :pagination="{ pageSize: 20 }">
            <template #bodyCell="{ column, text, record, index }">
              <template v-if="column.dataIndex == 'operation'">
                <a-button @click="editRecord(record)">Edit</a-button>
              </template>
              <template v-else>
                {{ record[column.dataIndex] }}
              </template>
            </template>
          </a-table>
        </div>
      </div>
      <!-- Modal Start-->
      <a-modal v-model:open="modal.isOpen" :title="modal.title" width="60%">
        <a-form
          ref="modalRef"
          :model="modal.data"
          name="Teacher"
          :label-col="{ span: 8 }"
          :wrapper-col="{ span: 16 }"
          autocomplete="off"
          :rules="rules"
          :validate-messages="validateMessages"
        >
        <a-form-item label="Code" name="code">
            <a-input type="input" v-model:value="modal.data.code" />
          </a-form-item>
          <a-form-item label="Title" name="title">
            <a-input type="input" v-model:value="modal.data.title" />
          </a-form-item>
          <a-form-item label="Content" name="content">
            <a-textarea v-model:value="modal.data.content" :rows="10" />
          </a-form-item>
          <a-form-item label="File Extra" name="file_extra">
            
            <div v-for="media in modal.data.media" class="flex flex-row">
              
              <img :src="media.original_url" class="mr-4" style="max-width:60%"/>
              <div class="flex flex-col gap-2 w-full">
                {{$t('title')}}
                <a-input type="input" v-model:value="media.title" placeholder="" />
                {{$t('description')}}
                <a-textarea :rows="5" v-model:value="media.description" placeholder="" />
              </div>
            </div>
          </a-form-item>
          <a-form-item label="Upload File">
            <a-upload
              :file-list="modal.data.files"
              :before-upload="beforeUpload"
              :on-change="handleChange"
              :multiple="true"
              :show-upload-list="true"
              :custom-request="dummyRequest"
            >
              <a-button>
                <UploadOutlined />
                Click to upload
              </a-button>
            </a-upload>
          </a-form-item>
          <a-form-item label="Uploaded File">
            <ol>
              <li v-for="file in modal.data.media" @click="removeMedia(file.id)">{{ file.file_name }}<a class="text-red-500">X</a></li>
            </ol>
          </a-form-item>
        </a-form>
        <template #footer>
          <a-button
            v-if="modal.mode == 'EDIT'"
            key="Update"
            type="primary"
            @click="updateRecord()"
            >Update</a-button
          >
          <a-button
            v-if="modal.mode == 'CREATE'"
            key="Store"
            type="primary"
            @click="storeRecord()"
            >Add</a-button
          >
        </template>
      </a-modal>
      <!-- Modal End-->
    </AdminLayout>
  </template>
  
  <script>
  import AdminLayout from "@/Layouts/AdminLayout.vue";
  import { defineComponent, reactive } from "vue";
  
  export default {
    components: {
      AdminLayout,
    },
    props: ["mission",'files'],
    data() {
      return {
        modal: {
          isOpen: false,
          data: {},
          title: "Modal",
          mode: "",
        },
        teacherStateLabels: {},
        columns: [
          {
            title: "Code",
            i18n: "code",
            dataIndex: "code",
          },{
            title: "Title",
            i18n: "title",
            dataIndex: "title",
          },{
            title: "Content",
            i18n: "content",
            dataIndex: "content",
          },{
            title: "Operation",
            i18n: "operation",
            dataIndex: "operation",
            key: "operation",
          },
        ],
        rules: {
          name: { required: true },
          email: { required: true, type: "email" },
          password: { required: true },
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
      removeMedia(media_id) {
        this.modal.data.media = this.modal.data.media.filter(item => item.id !== media_id);
      },
      createRecord() {
        this.modal.data = {};
        this.modal.mode = "CREATE";
        this.modal.title = "create";
        this.modal.isOpen = true;
      },
      editRecord(record) {
        this.modal.data = { ...record };
        this.modal.data.content = JSON.stringify(record.content);
        this.modal.mode = "EDIT";
        this.modal.title = "edit";
        this.modal.isOpen = true;
      },
      beforeUpload(file){
        const isValid = file.type === 'image/jpeg' || file.type === 'image/png';
        if (!isValid) {
          message.error('You can only upload JPG/PNG file!');
        }
        return isValid;
      },
      handleChange(newFileList){
        this.modal.data.files = newFileList.fileList;
      },
      dummyRequest({file, onSuccess}){
        setTimeout(() => {
          onSuccess(file);
        }, 0);
      },
      storeRecord() {
        
        this.modal.data.content = JSON.parse(this.modal.data.content);

        this.$refs.modalRef
          .validateFields()
          .then(() => {
            this.$inertia.post(route("admin.stages.store"), this.modal.data, {
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
        this.modal.data.content = JSON.parse(this.modal.data.content);
        console.log(this.modal.data);
        this.modal.data._method='PATCH';
        this.$inertia.post(
          route("admin.mission.stages.update", {mission: this.mission.id, stage: this.modal.data.id } ),
          this.modal.data,
          {
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
  