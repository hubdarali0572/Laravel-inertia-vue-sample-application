<script setup>
import { computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import PermissionGroupCards from "@/Components/PermissionGroupCards.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { useI18n } from "@/composables/useI18n";

const props = defineProps({
    permissionGroups: Object,
    role: Object, // Received when editing
    rolePermissions: Array, // IDs received when editing
});

const { t } = useI18n();
const isEditing = computed(() => !!props.role);

const form = useForm({
    // Populate form if editing, otherwise empty
    name: props.role?.name ?? "",
    permissions: props.rolePermissions ?? [],
});

const submit = () => {
    if (isEditing.value) {
        form.put(route("roles.update", props.role.id));
    } else {
        form.post(route("roles.store"));
    }
};
</script>

<template>
    <Head :title="t('roles.edit_title')" />

    <AuthenticatedLayout>
        <!-- Header -->
        <div class="theme-page-header">
            <div>
                <h2 class="theme-page-title">
                </h2>
            </div>
            <Link 
                :href="route('roles.index')" 
                class="theme-form-back-link"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                {{ t("roles.back") }}
            </Link>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            
            <!-- Role Identity Card -->
            <div class="theme-form-card">
                <div class="theme-form-body max-w-md">
                    <InputLabel for="name" :value="t('roles.name')" class="theme-form-label" />
                    <TextInput 
                        id="name" v-model="form.name" type="text" required 
                        class="theme-form-input"
                        :placeholder="t('roles.placeholder_name')"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
            </div>

            <PermissionGroupCards
                v-model="form.permissions"
                :groups="permissionGroups"
            />
            <InputError :message="form.errors.permissions" class="mt-2" />

            <!-- Submit Action -->
            <div class="theme-form-actions">
                <Link :href="route('roles.index')" class="theme-btn-secondary">
                    {{ t("common.cancel") }}
                </Link>
                <PrimaryButton :disabled="form.processing">
                    {{ t("roles.update_button") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
