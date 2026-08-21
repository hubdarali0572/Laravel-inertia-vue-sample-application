<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { usePermissions } from "@/composables/usePermissions";
import { useI18n } from "@/composables/useI18n";
import PaginationFooter from "@/Components/PaginationFooter.vue";

defineProps({ users: Object });

const { can } = usePermissions();
const { t } = useI18n();
const canCreate = computed(() => can("create user"));
const canEdit = computed(() => can("edit user"));
const canDelete = computed(() => can("delete user"));
const showActions = computed(() => canEdit.value || canDelete.value);

const brokenImages = ref({});
const isModalOpen = ref(false);
const selectedUser = ref(null);

const markImageBroken = (userId) => {
    brokenImages.value[userId] = true;
};

const showUserImage = (user) =>
    user.profile_image && !brokenImages.value[user.id];

const openDeleteModal = (user) => {
    selectedUser.value = user;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    setTimeout(() => {
        selectedUser.value = null;
    }, 300);
};

const confirmDelete = () => {
    if (selectedUser.value) {
        router.delete(route("users.destroy", selectedUser.value.id), {
            onSuccess: () => closeModal(),
            onFinish: () => closeModal(),
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="t('users.title')" />

        <PageHeader
            :title="t('users.title')"
            :subtitle="t('users.subtitle')"
        >
            <Link
                v-if="canCreate"
                :href="route('users.create')"
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
                {{ t("common.add_new") }}
            </Link>
        </PageHeader>

        <FlashMessage />

        <div class="theme-table-card">
            <div class="theme-table-wrap">
                <table class="theme-table">
                    <thead>
                        <tr class="theme-table-header">
                            <th class="theme-table-header-cell">{{ t("users.info") }}</th>
                            <th class="theme-table-header-cell">{{ t("users.role") }}</th>
                            <th
                                v-if="showActions"
                                class="theme-table-header-cell w-px text-end"
                            >
                                {{ t("common.actions") }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                            class="theme-table-row group"
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
                                            class="truncate text-sm font-bold tracking-tight text-slate-800 dark:text-slate-100"
                                        >
                                            {{ user.name }}
                                        </div>
                                        <div
                                            class="truncate text-xs font-medium text-slate-500 dark:text-slate-400"
                                        >
                                            {{ user.email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="theme-table-cell">
                                <span
                                    class="text-sm font-bold capitalize tracking-tight text-slate-800 dark:text-slate-100"
                                    >{{ user.role?.name }}</span
                                >
                            </td>
                            <td
                                v-if="showActions"
                                class="theme-table-cell text-end"
                            >
                                <div class="theme-table-actions">
                                    <Link
                                        v-if="canEdit"
                                        :href="route('users.edit', user.id)"
                                        class="theme-table-action-btn theme-table-action-edit"
                                        :title="t('users.edit')"
                                        :aria-label="t('users.edit')"
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
                                class="theme-table-cell py-10 text-center font-medium text-slate-400 dark:text-slate-500"
                            >
                                {{ t("users.empty") }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <PaginationFooter :paginator="users" noun="users" />
        </div>

        <ConfirmModal
            :show="isModalOpen"
            :title="t('users.delete_title')"
            :message="t('users.delete_message')"
            :confirm-label="t('users.delete_confirm')"
            :cancel-label="t('users.delete_cancel')"
            :badge="selectedUser?.name"
            :badge-initial="selectedUser?.name?.slice(0, 1)"
            @close="closeModal"
            @confirm="confirmDelete"
        />
    </AuthenticatedLayout>
</template>
