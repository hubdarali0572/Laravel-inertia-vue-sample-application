<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHeader from "@/Components/PageHeader.vue";
import RoleForm from "@/Components/Roles/RoleForm.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { usePermissions } from "@/composables/usePermissions";
import { useI18n } from "@/composables/useI18n";
import PaginationFooter from "@/Components/PaginationFooter.vue";

const props = defineProps({
    roles: Object,
    permissionGroups: {
        type: Object,
        default: () => ({}),
    },
    editingRole: {
        type: Object,
        default: null,
    },
});

const { can } = usePermissions();
const { t } = useI18n();
const canCreate = computed(() => can("create role"));
const canEdit = computed(() => can("edit role"));
const canDelete = computed(() => can("delete role"));
const showActions = computed(() => canEdit.value || canDelete.value);
const showFormPanel = computed(() => canCreate.value || canEdit.value);

const activeRole = ref(props.editingRole);
const isModalOpen = ref(false);
const deleteTarget = ref(null);

watch(
    () => props.editingRole,
    (role) => {
        activeRole.value = role;
    },
);

const startEdit = (role) => {
    activeRole.value = {
        id: role.id,
        name: role.name,
        permission_ids: [...(role.permission_ids ?? [])],
    };
};

const cancelEdit = () => {
    activeRole.value = null;
};

const openDeleteModal = (role) => {
    deleteTarget.value = role;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => {
        deleteTarget.value = null;
    }, 300);
};

const confirmDelete = () => {
    if (deleteTarget.value) {
        router.delete(route("roles.destroy", deleteTarget.value.id), {
            onSuccess: () => {
                if (activeRole.value?.id === deleteTarget.value?.id) {
                    cancelEdit();
                }
                closeModal();
            },
            onFinish: () => closeModal(),
        });
    }
};
</script>

<template>
    <Head :title="t('roles.title')" />

    <AuthenticatedLayout>

        <FlashMessage />

        <div class="theme-split-layout">
            <aside v-if="showFormPanel" class="theme-split-form-panel">
                <RoleForm
                    :permission-groups="permissionGroups"
                    :role="activeRole"
                    @cancel="cancelEdit"
                />
            </aside>

            <section
                class="theme-split-list-panel"
                :class="{ 'theme-split-list-panel-full': !showFormPanel }"
            >
                <div class="theme-table-card">
                    <div class="theme-form-section-header">
                        <h3 class="theme-form-section-title">
                            {{ t("roles.list_title") }}
                        </h3>
                    </div>

                    <div class="theme-table-wrap">
                        <table class="theme-table">
                            <thead>
                                <tr class="theme-table-header">
                                    <th class="theme-table-header-cell">
                                        {{ t("roles.name") }}
                                    </th>
                                    <th
                                        v-if="showActions"
                                        class="theme-table-header-cell w-px text-end"
                                    >
                                        {{ t("common.actions") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="theme-table-divide">
                                <tr
                                    v-for="role in roles.data"
                                    :key="role.id"
                                    class="theme-table-row group"
                                    :class="{
                                        'theme-table-row-active':
                                            activeRole?.id === role.id,
                                    }"
                                >
                                    <td class="theme-table-cell">
                                        <span
                                            class="theme-heading text-sm font-bold capitalize tracking-tight"
                                            >{{ role.name }}</span
                                        >
                                    </td>
                                    <td
                                        v-if="showActions"
                                        class="theme-table-cell text-end"
                                    >
                                        <div class="theme-table-actions">
                                            <button
                                                v-if="canEdit"
                                                type="button"
                                                class="theme-table-action-btn theme-table-action-edit"
                                                :class="{
                                                    'is-active':
                                                        activeRole?.id ===
                                                        role.id,
                                                }"
                                                :title="t('roles.edit')"
                                                :aria-label="t('roles.edit')"
                                                @click="startEdit(role)"
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
                                            </button>
                                            <button
                                                v-if="canDelete"
                                                type="button"
                                                class="theme-table-action-btn theme-table-action-delete"
                                                :title="t('roles.delete')"
                                                :aria-label="t('roles.delete')"
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
                                        class="theme-table-empty"
                                    >
                                        {{ t("roles.empty") }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <PaginationFooter :paginator="roles" noun="roles" />
                </div>
            </section>
        </div>

        <ConfirmModal
            :show="isModalOpen"
            :title="t('roles.delete_title')"
            :message="t('roles.delete_message')"
            :confirm-label="t('roles.delete_confirm')"
            :cancel-label="t('roles.delete_cancel')"
            :badge="deleteTarget?.name"
            :badge-initial="deleteTarget?.name?.slice(0, 1)"
            @close="closeModal"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
