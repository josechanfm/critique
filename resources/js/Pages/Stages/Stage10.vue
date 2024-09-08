<template>
  <AdminLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ $t('my_project') }}
      </h2>
    </template>
    <StageHeader :current="mission.current_stage" :steps="configStages"/>

    <div class="container mx-auto pt-5">
      <div class="bg-white relative shadow rounded-lg">
        <a-row justify="space-between" align="bottom" class="h-32 p-2">
          <a-col :span="12" class="bg-red-100">
            <div style="height:150px">
              {{ mission }}
              col-4
            </div>
          </a-col>
          <a-col :span="12" class="bg-blue-100">
            <div style="height:150px">
              col-4
            </div>
          </a-col>
        </a-row>
      </div>
    </div>

  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { defineComponent, reactive } from "vue";
import StageHeader from "@/Pages/StageHeader.vue";


export default {
  components: {
    AdminLayout,
    StageHeader
  },
  props: ["configStages","mission"],
  data() {
    return {
      current: 1
    };
  },
  created() {

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