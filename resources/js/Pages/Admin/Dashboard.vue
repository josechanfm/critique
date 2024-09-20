<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
    <AdminLayout title="Dashboard">
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
                    <inertia-link class="border-b border-blue-600 text-blue-500" :href="route('admin.missions.index')">Missions</inertia-link class="border-b border-blue-600 text-blue-500">
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
