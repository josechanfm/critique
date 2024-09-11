<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $t('my_project') }}
      </h2>
    </template>
    <StageHeader :current="mission.current_stage" :steps="configStages"/>

    <div class="container mx-auto pt-5">
      <div class="bg-white relative shadow rounded-lg p-5">
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
          <template v-for="(c,idx) in stage.content">
            <label class="font-bold">{{ c.title }}</label>
            <p>{{ c.note }}</p>
            <a-input v-model:value="items[idx].title" :placeholder="c.placeholder"/>
            <a-devider/>
          </template>
          <div class="flex flex-row item-center justify-center gap-5 pt-5">
            <a-button >{{ $t('back') }}</a-button>
            <a-button type="primary" html-type="submit">{{ $t('submit') }}</a-button>
          </div>
        </a-form>
      </div>
    </div>

  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, reactive } from "vue";
import StageHeader from "@/Pages/Stages/StageHeader.vue";


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
        {title:"理解困境",content:null},
        {title:"第一次反思",content:null},
        {title:"初步的决定",content:null},
        {title:"初次的讨论",content:null},
        {title:"第二次反思",content:null},
        {title:"明确问题",content:null}
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
    console.log(this.items)
  },
  mounted(){
    if( this.stage.tasks.length>0 ){
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
    onFinish(){
      this.$inertia.patch(
          route("missions.update", this.mission.id),this.items,{
            onSuccess: (page) => {
              this.items=this.stage.tasks
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