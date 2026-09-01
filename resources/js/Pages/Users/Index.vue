<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHeader from "@/Components/PageHeader.vue";
import UserForm from "@/Components/Users/UserForm.vue";
import { Head, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { usePermissions } from "@/composables/usePermissions";
import { useI18n } from "@/composables/useI18n";
import PaginationFooter from "@/Components/PaginationFooter.vue";

const props = defineProps({
    users: Object,
    roles: {
        type: Array,
        default: () => [],
    },
    editingUser: {
        type: Object,
        default: null,
    },
});

const { can } = usePermissions();
const { t } = useI18n();
const canCreate = computed(() => can("create user"));
const canEdit = computed(() => can("edit user"));
const canDelete = computed(() => can("delete user"));
const showActions = computed(() => canEdit.value || canDelete.value);
const showFormPanel = computed(() => canCreate.value || canEdit.value);

const activeUser = ref(props.editingUser);
const brokenImages = ref({});
const isModalOpen = ref(false);
const deleteTarget = ref(null);

watch(
    () => props.editingUser,
    (user) => {
        activeUser.value = user;
    },
);

const markImageBroken = (userId) => {
    brokenImages.value[userId] = true;
};

const showUserImage = (user) =>
    user.profile_image && !brokenImages.value[user.id];

const startEdit = (user) => {
    activeUser.value = {
        id: user.id,
        name: user.name,
        email: user.email,
        role_id: user.role_id ?? user.role?.id ?? "",
        profile_image: user.profile_image,
    };
};

const cancelEdit = () => {
    activeUser.value = null;
};

const openDeleteModal = (user) => {
    deleteTarget.value = user;
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
        router.delete(route("users.destroy", deleteTarget.value.id), {
            onSuccess: () => {
                if (activeUser.value?.id === deleteTarget.value?.id) {
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
    <AuthenticatedLayout>
        <Head :title="t('users.title')" />

        <FlashMessage />

        <div class="theme-split-layout">
            <aside v-if="showFormPanel" class="theme-split-form-panel">
                <UserForm
                    :roles="roles"
                    :user="activeUser"
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
                            {{ t("users.list_title") }}
                        </h3>
                    </div>

                    <div class="theme-table-wrap">
                        <table class="theme-table">
                            <thead>
                                <tr class="theme-table-header">
                                    <th class="theme-table-header-cell">
                                        {{ t("users.info") }}
                                    </th>
                                    <th class="theme-table-header-cell">
                                        {{ t("users.role") }}
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
                                    v-for="user in users.data"
                                    :key="user.id"
                                    class="theme-table-row group"
                                    :class="{
                                        'theme-table-row-active':
                                            activeUser?.id === user.id,
                                    }"
                                >
                                    <td class="theme-table-cell">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center overflow-hidden rounded-full border border-slate-200 bg-slate-100 dark:border-slate-600 dark:bg-slate-700"
                                            >
                                                <img
                                                    v-if="showUserImage(user)"
                                                    :src="user.profile_image"
                                                    :alt="user.name"
                                                    class="h-full w-full object-cover"
                                                    @error="markImageBroken(user.id)"
                                                />
                                                <svg
                                                    v-if="!showUserImage(user)"
                                                    class="h-5 w-5 text-slate-400"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <div
                                                    class="theme-heading truncate text-sm font-bold tracking-tight"
                                                >
                                                    {{ user.name }}
                                                </div>
                                                <div
                                                    class="theme-text-muted truncate text-xs font-medium"
                                                >
                                                    {{ user.email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="theme-table-cell">
                                        <span class="theme-user-role">{{
                                            user.role?.name
                                        }}</span>
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
                                                        activeUser?.id ===
                                                        user.id,
                                                }"
                                                :title="t('users.edit')"
                                                :aria-label="t('users.edit')"
                                                @click="startEdit(user)"
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
                                                :title="t('users.delete')"
                                                :aria-label="t('users.delete')"
                                                @click="openDeleteModal(user)"
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
                                <tr v-if="users.data.length === 0">
                                    <td
                                        :colspan="showActions ? 3 : 2"
                                        class="theme-table-empty"
                                    >
                                        {{ t("users.empty") }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <PaginationFooter :paginator="users" noun="users" />
                </div>
            </section>
        </div>

        <ConfirmModal
            :show="isModalOpen"
            :title="t('users.delete_title')"
            :message="t('users.delete_message')"
            :confirm-label="t('users.delete_confirm')"
            :cancel-label="t('users.delete_cancel')"
            :badge="deleteTarget?.name"
            :badge-initial="deleteTarget?.name?.slice(0, 1)"
            @close="closeModal"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
