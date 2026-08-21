<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

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
            <h2 class="theme-form-section-title">Profile information</h2>
            <p class="mt-0.5 text-[11px] text-slate-500 dark:text-slate-400">
                Name and email used on this account.
            </p>
        </div>

        <div class="space-y-3 p-4">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-[11px] font-bold text-white"
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
                    class="shrink-0 text-[10px] font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-300"
                >
                    Verified
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
                            value="Full name"
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
                            value="Email"
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
                    class="rounded border border-indigo-200 bg-indigo-50 px-3 py-2 text-[11px] dark:border-indigo-500/30 dark:bg-indigo-500/10"
                >
                    <p class="font-medium text-indigo-800 dark:text-indigo-200">
                        Email is not verified.
                    </p>
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="mt-1 font-semibold text-indigo-600 dark:text-indigo-300"
                    >
                        Resend link
                    </Link>
                    <p
                        v-show="status === 'verification-link-sent'"
                        class="mt-1 text-indigo-700 dark:text-indigo-200"
                    >
                        Verification link sent.
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
                            class="text-[11px] font-medium text-indigo-600 dark:text-indigo-300"
                        >
                            Saved
                        </p>
                    </Transition>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="theme-btn-primary disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>
