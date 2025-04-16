<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { GlobalOutlined, LogoutOutlined } from '@ant-design/icons-vue';
// import { loadLanguageAsync } from "laravel-vue-i18n";
// import { usePage } from "@inertiajs/vue3";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

// const page = usePage();
//loadLanguageAsync(page.props.value.lang);
// loadLanguageAsync('zh');

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<style>
.banner{
    background: rgb(153,247,247);
    background: linear-gradient(90deg, rgba(153,247,247,1) 0%, rgba(223,250,255,1) 25%, rgba(208,235,255,1) 60%, rgba(189,251,255,1) 100%);
}
.background{
    background: rgb(212,254,255);
background: linear-gradient(135deg, rgba(212,254,255,0.5746673669467788) 0%, rgba(225,239,240,0.4766281512605042) 25%, rgba(235,245,246,0.40940126050420167) 60%, rgba(191,246,211,0.4682247899159664) 100%);
}
</style>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen background">
            <nav class="bg-white border-b-2 border-blue-800 border-gray-100 ">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
                    <div class="flex justify-between h-16 ">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-9 w-auto " />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    {{ $t('dashboard') }}
                                </NavLink>
                                <NavLink :href="route('reading')" :active="route().current('reading')">
                                    {{ $t('reading') }}
                                </NavLink>
                                <NavLink v-role="['admin']" :href="route('admin.missions.index')" :active="route().current('admin.missions.index')">
                                    {{ $t('mission') }}
                                </NavLink>
                                <NavLink v-role="['admin']" :href="route('admin.readings.index')" :active="route().current('admin.readings.index')">
                                    阅读编辑
                                </NavLink>
                                <!-- <NavLink v-role="['admin']" :href="route('admin.templateStages.index')" :active="route().current('admin.templateStages.index')">
                                    Stage Template
                                </NavLink> -->
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative" v-role="['admin']">
                                <inertia-link class="border-b border-blue-600 text-blue-500" :href="route('admin.dashboard')">{{ $t('admin') }}</inertia-link>
                            </div>

                            <div class="ms-3 relative">
                                <form method="POST" @submit.prevent="logout">
                                    <a-button class="text-blue-600  hover:text-blue-700 text-base" type="submit" html-type="submit">
                                        {{ $t('logout') }} <LogoutOutlined /> 
                                    </a-button>
                                </form>
                            </div>
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                <GlobalOutlined />
                                                <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink as="a" href="/language/zh">
                                            {{ $t("chinese") }}
                                        </DropdownLink>
                                        <DropdownLink as="a" href="/language/en">
                                            {{ $t("english") }}
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            {{ $t('dashboard') }}
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    {{ $t('logout') }} 
                                </ResponsiveNavLink>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow banner shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 ">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
