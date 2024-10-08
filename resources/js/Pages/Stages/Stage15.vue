<template>
<AdminLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ $page.props.lang == "en" ? mission.title_en : mission.title }} 
        </h2>
    </template>
    <StageHeader :mission="mission" :current="mission.current_stage" :steps="configStages" :page="page"/>

    <div class="container mx-auto pt-5">
        <div class="bg-white relative shadow rounded-lg md:p-5 p-4">
            <div class="flex justify-center">
                <div class="flex flex-col items-center gap-4">
                    <img src="images/final_reward.png" width="600px" />
                    <div class="text-2xl font-black text-red-500">
                        恭喜您已经成功的完成了批判性思维训练的所有步骤。
                    </div>
                </div>
            </div>
        </div>
    </div>

</AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {
    defineComponent,
    reactive
} from "vue";
import StageHeader from "@/Pages/Stages/StageHeader.vue";

export default {
    components: {
        AdminLayout,
        StageHeader
    },
    props: ["configStages", "mission", "stage"],
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
    methods: {},
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
