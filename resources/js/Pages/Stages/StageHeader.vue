<template>
      <div class="container mx-auto pt-5">
      <div class="bg-white relative shadow rounded-lg">

      <div class="block sm:hidden flex items-center p-5">
        <a-avatar size="large" :style="{ backgroundColor: '#1890FF', verticalAlign: 'middle' }">{{ current+1 }}</a-avatar>
        <div class="pl-5">{{ steps[current].label }}</div>
      </div>
    <!-- <div :style="containerStyle" class="hidden sm:block"> -->
    <div :style="containerStyle">
      <a-steps :current="current" :style="stepsStyle" class="!flex !overflow-clip" :responsive="false">
        <template v-for="(step, index) in steps" :key="index">
          <a-step>
            <template v-slot:title>
              <div style="text-align: center;">
                <div style="height: 40px;"> <!-- Space for the icon -->
                  <!-- Optional: Add some icon or step marker here if needed -->
                </div>
                <div class="rotated-title">{{ step.label}}</div> <!-- Step Title -->
              </div>
            </template>
          </a-step>
        </template>
      </a-steps>
    </div>

    </div>
    </div>
  </template>
  
  <script setup>
  import { computed, defineProps } from 'vue';
  
  // Define props with the correct types
  const props = defineProps({
    current: {
      type: Number,
      required: true,
    },
    steps: {
      type: Array,
      required: true,
    },
  });
  
  // Define computed styles
  const containerStyle = computed(() => ({
    maxWidth: '100%', // Ensures the container does not exceed screen width
    overflow: 'hidden', // Prevents overflow of content
    padding: '20px',
    boxSizing: 'border-box',
  }));
  
  const stepsStyle = computed(() => ({
    display: 'flex',
    justifyContent: 'space-between', // Distributes steps evenly
    flexWrap: 'nowrap', // Prevents wrapping to the next line
    height: '120px', // Set the height as desired
  }));
  </script>
  
  <style>
  /* Add any additional styles here */
  .rotated-title {
    /* Sample styles for the rotated title */
    display: inline-block;
    transform: rotate(-45deg); /* Adjust as needed */
    white-space: nowrap;
    margin-top: 10px;
  }
  </style>