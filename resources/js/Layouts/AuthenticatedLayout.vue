<script setup>
import { ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

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
];
</script>

<template>
    <div class="h-screen flex overflow-hidden bg-gray-50 font-sans text-gray-900">
        
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
            class="fixed inset-y-0 left-0 z-50 w-72 lg:w-64 bg-slate-400 text-white flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
            :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-white/10 shrink-0 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg">
                        <svg class="w-6 h-6 text-[#044e3a]" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                        </svg>
                    </div>
                    <span class="font-bold text-sm lg:text-base tracking-widest uppercase">Sample Project</span>
                </div>
                <button @click="isSidebarOpen = false" class="lg:hidden p-2 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

          <!-- Scrollable Nav Links -->
            <nav class="flex-1 overflow-y-auto py-4 custom-scrollbar">
                <template v-for="item in navItems" :key="item.name">
                    
                    <!-- Category Header: Styled with better contrast and spacing -->
                    <div v-if="item.category" class="px-7 pt-3 pb-3 text-[10px] font-black text-white tracking-[0.2em] uppercase">
                        {{ item.category }}
                    </div>

                    <!-- Navigation Link -->
                    <div v-else class="px-3 mb-2"> <!-- Wrapper to handle horizontal spacing -->
                        <Link 
                            :href="route(item.route)"
                            class="group flex items-center px-4 py-3 rounded-xl transition-all duration-200 ease-in-out"
                            :class="item.active 
                                ? 'bg-slate-500 text-white shadow-sm' 
                                : 'text-slate-100 hover:bg-slate-600/50 hover:text-white'"
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
            <div class="relative p-4 bg-slate-600 border-t border-white/10 shrink-0">
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

                <button @click="isProfileMenuOpen = !isProfileMenuOpen" class="w-full flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition group">
                    <div class="w-9 h-9 rounded-full bg-slate-500 flex items-center justify-center text-sm font-bold border-2 border-white/20 shrink-0">{{ $page.props.auth.user.name.charAt(0) }}</div>
                    <div class="flex-1 text-left overflow-hidden text-white">
                        <p class="text-xs font-bold truncate leading-none">{{ $page.props.auth.user.name }}</p>
                        <p class="text-[10px] text-white mt-1">Administrator</p>
                    </div>
                    <svg class="w-4 h-4 text-white transition" :class="{'rotate-180': isProfileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 15l7-7 7 7" /></svg>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- HEADER -->
            <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-8 shrink-0 z-30 shadow-sm">
                <div class="flex items-center">
                    <button @click="isSidebarOpen = true" class="p-2 -ml-2 mr-3 text-gray-500 lg:hidden">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                    <div>
                        <h1 class="text-sm lg:text-lg font-bold text-gray-800 leading-none">Dashboard</h1>
                        <p class="hidden sm:block text-[10px] text-gray-400 uppercase tracking-widest mt-1">Laravel Inertia Vue Admin</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="hidden md:block text-sm font-bold text-gray-700">{{ $page.props.auth.user.name }}</span>
                    <div class="w-8 h-8 rounded-full bg-slate-600 text-white flex items-center justify-center text-xs font-bold uppercase shadow-inner">
                        {{ $page.props.auth.user.name.slice(0, 2) }}
                    </div>
                </div>
            </header>

            <!-- SCROLLABLE MAIN & FOOTER -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-8 custom-scrollbar flex flex-col">
                <div class="flex-1 max-w-[1600px] mx-auto w-full">
                    <!-- This SLOT is where your specific page content will appear -->
                    <slot />
                </div>

                <!-- FOOTER -->
                <footer class="mt-1 pt-8 pb-3 border-t border-gray-200 text-center text-gray-600 text-[10px] lg:text-xs">
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
aside .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); }
</style>