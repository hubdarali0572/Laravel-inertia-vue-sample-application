<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { usePermissions } from "@/composables/usePermissions";
import { useActivityLog } from "@/composables/useActivityLog";

defineProps({
    activities: Object,
});

const { can } = usePermissions();
const canDelete = computed(() => can("delete activity"));
const { formatDate, getModelName, changeSummary, actionBadgeClass } =
    useActivityLog();

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
    setTimeout(() => {
        selectedLogId.value = null;
    }, 300);
};

const confirmDelete = () => {
    router.delete(route("activity.destroy", selectedLogId.value), {
        onSuccess: () => closeModals(),
        onFinish: () => closeModals(),
    });
};

const confirmClearAll = () => {
    router.delete(route("activity.clear"), {
        onSuccess: () => closeModals(),
        onFinish: () => closeModals(),
    });
};

const isModalOpen = computed(
    () => isDeleteModalOpen.value || isClearModalOpen.value,
);

const modalTitle = computed(() =>
    isDeleteModalOpen.value
        ? "Delete Activity Log"
        : "Clear All Activity Logs",
);

const modalMessage = computed(() =>
    isDeleteModalOpen.value
        ? "Are you sure you want to permanently remove this activity log entry?"
        : "Are you sure you want to permanently delete all system activity logs?",
);

const modalConfirmLabel = computed(() =>
    isDeleteModalOpen.value ? "Yes, Delete Log" : "Yes, Clear All",
);

const modalCancelLabel = computed(() =>
    isDeleteModalOpen.value ? "No, Keep Log" : "No, Keep Logs",
);

const modalBadge = computed(() =>
    isDeleteModalOpen.value && selectedLogId.value
        ? `Log ID: #${selectedLogId.value}`
        : null,
);

const modalIcon = computed(() =>
    isClearModalOpen.value ? "warning" : "delete",
);

const handleModalConfirm = () => {
    if (isDeleteModalOpen.value) {
        confirmDelete();
    } else {
        confirmClearAll();
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Activity Logs" />

        <PageHeader
            title="System Activity Logs"
            subtitle="Detailed tracking of all system changes."
        >
            <button
                v-if="canDelete"
                type="button"
                class="theme-btn-secondary"
                @click="isClearModalOpen = true"
            >
                <svg
                    class="h-4 w-4"
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
        </PageHeader>

        <FlashMessage />

        <div class="theme-table-card">
            <div class="theme-table-wrap">
                <table class="theme-table">
                    <thead>
                        <tr class="theme-table-header">
                            <th class="theme-table-header-cell">User</th>
                            <th class="theme-table-header-cell">Action</th>
                            <th class="theme-table-header-cell">Module</th>
                            <th class="theme-table-header-cell">Summary</th>
                            <th class="theme-table-header-cell">Timestamp</th>
                            <th class="theme-table-header-cell w-px text-right">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr
                            v-for="log in activities.data"
                            :key="log.id"
                            class="theme-table-row"
                        >
                            <td class="theme-table-cell">
                                <div class="text-sm font-bold text-slate-800 dark:text-slate-100">
                                    {{ log.causer?.name || "System" }}
                                </div>
                                <div
                                    v-if="log.causer?.email"
                                    class="text-xs text-slate-500 dark:text-slate-400"
                                >
                                    {{ log.causer.email }}
                                </div>
                            </td>
                            <td class="theme-table-cell">
                                <span
                                    class="inline-flex rounded px-2 py-0.5 text-[10px] font-black uppercase tracking-wide"
                                    :class="actionBadgeClass(log.description)"
                                >
                                    {{ log.description }}
                                </span>
                            </td>
                            <td class="theme-table-cell">
                                <span class="text-sm font-semibold uppercase text-slate-700 dark:text-slate-200">
                                    {{ getModelName(log.subject_type) }}
                                </span>
                                <span class="ml-1 text-xs text-slate-400">
                                    #{{ log.subject_id || "—" }}
                                </span>
                            </td>
                            <td class="theme-table-cell">
                                <span class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ changeSummary(log) }}
                                </span>
                            </td>
                            <td class="theme-table-cell text-xs font-semibold text-slate-700 dark:text-slate-300">
                                {{ formatDate(log.created_at) }}
                            </td>
                            <td class="theme-table-cell text-right">
                                <div class="theme-table-actions">
                                    <Link
                                        :href="route('activity.show', log.id)"
                                        class="theme-table-action-btn theme-table-action-view"
                                        title="View Log"
                                        aria-label="View Log"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="2"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .644C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </Link>
                                    <button
                                        v-if="canDelete"
                                        type="button"
                                        class="theme-table-action-btn theme-table-action-delete"
                                        title="Delete Log"
                                        aria-label="Delete Log"
                                        @click="openDeleteModal(log.id)"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="2"
                                        >
                                            <path
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="activities.data.length === 0">
                            <td
                                colspan="6"
                                class="theme-table-cell py-10 text-center font-medium text-slate-400 dark:text-slate-500"
                            >
                                No activity logs found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="theme-table-footer">
                <div class="theme-table-footer-meta">
                    Showing
                    <span class="text-slate-900 dark:text-slate-200">{{
                        activities.from || 0
                    }}</span>
                    to
                    <span class="text-slate-900 dark:text-slate-200">{{
                        activities.to || 0
                    }}</span>
                    of
                    <span class="text-slate-900 dark:text-slate-200">{{
                        activities.total
                    }}</span>
                    logs
                </div>
                <div class="flex flex-wrap items-center justify-center gap-1">
                    <template v-for="(link, k) in activities.links" :key="k">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            class="theme-pagination-btn"
                            :class="[
                                link.active
                                    ? 'theme-pagination-active'
                                    : 'theme-pagination-inactive',
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="theme-pagination-btn cursor-not-allowed border-slate-100 bg-white text-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-600"
                            v-html="link.label"
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
