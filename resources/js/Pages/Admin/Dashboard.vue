<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    Admin Backend Platform <br>

                    You can edit the <u>Mission</u> & <u>Stages</u> Name that showing to user<br>

                    Futhermore, Task is what the user input. <br>
                    <inertia-link :href="route('admin.missions.index')">Missions</inertia-link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
