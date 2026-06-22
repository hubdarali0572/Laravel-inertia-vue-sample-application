<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// FIXED: Changed 'users' to 'roles' to match controller and template logic
defineProps({ roles: Object });

const page = usePage();
const showFlash = ref(false);
let timer = null;

const startTimer = () => {
    showFlash.value = true;
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        showFlash.value = false;
    }, 5000);
};

watch(
    () => [page.props.flash.success, page.props.flash.danger],
    ([newSuccess, newDanger]) => {
        if (newSuccess || newDanger) {
            startTimer();
        }
    },
    { immediate: true }
);

// Modal Logic
const isModalOpen = ref(false);
const selectedRole = ref(null); // Stores the whole role object

const openDeleteModal = (role) => {
    selectedRole.value = role; // Store the object, not just the name string
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => { selectedRole.value = null; }, 300);
};

const confirmDelete = () => {
    if (selectedRole.value) {
        router.delete(route('roles.destroy', selectedRole.value.id), {
            onSuccess: () => closeModal(),
            onFinish: () => closeModal(),
        });
    }
};
</script>

<template>
    <Head title="Role Management" />

    <AuthenticatedLayout>
        <!-- Page Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-700 tracking-tight">Role Management</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Manage and monitor system access and roles.</p>
            </div>
            
            <Link :href="route('roles.create')"
                class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-500/20 hover:bg-slate-700 transition-all active:scale-95"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5v14m7-7H5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Create New Role
            </Link>
        </div>

        <!-- Flash Messages -->
        <transition name="fade">
            <div v-if="showFlash && ($page.props.flash.success || $page.props.flash.danger)" 
                :class="[$page.props.flash.success ? 'bg-emerald-50 border-emerald-500 text-emerald-800' : 'bg-rose-50 border-rose-500 text-rose-800']"
                class="mb-6 flex items-center p-4 border-l-4 rounded-r-xl shadow-sm"
            >
                <p class="text-sm font-bold">{{ $page.props.flash.success || $page.props.flash.danger }}</p>
                <button @click="showFlash = false" class="ml-auto opacity-50 hover:opacity-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </transition>

        <!-- Professional Table Card -->
        <div class="bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-400">
                            <th class="px-6 py-4 text-[11px] font-black text-white uppercase tracking-widest border-b border-slate-100">Role Name</th>
                            <th class="px-6 py-4 text-[11px] font-black text-white uppercase tracking-widest border-b border-slate-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="role in roles.data" :key="role.id" class="hover:bg-slate-50/40 transition-colors group">
                            <td class="px-6 py-2">
                                <span class="text-sm font-bold text-slate-800 tracking-tight capitalize">{{ role.name }}</span>
                            </td>

                            <td class="px-6 py-2 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    <Link 
                                        :href="route('roles.edit', role.id)" 
                                        class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </Link>
                                    <button 
                                        @click="openDeleteModal(role)" 
                                        class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Empty State -->
                        <tr v-if="roles.data.length === 0">
                            <td colspan="2" class="px-6 py-12 text-center text-slate-400 font-medium italic">No roles available.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
          <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                 <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center sm:text-left">
                    Showing <span class="text-slate-900">{{ roles.from || 0 }}</span>
                    to <span class="text-slate-900">{{ roles.to || 0 }}</span>
                    of <span class="text-slate-900">{{ roles.total }}</span> roles
                </div>

                <div class="flex flex-wrap justify-center items-center gap-1.5">
                    <template v-for="(link, k) in roles.links" :key="k">
                        <Link 
                            v-if="link.url" 
                            :href="link.url" 
                            v-html="link.label"
                            class="min-w-[30px] h-6 px-2 flex items-center justify-center text-xs font-bold rounded-lg border transition-all"
                            :class="[link.active ? 'bg-slate-600 border-slate-600 text-white shadow-lg shadow-slate-200' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400']"
                        />
                        <span v-else v-html="link.label" class="min-w-[34px] h-8 px-2 flex items-center justify-center text-xs font-bold text-slate-300 bg-white border border-slate-100 rounded-lg cursor-not-allowed" />
                    </template>
                </div>
            </div>
        </div>
        <Teleport to="body">
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="closeModal"></div>

            <!-- Modal Content -->
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0 scale-95 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 scale-100 translate-y-0"
                leave-to-class="opacity-0 scale-95 translate-y-4"
            >
                <div v-if="isModalOpen" class="relative bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden w-full max-w-[400px] transform transition-all">
                    <div class="p-10 text-center">
                        <!-- Icon Circle -->
                        <div class="flex items-center justify-center w-20 h-20 mx-auto bg-rose-50 rounded-full mb-6">
                            <div class="flex items-center justify-center w-14 h-14 bg-rose-100 rounded-full">
                                <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">Delete System Role?</h3>
                        
                        <p class="mt-4 text-slate-500 font-medium text-sm leading-relaxed px-2">
                            Are you sure you want to permanently remove this role from the system? :
                              <div class="mt-6 inline-flex items-center px-5 py-2.5 bg-slate-50 border border-slate-100 rounded-2xl">
                            <div class="w-6 h-6 rounded-full bg-slate-800 text-[10px] text-white flex items-center justify-center font-bold mr-2 uppercase">
                                {{ selectedRole?.name.slice(0, 1) }}
                            </div>
                            <span class="text-slate-900 font-black text-sm tracking-tight">
                                {{ selectedRole?.name }}
                            </span>
                        </div>
                        </p>
                        <p class="mt-6 text-[11px] text-rose-500 font-bold uppercase tracking-[0.2em]">This action is irreversible</p>
                    </div>

                    <!-- Centered Actions -->
                    <div class="flex flex-col gap-3 p-10 pt-0 items-center justify-center">
                        <button 
                            @click="confirmDelete" 
                            class="w-full py-4 text-sm font-black text-white bg-rose-600 rounded-2xl hover:bg-rose-700 shadow-xl shadow-rose-200 transition-all active:scale-[0.97]"
                        >
                            Yes, Confirm Deletion
                        </button>
                        <button 
                            @click="closeModal" 
                            class="w-full py-4 text-sm font-bold text-slate-400 bg-transparent hover:text-slate-600 transition-all"
                        >
                            No, keep this role
                        </button>
                    </div>
                </div>
            </Transition>
        </div>
    </Transition>
</Teleport>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>