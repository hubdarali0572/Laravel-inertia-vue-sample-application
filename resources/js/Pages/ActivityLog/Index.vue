<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from 'vue';

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

const isModalOpen = computed(() => isDeleteModalOpen.value || isClearModalOpen.value);

const modalTitle = computed(() =>
    isDeleteModalOpen.value ? 'Delete Activity Log' : 'Clear All Activity Logs'
);

const modalMessage = computed(() =>
    isDeleteModalOpen.value
        ? 'Are you sure you want to permanently remove this activity log entry?'
        : 'Are you sure you want to permanently delete all system activity logs?'
);

const modalConfirmLabel = computed(() =>
    isDeleteModalOpen.value ? 'Yes, Delete Log' : 'Yes, Clear All'
);

const modalCancelLabel = computed(() =>
    isDeleteModalOpen.value ? 'No, Keep Log' : 'No, Keep Logs'
);

const modalBadge = computed(() =>
    isDeleteModalOpen.value && selectedLogId.value ? `Log ID: #${selectedLogId.value}` : null
);

const modalIcon = computed(() => (isClearModalOpen.value ? 'warning' : 'delete'));

const handleModalConfirm = () => {
    if (isDeleteModalOpen.value) {
        confirmDelete();
    } else {
        confirmClearAll();
    }
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
                <h2 class="text-2xl font-black text-slate-700 dark:text-slate-100 tracking-tight">System Activity Logs</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 font-medium">Detailed tracking of all system changes.</p>
            </div>
            
           <button 
                @click="isClearModalOpen = true" 
                class="theme-btn-secondary text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-500/30 hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:border-rose-300"
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
        <div v-if="showFlash && $page.props.flash.success" class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border-l-4 border-emerald-500 text-emerald-800 dark:text-emerald-300 text-sm font-bold rounded-r-xl shadow-sm flex justify-between items-center animate-pulse">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                {{ $page.props.flash.success }}
            </div>
            <button @click="showFlash = false" class="text-emerald-400 hover:text-emerald-900 text-xl">×</button>
        </div>

        <div class="theme-table-card">
            <div class="overflow-x-auto">
                <table class="w-full text-left table-fixed min-w-[1100px]">
                    <thead>
                        <tr class="theme-table-header">
                            <th class="theme-table-header-cell w-[15%]">User</th>
                            <th class="theme-table-header-cell w-[8%] text-center">Action</th>
                            <th class="theme-table-header-cell w-[10%]">Resource</th>
                            <th class="theme-table-header-cell w-[15%] text-center">Name Change</th>
                            <th class="theme-table-header-cell w-[15%] text-center">Email Change</th>
                            <th class="theme-table-header-cell w-[15%] text-right">Timestamp</th>
                            <th class="theme-table-header-cell w-[7%] text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr v-for="log in activities.data" :key="log.id" class="theme-table-row">
                            <td class="px-4 text-xs font-bold text-slate-800 dark:text-slate-100">
                                {{ log.causer?.name || "System" }}
                            </td>
                            <td class="px-4 text-center py-2">
                                <span :class="{'px-1.5 py-0.5 rounded text-[9px] font-black uppercase': true, 'bg-emerald-100 dark:bg-emerald-500/15 text-emerald-700 dark:text-emerald-300': log.description === 'created', 'bg-amber-100 dark:bg-amber-500/15 text-amber-700 dark:text-amber-300': log.description === 'updated', 'bg-rose-100 dark:bg-rose-500/15 text-rose-700 dark:text-rose-300': log.description === 'deleted'}">
                                    {{ log.description }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <div class="text-[11px] font-black text-slate-700 dark:text-slate-200 uppercase">{{ getModelName(log.subject_type) }}</div>
                                <div class="text-[9px] text-slate-400 dark:text-slate-500 font-medium">REF: #{{ log.subject_id }}</div>
                            </td>
                            <td class="px-4 text-center py-2">
                                <div v-if="getFieldData(log, 'name').hasChange">
                                    <span class="text-emerald-600 font-bold text-[11px]">{{ getFieldData(log, 'name').new || '-' }}</span>
                                </div>
                                <span v-else class="text-slate-200">-</span>
                            </td>
                            <td class="px-4 text-center py-2">
                                <div v-if="getFieldData(log, 'email').hasChange">
                                    <span class="text-emerald-600 font-bold text-[11px]">{{ getFieldData(log, 'email').new || '-' }}</span>
                                </div>
                                <span v-else class="text-slate-200">-</span>
                            </td>
                            <td class="px-4 text-right text-[11px] font-bold text-slate-700 dark:text-slate-300 py-2">
                                {{ formatDate(log.created_at) }}
                            </td>
                            <td class="px-4 text-center py-2">
                                <div class="theme-table-actions justify-center">
                                    <button
                                        @click="openDeleteModal(log.id)"
                                        class="theme-table-action-btn theme-table-action-delete"
                                        title="Delete Log"
                                        aria-label="Delete Log"
                                    >
                                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
          <!-- Pagination Footer -->
            <div class="theme-table-footer flex flex-col space-y-4 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between">
                <!-- Summary Text -->
                <div class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest text-center sm:text-left">
                    Showing <span class="text-slate-900 dark:text-slate-200">{{ activities.from || 0 }}</span> to <span class="text-slate-900 dark:text-slate-200">{{ activities.to || 0 }}</span> of <span class="text-slate-900 dark:text-slate-200">{{ activities.total }}</span> logs
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
                            :class="[link.active ? 'theme-pagination-active' : 'theme-pagination-inactive']"
                        />
                        <!-- Disabled State (e.g. 'Previous' on first page) -->
                        <span 
                            v-else 
                            v-html="link.label" 
                            class="min-w-[30px] h-6 px-2 flex items-center justify-center text-xs font-bold text-slate-300 dark:text-slate-600 bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-lg cursor-not-allowed" 
                        />
                    </template>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="isModalOpen"
            :title="modalTitle"
            :message="modalMessage"
            :confirm-label="modalConfirmLabel"
            :cancel-label="modalCancelLabel"
            :badge="modalBadge"
            :icon="modalIcon"
            @close="closeModals"
            @confirm="handleModalConfirm"
        />

    </AuthenticatedLayout>
</template>