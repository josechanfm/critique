<template>
    <AdminLayout title="Dashboard">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Config
        </h2>
      </template>

      <div class="container mx-auto pt-5">
        <div class="bg-white relative shadow rounded-lg overflow-x-auto">
          <div>顯示檔前階段的內容, 並提供進入下一個階段的按鈕.</div>


          {{stage}}
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
            <a-input v-model:value="modal.data.code" />
          </a-form-item>
          <a-form-item label="Title" name="title">
            <a-input v-model:value="modal.data.title" />
          </a-form-item>
          <div>
            {{ modal.data.tasks }}
          </div>
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
    props: ["stage"],
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
            title: "Start",
            i18n: "started_at",
            dataIndex: "started_at",
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
  