<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Welcome from '@/Components/Welcome.vue';

const props = defineProps({
    user: {
    },
    missions:{

    },
});
</script>

<template>
<AdminLayout title="首页">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{$t('dashboard')}}
        </h2>
    </template>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg min-h-48">
                <div class="p-5">
                    <p>批判性思维训练社区 汲取了基于挑战学习（Challenge based learning）（简称 CBL）和知识和价值观教育（Value and Knowledge Education）（简称 VaKE）两种学习模型的优势，结合了跨学科的理念，旨在为学习者成功应对21世动荡变化的世界做准备，同时更加注重培养学习者的批判性思维技能，使学习者能够在劳动力市场更具有竞争力。同时随着教育技术的发展，我们在教育方式上也整合了数字工具和在线平台，以适应现代学习的需求。这些步骤基于广泛的教育学、心理学和社会学研究，并结合了教育实践者的经验反馈。我们致力于持续改进，确保我们的教育方法始终处于行业前沿，满足学生和社会的需求。</p>
                    <p>批判性思维训练社区的训练过程 来源于基于挑战学习（Challenge based learning）（简称 CBL）和知识和价值观教育（Value and Knowledge Education）（简称 VaKE）两个创新教育模型，并在此基础上进行了批判性的整合，整合后这套教学模式一共有3个阶段，分别是参与阶段，调查阶段和行动阶段，这三个阶段又包括14个步骤，每个步骤都是进行下一步骤的基础，因此学习者必须遵循这个学习过程基本顺序去完成整个学习过程。</p>
                    <p>
                        
                    </p>
                </div>
            </div>
            <div class="flex bg-white p-4 my-2 rounded-lg shadow-md" v-for="mission in missions">
                <div class="pr-4">
                    <a :href="route('missions.index', {mission_id: mission.id})" class="text-blue-500 underline hover:text-blue-600">{{ $page.props.lang == "en" ? mission.title_en : mission.title }}</a>
                </div>
                <div class="px-4 border-x-2">
                    <a :href="route('missions.evaluation', {mission_id: mission.id})" class="text-white bg-green-500 hover:bg-green-600 rounded-lg shadow-md p-2"> 评估量表</a>
                </div>
                <div class="pl-4">
                    <a :href="route('missions.report', {mission_id: mission.id})" class="text-white bg-blue-500 hover:bg-blue-600 rounded-lg shadow-md p-2"> 查看总览</a>
                </div>
            </div>
        </div>
    </div>
</AdminLayout>
</template>
