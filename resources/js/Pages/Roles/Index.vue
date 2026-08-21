<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { usePermissions } from "@/composables/usePermissions";

defineProps({ roles: Object });

const { can } = usePermissions();
const canCreate = computed(() => can("create role"));
const canEdit = computed(() => can("edit role"));
const canDelete = computed(() => can("delete role"));
const showActions = computed(() => canEdit.value || canDelete.value);

const isModalOpen = ref(false);
const selectedRole = ref(null);

const openDeleteModal = (role) => {
    selectedRole.value = role;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => {
        selectedRole.value = null;
    }, 300);
};

const confirmDelete = () => {
    if (selectedRole.value) {
        router.delete(route("roles.destroy", selectedRole.value.id), {
            onSuccess: () => closeModal(),
            onFinish: () => closeModal(),
        });
    }
};
</script>

<template>
    <Head title="Role Management" />

    <AuthenticatedLayout>
        <PageHeader
            title="Role Management"
            subtitle="Manage and monitor system access and roles."
        >
            <Link
                v-if="canCreate"
                :href="route('roles.create')"
                class="theme-btn-primary"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M12 5v14m7-7H5"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                Add New
            </Link>
        </PageHeader>

        <FlashMessage />

        <div class="theme-table-card">
            <div class="theme-table-wrap">
                <table class="theme-table">
                    <thead>
                        <tr class="theme-table-header">
                            <th class="theme-table-header-cell">Role Name</th>
                            <th
                                v-if="showActions"
                                class="theme-table-header-cell w-px text-right"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr
                            v-for="role in roles.data"
                            :key="role.id"
                            class="theme-table-row group"
                        >
                            <td class="theme-table-cell">
                                <span
                                    class="text-sm font-bold capitalize tracking-tight text-slate-800 dark:text-slate-100"
                                    >{{ role.name }}</span
                                >
                            </td>
                            <td
                                v-if="showActions"
                                class="theme-table-cell text-right"
                            >
                                <div class="theme-table-actions">
                                    <Link
                                        v-if="canEdit"
                                        :href="route('roles.edit', role.id)"
                                        class="theme-table-action-btn theme-table-action-edit"
                                        title="Edit Role"
                                        aria-label="Edit Role"
                                    >
                                        <svg
                                            class="h-3.5 w-3.5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="2"
                                        >
                                            <path
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </Link>
                                    <button
                                        v-if="canDelete"
                                        type="button"
                                        class="theme-table-action-btn theme-table-action-delete"
                                        title="Delete Role"
                                        aria-label="Delete Role"
                                        @click="openDeleteModal(role)"
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
                        <tr v-if="roles.data.length === 0">
                            <td
                                :colspan="showActions ? 2 : 1"
                                class="theme-table-cell py-10 text-center font-medium italic text-slate-400 dark:text-slate-500"
                            >
                                No roles available.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="theme-table-footer">
                <div class="theme-table-footer-meta">
                    Showing
                    <span class="text-slate-900 dark:text-slate-200">{{
                        roles.from || 0
                    }}</span>
                    to
                    <span class="text-slate-900 dark:text-slate-200">{{
                        roles.to || 0
                    }}</span>
                    of
                    <span class="text-slate-900 dark:text-slate-200">{{
                        roles.total
                    }}</span>
                    roles
                </div>
                <div class="flex flex-wrap items-center justify-center gap-1">
                    <template v-for="(link, k) in roles.links" :key="k">
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
            title="Delete System Role"
            message="Are you sure you want to permanently remove this role from the system?"
            confirm-label="Yes, Delete Role"
            cancel-label="No, Keep Role"
            :badge="selectedRole?.name"
            :badge-initial="selectedRole?.name?.slice(0, 1)"
            @close="closeModal"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
