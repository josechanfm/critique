<template>
    <AdminLayout title="Dashboard">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{$t('config')}}
        </h2>
      </template>
      <div class="container mx-auto pt-5">
        <a-button @click="createRecord()" type="primary">
          {{ $t('mission_create') }}
        </a-button>
        <div class="bg-white relative shadow rounded-lg overflow-x-auto">
          <a-table :dataSource="missions" :columns="columns">
            <template #bodyCell="{ column, text, record, index }">
              <template v-if="column.dataIndex == 'operation'">
                <a-button @click="editRecord(record)">{{ $t('edit') }}</a-button>
                <a-button :href="route('admin.missions.show',record.id)">{{ $t('mission_stage') }}</a-button>
                <a-button :href="route('admin.mission.stages.index',record.id)">{{ $t('mission_task') }}</a-button>
              </template>
              <template v-if="column.dataIndex == 'mission_status'">
                <span v-if="record.status == 1" class="px-3 py-2 rounded bg-green-500 text-white">
                    {{$t('activating')}}
                  </span>
                <a-popconfirm v-else :title="$t('mission_change_status')" @confirm="changeMissionStatus(record.id)">
                  <a class="px-3 py-2 rounded border shadow">
                    {{$t('active')}}
                  </a>
                </a-popconfirm>

              </template>
              <template v-if="column.dataIndex == 'current_stage'">
                <span v-if="text>=14">
                  {{ $t('mission_finish') }}
                </span>
                <span v-else>
                  {{ text+1 }}
                </span>
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
          <a-form-item label="Title" name="title">
            <a-input type="input" v-model:value="modal.data.title" />
          </a-form-item>
          <a-form-item label="Title English" name="title_en">
            <a-input type="input" v-model:value="modal.data.title_en" />
          </a-form-item>
          <!-- <a-form-item label="Description" name="description">
            <a-textarea v-model:value="modal.data.description" :rows="15" />
          </a-form-item> -->
          <a-form-item label="Remark" name="remark">
            <a-textarea v-model:value="modal.data.remark" />
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
    props: ["configStages","missions"],
    data() {
      return {
        modal: {
          isOpen: false,
          data: {},
          title: this.$t('mission'),
          mode: "",
        },
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
    computed:{
      columns(){
        return[
        {
            title: this.$t('mission_title'),
            i18n: "title",
            dataIndex: "title",
          },{
            title: this.$t('mission_current'),
            i18n: "current_stage",
            dataIndex: "current_stage",
          },{
            title: this.$t('mission_status'),
            i18n: "mission_status",
            dataIndex: "mission_status",
          },{
            title: this.$t('operation'),
            i18n: "operation",
            dataIndex: "operation",
            key: "operation",
          },

        ]
      }
    },
    methods: {
      createRecord() {
        this.modal.data = {};
        this.modal.mode = "CREATE";
        this.modal.title = this.$t('mission_create');
        this.modal.isOpen = true;
      },
      editRecord(record) {
        this.modal.data = { ...record };
        // this.modal.data.content = JSON.stringify(record.content);
        this.modal.mode = "EDIT";
        this.modal.title = this.$t('mission_edit');
        this.modal.isOpen = true;
      },
      storeRecord() {
        this.$refs.modalRef
          .validateFields()
          .then(() => {
            this.$inertia.post(route("admin.missions.store"), this.modal.data, {
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
      changeMissionStatus(mission_id){
        this.$inertia.get(
          route("admin.missions.change_status", mission_id),
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
      updateRecord() {
        console.log(this.modal.data);
        this.$inertia.patch(
          route("admin.missions.update", this.modal.data.id),
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
  