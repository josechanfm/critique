<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $t('my_project') }}
      </h2>
    </template>
    <StageHeader :current="mission.current_stage" :steps="configStages"/>

    <div class="container mx-auto pt-5">
      <div class="bg-white relative shadow rounded-lg md:p-5 p-4 ">
        <div class="max-w-7xl mx-auto">
          <template v-for="item in stage.content">
            <a-row>
              <a-col><div><img :src="item.image" width="100px"/></div></a-col>
              <a-col><div>{{ item.title }}</div></a-col>
            </a-row>
          </template>
          <a-form
            :model="items"
            name="fund"
            :label-col="labelCol"

            autocomplete="off"
            :rules="rules"
            :validate-messages="validateMessages"
            @finish="onFinish"
            enctype="multipart/form-data"
          >
            <a-form-item label="已完成" name="entity">
              <a-checkbox v-model:checked="items[0].title">Checkbox</a-checkbox>
            </a-form-item>

            <div class="flex flex-row item-center justify-center gap-5 pt-5">
              <a-button >{{ $t('back') }}</a-button>
              <a-button type="primary" html-type="submit">{{ $t('submit') }}</a-button>
            </div>
          </a-form>
        </div>
      </div>
    </div>

  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, reactive } from "vue";
import StageHeader from "@/Pages/Stages/StageHeader.vue";
import { notification } from 'ant-design-vue';


export default {
  components: {
    AdminLayout,
    StageHeader
  },
  props: ["configStages","mission","stage"],
  data() {
    return {
      current: 1,
      items:[
        {title:null,content:'已完成'}
      ],
      rules: {
          title: { required: true },
          content: { required: true},
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
  mounted(){
    if( this.stage.tasks.length>0 ){
      this.items.title = this.stage.tasks[0].title==1?true:false
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
    onFinish(){
      this.$inertia.patch(
          route("missions.update", this.mission.id),this.items,{
            onSuccess: (page) => {
              this.items=this.stage.tasks
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
