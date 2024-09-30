<template>
<div class="container mx-auto pt-5">
    <div class="bg-white relative shadow rounded-lg border-green-600 border-t-2">

        <div class="block sm:hidden flex items-center p-5">
            <a-avatar size="large" :style="{ backgroundColor: '#1890FF', verticalAlign: 'middle' }">{{ page }}</a-avatar>
            <div class="pl-5">{{ steps[page-1]?  steps[page-1].label:'' }}</div>
        </div>
		<div class="block sm:hidden flex justify-between px-3 py-3">
			<a class="px-4 text-blue-500" @click="viewStage( Number(page)-2)">← {{$t('pervious')}}</a>
			<a class="px-4 text-blue-500" @click="viewStage( Number(page))">{{$t('next')}} →</a>
		</div>
        <div :style="containerStyle" class="hidden sm:block">
            <a-steps :current="current" :style="stepsStyle" class="!flex !overflow-clip" :responsive="false">
                <template v-for="(step, index) in steps" :key="index">
                    <a-step @click="viewStage(index)" :status="current == index? 'finish': Number(page) == index+1 ? 'process' :'wait' ">
                        <template v-slot:title>
                            <div style="text-align: center;">
                                <div style="height: 25px;">
                                    <!-- Space for the icon -->
                                    <!-- Optional: Add some icon or step marker here if needed -->
                                </div>
                                <div class="rotated-title" style="font-size:15px;">{{ step.label }}</div> <!-- Step Title -->
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
import {
    computed,
    defineProps
} from 'vue';

// Define props with the correct types
const props = defineProps({
    mission: {
        type: Array,
        required: false,
    },
    current: {
        type: Number,
        required: true,
    },
    steps: {
        type: Array,
        required: true,
    },
    page: {
        type: String,
        required: false,
    }
});

const viewStage = (page) => {
    window.location.href = route('missions.index', {
        "mission_id": props.mission.id,
        "page": page
    });
};

// Define computed styles
const containerStyle = computed(() => ({
    maxWidth: '100%', // Ensures the container does not exceed screen width
    overflow: 'hidden', // Prevents overflow of content
    padding: '15px',
    boxSizing: 'border-box',
}));

const stepsStyle = computed(() => ({
    display: 'flex',
    justifyContent: 'space-between', // Distributes steps evenly
    flexWrap: 'nowrap', // Prevents wrapping to the next line
    height: '140px', // Set the height as desired
}));
</script>
  
  
<style>
/* Add any additional styles here */
.rotated-title {
    /* Sample styles for the rotated title */
    display: inline-block;
    transform: rotate(-45deg);
    /* Adjust as needed */
    white-space: nowrap;
    /* margin-top: 10px; */
}

.ant-steps .ant-steps-item-finish>.ant-steps-item-container>.ant-steps-item-content>.ant-steps-item-title::after {
    background-color: rgba(5, 5, 5, 0.06);
}
</style>
