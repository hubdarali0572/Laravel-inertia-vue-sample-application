<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";
import { useI18n } from "@/composables/useI18n";

const { t } = useI18n();
const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="theme-form-card overflow-hidden">
        <div class="theme-form-section-header">
            <h2 class="theme-form-section-title">{{ t("profile.delete_title") }}</h2>
            <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">
                {{ t("profile.delete_hint") }}
            </p>
        </div>

        <div class="flex items-center justify-between gap-3 p-4">
            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                {{ t("profile.delete_help") }}
            </p>
            <button
                type="button"
                class="theme-btn-danger shrink-0"
                @click="confirmUserDeletion"
            >
                {{ t("profile.delete_button") }}
            </button>
        </div>

        <Modal :show="confirmingUserDeletion" max-width="sm" @close="closeModal">
            <div class="theme-modal-header">
                <h3 class="theme-modal-title">{{ t("profile.delete_modal_title") }}</h3>
                <button
                    type="button"
                    class="theme-modal-close"
                    :aria-label="t('common.close')"
                    @click="closeModal"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>

            <div class="theme-modal-body">
                <div class="theme-modal-icon theme-modal-icon-danger">
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                        />
                    </svg>
                </div>

                <p class="theme-modal-message">
                    {{ t("profile.delete_modal_message") }}
                </p>

                <p class="theme-modal-warning text-center">
                    {{ t("common.irreversible") }}
                </p>

                <div class="mt-3">
                    <InputLabel
                        for="password"
                        :value="t('profile.password')"
                        class="theme-form-label"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="theme-form-input mt-2 block w-full"
                        :placeholder="t('profile.placeholder_password')"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>
            </div>

            <div class="theme-modal-footer">
                <button
                    type="button"
                    class="theme-modal-btn-cancel"
                    @click="closeModal"
                >
                    {{ t("common.cancel") }}
                </button>

                <button
                    type="button"
                    :disabled="form.processing"
                    class="theme-modal-btn-danger disabled:cursor-not-allowed disabled:opacity-50"
                    @click="deleteUser"
                >
                    {{
                        form.processing
                            ? t("profile.deleting")
                            : t("profile.delete_modal_title")
                    }}
                </button>
            </div>
        </Modal>
    </section>
</template>
