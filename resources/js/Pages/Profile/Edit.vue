<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";
import { useI18n } from "@/composables/useI18n";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const { t } = useI18n();
</script>

<template>
    <Head :title="t('profile.title')" />

    <AuthenticatedLayout>
        <PageHeader
            :title="t('profile.title')"
            :subtitle="t('profile.subtitle')"
        />

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
            />
            <UpdatePasswordForm />
            <div class="lg:col-span-2">
                <DeleteUserForm />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
