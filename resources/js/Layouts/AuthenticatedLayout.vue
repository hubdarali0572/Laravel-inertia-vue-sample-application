<script setup>
import { ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useDarkMode } from '@/composables/useDarkMode';

const { isDark, toggleDarkMode } = useDarkMode();

const isSidebarOpen = ref(false); 
const isProfileMenuOpen = ref(false);

// Close sidebar automatically when clicking a link on mobile
watch(() => usePage().url, () => {
    isSidebarOpen.value = false;
});

// Dynamic Navigation with "Active" state detection
const navItems = [
    { 
        name: 'Dashboard', 
        icon: 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 
        route: 'dashboard',
        active: route().current('dashboard')
    },
    { category: 'MAIN MODULES' },
    { 
        name: 'User Management',   
        icon: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', 
        route: 'users.index',
        active: route().current('users.*') // Active for index, edit, create
    },
    { 
        name: 'Roles & Permissions',   
        icon: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', 
        route: 'roles.index',
        active: route().current('roles.*') // Active for index, edit, create
    },
    { 
        name: 'Activity Logs',   
        icon: 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 
    route: 'activity.index',
        active: route().current('activity.*')
    },
];
</script>

<template>
    <div class="h-screen flex overflow-hidden bg-slate-50 dark:bg-slate-900 font-sans text-slate-900 dark:text-slate-100 transition-colors">
        
        <!-- MOBILE OVERLAY -->
        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isSidebarOpen" @click="isSidebarOpen = false" class="fixed inset-0 bg-black/60 z-40 lg:hidden"></div>
        </transition>

        <!-- SIDEBAR -->
        <aside 
            class="theme-sidebar fixed inset-y-0 left-0 z-50 w-72 lg:w-64 flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-slate-800 shrink-0 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-600 p-2 rounded-lg shadow-lg shadow-indigo-900/50">
                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-sm lg:text-base tracking-wide text-white">Sample Project</span>
                </div>
                <button @click="isSidebarOpen = false" class="lg:hidden p-2 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

          <!-- Scrollable Nav Links -->
            <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
                <template v-for="item in navItems" :key="item.name">
                    
                    <!-- Category Header: Styled with better contrast and spacing -->
                    <div v-if="item.category" class="theme-sidebar-category px-7 pt-3 pb-3 text-[10px] font-semibold tracking-[0.15em] uppercase">
                        {{ item.category }}
                    </div>

                    <!-- Navigation Link -->
                    <div v-else class="px-3 mb-2"> <!-- Wrapper to handle horizontal spacing -->
                        <Link 
                            :href="route(item.route)"
                            class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 ease-in-out"
                            :class="item.active 
                                ? 'theme-sidebar-nav-active' 
                                : 'theme-sidebar-nav-inactive'"
                        >
                            <!-- Icon: Group hover ensures icon color follows text -->
                            <svg 
                                class="w-5 h-5 mr-3 transition-colors duration-200" 
                                 fill="none" 
                                stroke="currentColor" 
                                viewBox="0 0 24 24" 
                                stroke-width="2"
                            >
                                <path :d="item.icon" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                            <!-- Label -->
                            <span class="text-sm font-semibold tracking-wide">
                                {{ item.name }}
                            </span>
                        </Link>
                    </div>

                </template>
            </nav>

            <!-- Fixed Bottom Profile -->
            <div class="theme-sidebar-footer relative p-4 shrink-0">
                <!-- Dropdown Menu -->
                <transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-2 scale-95"
                    enter-to-class="opacity-100 translate-y-0 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="opacity-100 translate-y-0 scale-100"
                    leave-to-class="opacity-0 translate-y-2 scale-95"
                >
                    <div v-if="isProfileMenuOpen" class="absolute bottom-full left-4 right-4 mb-3 bg-white rounded-2xl shadow-2xl z-[60] overflow-hidden text-gray-800 border border-gray-100">
                        <div class="p-4 bg-gray-50 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Signed in as</p>
                            <p class="text-sm font-bold truncate">{{ $page.props.auth.user.email }}</p>
                        </div>
                        <Link :href="route('logout')" method="post" as="button" class="w-full text-left p-4 text-sm font-bold text-red-600 hover:bg-red-50 transition flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M17 16l4-4m0 0l-4-4m4 4H7" /></svg>
                            Log Out
                        </Link>
                    </div>
                </transition>

                <button @click="isProfileMenuOpen = !isProfileMenuOpen" class="w-full flex items-center space-x-3 p-3 rounded-xl hover:bg-slate-800 transition group">
                    <div class="w-9 h-9 rounded-full bg-indigo-600 flex items-center justify-center text-sm font-bold border-2 border-indigo-500/30 shrink-0">{{ $page.props.auth.user.name.charAt(0) }}</div>
                    <div class="flex-1 text-left overflow-hidden text-white">
                        <p class="text-xs font-semibold truncate leading-none">{{ $page.props.auth.user.name }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">Administrator</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition" :class="{'rotate-180': isProfileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 15l7-7 7 7" /></svg>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- HEADER -->
            <header class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-30 shadow-sm transition-colors">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = true" class="p-2 -ml-2 mr-3 text-gray-500 dark:text-slate-300 lg:hidden">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div>
                        <h1 class="text-sm lg:text-lg font-bold text-slate-800 dark:text-slate-100 leading-none">Dashboard</h1>
                        <p class="hidden sm:block text-[10px] text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-1">Laravel Inertia Vue Admin</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <!-- Light / Dark Mode Toggle -->
                    <button
                        @click="toggleDarkMode"
                        type="button"
                        class="relative inline-flex items-center justify-center w-9 h-9 rounded-full border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-amber-300 hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500/40"
                        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                        :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <!-- Sun (shown in dark mode, click to go light) -->
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                        </svg>
                        <!-- Moon (shown in light mode, click to go dark) -->
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                        </svg>
                    </button>

                    <span class="hidden md:block text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $page.props.auth.user.name }}</span>
                    <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold uppercase shadow-sm">
                        {{ $page.props.auth.user.name.slice(0, 2) }}
                    </div>
                </div>
            </header>

            <!-- SCROLLABLE MAIN & FOOTER -->
            <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-slate-900 p-4 lg:p-8 custom-scrollbar flex flex-col transition-colors">
                <div class="flex-1 max-w-[1600px] mx-auto w-full">
                    <!-- This SLOT is where your specific page content will appear -->
                    <slot />
                </div>

                <!-- FOOTER -->
                <footer class="mt-1 pt-8 pb-3 border-t border-slate-200 dark:border-slate-700 text-center text-slate-500 dark:text-slate-500 text-[10px] lg:text-xs">
                    <div class="uppercase tracking-widest font-bold">
                        Sample Project for Laravel Inertia Vue3 Admin Dashboard Layout
                    </div>
                </footer>
            </main>
        </div>
    </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0, 0, 0, 0.05); border-radius: 10px; }
aside .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.3); }
</style>