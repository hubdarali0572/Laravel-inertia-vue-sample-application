<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { ref, watch } from 'vue';

const props = defineProps({
    activities: Object,
});

// Flash Message Logic
const page = usePage();
const showFlash = ref(false);
watch(() => page.props.flash?.success, (val) => { if (val) showFlash.value = true; }, { immediate: true });

// --- Modal State Logic ---
const isDeleteModalOpen = ref(false);
const isClearModalOpen = ref(false);
const selectedLogId = ref(null);

const openDeleteModal = (id) => {
    selectedLogId.value = id;
    isDeleteModalOpen.value = true;
};

const closeModals = () => {
    isDeleteModalOpen.value = false;
    isClearModalOpen.value = false;
    setTimeout(() => { selectedLogId.value = null; }, 300);
};

const confirmDelete = () => {
    router.delete(route('activity.destroy', selectedLogId.value), {
        onSuccess: () => closeModals(),
        onFinish: () => closeModals(),
    });
};

const confirmClearAll = () => {
    router.delete(route('activity.clear'), {
        onSuccess: () => closeModals(),
        onFinish: () => closeModals(),
    });
};
// --- End Modal Logic ---

const formatDate = (date) => {
    return new Date(date).toLocaleString("en-US", {
        month: "short", day: "numeric", year: "numeric", hour: "2-digit", minute: "2-digit",
    });
};

const getModelName = (type) => {
    return type ? type.split("\\").pop() : "System";
};

