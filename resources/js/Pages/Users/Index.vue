<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

// users will now be an Object containing 'data' and 'links' if paginated
defineProps({ users: Object });

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
const selectedUser = ref(null); // Store the whole user object here

const openDeleteModal = (user) => {
    selectedUser.value = user; // Pass the whole user object
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    // We clear the user after the modal closes so the animation stays smooth
    setTimeout(() => { selectedUser.value = null; }, 300);
};

const confirmDelete = () => {
    if (selectedUser.value) {
        router.delete(route('users.destroy', selectedUser.value.id), {
            onSuccess: () => closeModal(),
            onFinish: () => closeModal(),
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="User Management" />

        <!-- Page Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-700 tracking-tight">User Management</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Manage and monitor system access and roles.</p>
            </div>
            
            <Link :href="route('users.create')"
                class="inline-flex items-center justify-center px-6 py-3 bg-slate-500 text-white text-sm font-bold rounded-lg shadow-lg shadow-slate-500/20 hover:bg-slate-600"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M12 5v14m7-7H5" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Create New User
            </Link>
        </div>

        <!-- Flash Messages -->
        <transition name="fade">
            <div v-if="showFlash && ($page.props.flash.success || $page.props.flash.danger)" 
                :class="[$page.props.flash.success ? 'bg-emerald-50 border-emerald-500 text-emerald-800' : 'bg-rose-50 border-rose-500 text-rose-800']"
                class="mb-6 flex items-center p-4 border-l-4 rounded-r-xl shadow-sm"
            >
                <div class="flex-shrink-0">
                    <svg v-if="$page.props.flash.success" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                    <svg v-else class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/></svg>
                </div>
                <p class="ml-3 text-sm font-bold">{{ $page.props.flash.success || $page.props.flash.danger }}</p>
                <button @click="showFlash = false" class="ml-auto opacity-50 hover:opacity-100 transition-opacity">
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
                            <th class="px-6 py-4 text-[11px] font-black text-white uppercase tracking-widest border-b border-slate-100">User Info</th>
                            <th class="px-6 py-4 text-[11px] font-black text-white uppercase tracking-widest border-b border-slate-100">User Role</th>
                            <th class="px-6 py-4 text-[11px] font-black text-white uppercase tracking-widest border-b border-slate-100 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="user in users.data" :key="user.id" class="hover:bg-slate-50/40 transition-colors group">
                            <td class="px-6 py-2">
                                <div class="flex items-center space-x-4">
                                  <div class="h-10 w-10 shrink-0 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                        <!-- Show profile_image if it exists -->
                                        <img v-if="user.profile_image" 
                                            :src="user.profile_image" 
                                            class="h-full w-full object-cover" 
                                        />
                                        
                                        <!-- Show default icon if no image -->
                                        <svg v-else class="h-6 w-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-slate-800 tracking-tight">{{ user.name }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ user.email }}</div>
                                    </div>
                                </div>
                            </td>

                             <td class="px-6 py-2">
                             <div class="text-sm font-bold text-slate-800 tracking-tight">{{ user.role.name }}</div>
                            </td>

                            <td class="px-6 py-2 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center space-x-2">
                                    <Link 
                                        :href="route('users.edit', user.id)" 
                                        class="p-2.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all duration-200"
                                        title="Edit User"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </Link>
                                    
                                  <button 
                                        @click="openDeleteModal(user)" 
                                        class="p-2.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all duration-200"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="users.data.length === 0">
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium">No users found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center sm:text-left">
                    Showing <span class="text-slate-900">{{ users.from || 0 }}</span> to <span class="text-slate-900">{{ users.to || 0 }}</span> of <span class="text-slate-900">{{ users.total }}</span> entries
                </div>

                <div class="flex flex-wrap justify-center items-center gap-1.5">
                    <template v-for="(link, k) in users.links" :key="k">
                        <Link 
                            v-if="link.url" 
                            :href="link.url" 
                            v-html="link.label"
                            class="min-w-[30px] h-6 px-2 flex items-center justify-center text-xs font-bold rounded-lg border transition-all duration-200"
                            :class="[link.active ? 'bg-slate-600 border-slate-600 text-white shadow-lg' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50']"
                        />
                        <span v-else v-html="link.label" class="min-w-[30px] h-6 px-2 flex items-center justify-center text-xs font-bold text-slate-300 bg-white border border-slate-100 rounded-lg cursor-not-allowed" />
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
                    <!-- Professional Backdrop -->
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
                            <div class="p-10">
                                <!-- Icon Circle -->
                                <div class="flex items-center justify-center w-20 h-20 mx-auto bg-rose-50 rounded-full mb-6">
                                    <div class="flex items-center justify-center w-14 h-14 bg-rose-100 rounded-full shadow-inner">
                                        <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <p class="mt-4 text-slate-500 font-medium text-sm leading-relaxed px-2">
                                        Are you sure you want to permanently remove the account for:
                                    
                                    <div class="mt-4 inline-flex items-center px-4 py-2 bg-slate-50 border border-slate-100 rounded-2xl">
                                        <div class="w-6 h-6 rounded-full bg-slate-800 text-[10px] text-white flex items-center justify-center font-bold mr-2">
                                            {{ selectedUser?.name.slice(0, 1) }}
                                        </div>
                                        <span class="text-slate-900 font-black text-sm">{{ selectedUser?.name }}</span>
                                    </div>
                                    </p>

                                

                                    <p class="mt-4 text-[13px] text-rose-500 font-bold uppercase tracking-widest">This action is irreversible</p>
                                </div>
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
                                    class="w-full py-4 text-sm font-bold text-slate-400 bg-transparent hover:text-slate-600 rounded-2xl transition-all"
                                >
                                    No, keep this account
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
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>