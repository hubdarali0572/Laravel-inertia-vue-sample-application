<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { useI18n } from "@/composables/useI18n";

const { t } = useI18n();
const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const updatePassword = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="theme-form-card overflow-hidden">
        <div class="theme-form-section-header">
            <h2 class="theme-form-section-title">{{ t("profile.password") }}</h2>
            <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">
                {{ t("profile.password_hint") }}
            </p>
        </div>

        <form class="space-y-3 p-4" @submit.prevent="updatePassword">
            <div>
                <InputLabel
                    for="current_password"
                    :value="t('profile.current_password')"
                    class="theme-form-label mb-1"
                />
                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="theme-form-input"
                    autocomplete="current-password"
                />
                <InputError
                    :message="form.errors.current_password"
                    class="mt-1"
                />
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div>
                    <InputLabel
                        for="password"
                        :value="t('profile.new_password')"
                        class="theme-form-label mb-1"
                    />
                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="theme-form-input"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password" class="mt-1" />
                </div>

                <div>
                    <InputLabel
                        for="password_confirmation"
                        :value="t('profile.confirm_password')"
                        class="theme-form-label mb-1"
                    />
                    <TextInput
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="theme-form-input"
                        autocomplete="new-password"
                    />
                    <InputError
                        :message="form.errors.password_confirmation"
                        class="mt-1"
                    />
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <Transition
                    enter-active-class="transition ease-in-out duration-200"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out duration-200"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="theme-link text-[11px]"
                    >
                        {{ t("common.updated") }}
                    </p>
                </Transition>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="theme-btn-primary disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{ t("common.update") }}
                </button>
            </div>
        </form>
    </section>
</template>
