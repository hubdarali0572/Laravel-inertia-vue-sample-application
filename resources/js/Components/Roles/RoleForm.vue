<script setup>
import { computed, watch } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import PermissionGroupCards from "@/Components/PermissionGroupCards.vue";
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "@/composables/useI18n";

const props = defineProps({
    permissionGroups: {
        type: Object,
        default: () => ({}),
    },
    role: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["cancel"]);

const { t } = useI18n();

const form = useForm({
    name: "",
    permissions: [],
});

const isEditing = computed(() => !!props.role);

const fillForm = (role) => {
    form.clearErrors();
    form.name = role?.name ?? "";
    form.permissions = [...(role?.permission_ids ?? [])];
};

const resetForm = () => {
    form.clearErrors();
    form.name = "";
    form.permissions = [];
};

watch(
    () => props.role,
    (role) => {
        if (role) {
            fillForm(role);
            return;
        }

        resetForm();
    },
    { immediate: true },
);

const cancelEdit = () => {
    resetForm();
    emit("cancel");
};

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            if (isEditing.value) {
                emit("cancel");
            } else {
                resetForm();
            }
        },
    };

    if (isEditing.value) {
        form.put(route("roles.update", props.role.id), options);
        return;
    }

    form.post(route("roles.store"), options);
};
</script>

<template>
    <div class="theme-form-card theme-split-form-card">

        <form class="theme-form-body flex flex-col gap-4" @submit.prevent="submit">
            <div class="flex flex-col">
                <InputLabel
                    for="role-form-name"
                    :value="t('roles.name')"
                    class="theme-form-label"
                />
                <TextInput
                    id="role-form-name"
                    v-model="form.name"
                    type="text"
                    required
                    class="theme-form-input"
                    :placeholder="t('roles.placeholder_name')"
                />
                <InputError :message="form.errors.name" class="mt-1.5" />
            </div>

            <div class="theme-split-form-scroll">
                <PermissionGroupCards
                    v-model="form.permissions"
                    :groups="permissionGroups"
                    embedded
                />
                <InputError :message="form.errors.permissions" class="mt-2 px-1" />
            </div>

            <div
                class="flex flex-wrap justify-end gap-2 border-t border-slate-200 pt-4 dark:border-slate-700"
            >
                <button
                    v-if="isEditing"
                    type="button"
                    class="theme-btn-secondary"
                    @click="cancelEdit"
                >
                    {{ t("common.cancel") }}
                </button>
                <PrimaryButton :disabled="form.processing" class="w-full sm:w-auto">
                    {{
                        isEditing
                            ? t("roles.update_button")
                            : t("roles.create_button")
                    }}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