const getFieldData = (log, fieldName) => {
    const newValue = log.properties?.attributes?.[fieldName];
    const oldValue = log.properties?.old?.[fieldName];
    return {
        hasChange: newValue !== undefined || oldValue !== undefined,
        new: newValue,
        old: oldValue,
    };
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Activity Logs" />

        <!-- Page Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-700 tracking-tight">System Activity Logs</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Detailed tracking of all system changes.</p>
            </div>
            
           <button 
                @click="isClearModalOpen = true" 
                class="inline-flex items-center justify-center px-6 py-3 bg-slate-400 text-white text-xs font-bold rounded-xl border border-slate-100 hover:bg-slate-600 transition-all shadow-lg shadow-slate-400/20"
            >
                <!-- Trash Icon SVG -->
                <svg 
                    class="w-4 h-4 mr-2" 
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path 
                        stroke-linecap="round" 
                        stroke-linejoin="round" 
                        stroke-width="2.5" 
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                    />
                </svg>
                Clear All System Logs
            </button>
        </div>

        <!-- Flash Message -->
        <div v-if="showFlash && $page.props.flash.success" class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 text-sm font-bold rounded-r-xl shadow-sm flex justify-between items-center animate-pulse">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                {{ $page.props.flash.success }}
            </div>
            <button @click="showFlash = false" class="text-emerald-400 hover:text-emerald-900 text-xl">×</button>
        </div>

        <div class="bg-white rounded-md shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left table-fixed min-w-[1100px]">
                    <thead>
                        <tr class="bg-slate-400">
                            <th class="w-[15%] px-4 text-[11px] font-black text-white uppercase tracking-widest">User</th>
                            <th class="w-[8%] px-4 text-[11px] font-black text-white uppercase tracking-widest text-center">Action</th>
                            <th class="w-[10%] px-4  text-[11px] font-black text-white uppercase tracking-widest">Resource</th>
                            <th class="w-[15%] px-4  text-[11px] font-black text-white uppercase tracking-widest text-center">Name Change</th>
                            <th class="w-[15%] px-4 text-[11px] font-black text-white uppercase tracking-widest text-center">Email Change</th>
                            <th class="w-[15%] px-4 text-[11px] font-black text-white uppercase tracking-widest text-right">Timestamp</th>
                            <th class="w-[7%] px-4 text-[11px] font-black text-white uppercase tracking-widest text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="log in activities.data" :key="log.id" class="hover:bg-slate-50/40 transition-colors">
                            <td class="px-4 text-xs font-bold text-slate-800">
                                {{ log.causer?.name || "System" }}
                            </td>
                            <td class="px-4 text-center">
                                <span :class="{'px-1.5 py-0.5 rounded text-[9px] font-black uppercase': true, 'bg-emerald-100 text-emerald-700': log.description === 'created', 'bg-amber-100 text-amber-700': log.description === 'updated', 'bg-rose-100 text-rose-700': log.description === 'deleted'}">
                                    {{ log.description }}
                                </span>
                            </td>
                            <td class="px-4">
                                <div class="text-[11px] font-black text-slate-700 uppercase">{{ getModelName(log.subject_type) }}</div>
                                <div class="text-[9px] text-slate-400 font-medium">REF: #{{ log.subject_id }}</div>
                            </td>
                            <td class="px-4 text-center">
                                <div v-if="getFieldData(log, 'name').hasChange">
                                    <span class="text-emerald-600 font-bold text-[11px]">{{ getFieldData(log, 'name').new || '-' }}</span>
                                </div>
                                <span v-else class="text-slate-200">-</span>
                            </td>
                            <td class="px-4 text-center">
                                <div v-if="getFieldData(log, 'email').hasChange">
                                    <span class="text-emerald-600 font-bold text-[11px]">{{ getFieldData(log, 'email').new || '-' }}</span>
                                </div>
                                <span v-else class="text-slate-200">-</span>
                            </td>
                            <td class="px-4 text-right text-[11px] font-bold text-slate-700">
                                {{ formatDate(log.created_at) }}
                            </td>
                            <td class="px-4 text-center">
                                <button @click="openDeleteModal(log.id)" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-all">
                                    <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
          <!-- Pagination Footer -->
            <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                <!-- Summary Text -->
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center sm:text-left">
                    Showing <span class="text-slate-900">{{ activities.from || 0 }}</span> to <span class="text-slate-900">{{ activities.to || 0 }}</span> of <span class="text-slate-900">{{ activities.total }}</span> logs
                </div>

                <!-- Pagination Links -->
                <div class="flex flex-wrap justify-center items-center gap-1.5">
                    <template v-for="(link, k) in activities.links" :key="k">
                        <!-- Clickable Link -->
                        <Link 
                            v-if="link.url" 
                            :href="link.url" 
                            v-html="link.label"
                            class="min-w-[30px] h-6 px-2 flex items-center justify-center text-xs font-bold rounded-lg border transition-all duration-200"
                            :class="[link.active ? 'bg-slate-600 border-slate-600 text-white shadow-lg' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50']"
                        />
                        <!-- Disabled State (e.g. 'Previous' on first page) -->
                        <span 
                            v-else 
                            v-html="link.label" 
                            class="min-w-[30px] h-6 px-2 flex items-center justify-center text-xs font-bold text-slate-300 bg-white border border-slate-100 rounded-lg cursor-not-allowed" 
                        />
                    </template>
                </div>
            </div>
        </div>

        <!-- --- PROFESSIONAL MODALS --- -->

        <!-- Modal for Individual Delete & Clear All -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="isDeleteModalOpen || isClearModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-md" @click="closeModals"></div>

                    <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-95 translate-y-4" enter-to-class="opacity-100 scale-100 translate-y-0" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100 scale-100 translate-y-0" leave-to-class="opacity-0 scale-95 translate-y-4">
                        <div class="relative bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden w-full max-w-[400px] transform transition-all">
                            <div class="p-10 text-center">
                                <!-- Warning Icon -->
                                <div class="flex items-center justify-center w-20 h-20 mx-auto bg-rose-50 rounded-full mb-6">
                                    <div class="flex items-center justify-center w-14 h-14 bg-rose-100 rounded-full shadow-inner text-rose-600">
                                        <svg v-if="isDeleteModalOpen" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                </div>
                                
                                <h3 class="text-slate-900 font-black text-xl tracking-tight">Confirm Deletion</h3>
                                <p class="mt-4 text-slate-500 font-medium text-sm leading-relaxed px-2">
                                    {{ isDeleteModalOpen ? 'Are you sure you want to permanently remove this specific activity log entry?' : 'Are you sure you want to permanently delete ALL system activity logs?' }}
                                </p>

                                <div v-if="isDeleteModalOpen" class="mt-4 inline-flex items-center px-4 py-2 bg-slate-50 border border-slate-100 rounded-2xl">
                                    <span class="text-slate-900 font-black text-sm uppercase">Log ID: #{{ selectedLogId }}</span>
                                </div>

                                <p class="mt-6 text-[11px] text-rose-500 font-bold uppercase tracking-widest">This action is irreversible</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex flex-col gap-3 p-10 pt-0 items-center">
                                <button v-if="isDeleteModalOpen" @click="confirmDelete" class="w-full py-4 text-sm font-black text-white bg-rose-600 rounded-2xl hover:bg-rose-700 shadow-xl shadow-rose-200 transition-all active:scale-[0.97]">
                                    Yes, Delete Log
                                </button>
                                <button v-else @click="confirmClearAll" class="w-full py-4 text-sm font-black text-white bg-rose-600 rounded-2xl hover:bg-rose-700 shadow-xl shadow-rose-200 transition-all active:scale-[0.97]">
                                    Yes, Clear Everything
                                </button>
                                <button @click="closeModals" class="w-full py-4 text-sm font-bold text-slate-400 bg-transparent hover:text-slate-600 rounded-2xl transition-all">
                                    No, Keep Log
                                </button>
                            </div>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>

    </AuthenticatedLayout>
</template>