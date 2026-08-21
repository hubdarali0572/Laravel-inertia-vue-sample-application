<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
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
const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const initials = computed(() =>
    user.name
        .split(" ")
        .map((part) => part[0])
        .join("")
        .slice(0, 2)
        .toUpperCase(),
);

const isVerified = computed(() => !!user.email_verified_at);
</script>

<template>
    <section class="theme-form-card overflow-hidden">
        <div class="theme-form-section-header">
            <h2 class="theme-form-section-title">{{ t("profile.info") }}</h2>
            <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">
                {{ t("profile.info_hint") }}
            </p>
        </div>

        <div class="space-y-3 p-4">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[var(--color-primary)] text-[11px] font-bold text-[var(--color-on-primary)]"
                >
                    {{ initials }}
                </div>
                <div class="min-w-0 flex-1">
                    <p
                        class="truncate text-sm font-semibold leading-tight text-slate-800 dark:text-slate-100"
                    >
                        {{ user.name }}
                    </p>
                    <p
                        class="truncate text-[11px] leading-tight text-slate-500 dark:text-slate-400"
                    >
                        {{ user.email }}
                    </p>
                </div>
                <span
                    v-if="isVerified"
                    class="theme-accent-text shrink-0 text-[10px] font-semibold uppercase tracking-wide"
                >
                    {{ t("common.verified") }}
                </span>
            </div>

            <form
                class="space-y-3"
                @submit.prevent="form.patch(route('profile.update'))"
            >
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <div>
                        <InputLabel
                            for="name"
                            :value="t('profile.full_name')"
                            class="theme-form-label mb-1"
                        />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="theme-form-input"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel
                            for="email"
                            :value="t('profile.email')"
                            class="theme-form-label mb-1"
                        />
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="theme-form-input"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>
                </div>

                <div
                    v-if="mustVerifyEmail && user.email_verified_at === null"
                    class="theme-alert text-[11px]"
                >
                    <p class="font-medium">
                        {{ t("profile.unverified") }}
                    </p>
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="theme-link mt-1"
                    >
                        {{ t("profile.resend") }}
                    </Link>
                    <p
                        v-show="status === 'verification-link-sent'"
                        class="theme-text mt-1"
                    >
                        {{ t("profile.link_sent") }}
                    </p>
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
                            {{ t("common.saved") }}
                        </p>
                    </Transition>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="theme-btn-primary disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ t("common.save") }}
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>
