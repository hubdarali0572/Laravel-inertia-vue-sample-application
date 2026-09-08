<script setup>
import { ref } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from '@/composables/useI18n';

const { t } = useI18n();

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout auth-simple>
        <Head :title="t('auth.register')" />

        <!-- Icon + Heading -->
        <div class="mb-5 text-center">
            <div class="theme-auth-icon-box-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                </svg>
            </div>
            <h2 class="theme-heading text-lg font-bold tracking-tight sm:text-xl">
                {{ t('auth.register_title') }}
            </h2>
            <p class="theme-text-muted mt-1 text-xs sm:text-sm">
                {{ t('auth.register_subtitle') }}
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-3.5">
            <!-- Full Name -->
            <div>
                <InputLabel for="name" :value="t('auth.full_name')" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1.5"
                    v-model="form.name"
                    required
                    autofocus
                    placeholder="John Doe"
                    autocomplete="name"
                />
                <InputError class="mt-1.5" :message="form.errors.name" />
            </div>

            <!-- Email -->
            <div>
                <InputLabel for="email" :value="t('auth.email')" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1.5"
                    v-model="form.email"
                    required
                    placeholder="name@company.com"
                    autocomplete="username"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <InputLabel for="password" :value="t('auth.password')" />
                <div class="mt-1.5 relative">
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="pr-11"
                        v-model="form.password"
                        required
                        placeholder="••••••••"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        class="theme-text-muted absolute inset-y-0 right-0 flex items-center pr-3 transition-colors hover:text-[var(--color-heading)] focus:outline-none"
                        @click="showPassword = !showPassword"
                        :aria-label="showPassword ? 'Hide password' : 'Show password'"
                    >
                        <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.483 8.613 8.242 4.5 12 4.5c3.758 0 8.517 4.113 9.964 7.178.07.147.07.315 0 .462-1.447 3.065-4.206 7.178-9.964 7.178-3.758 0-8.517-4.113-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <InputLabel for="password_confirmation" :value="t('auth.confirm_password')" />
                <div class="mt-1.5 relative">
                    <TextInput
                        id="password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        class="pr-11"
                        v-model="form.password_confirmation"
                        required
                        placeholder="••••••••"
                        autocomplete="new-password"
                    />
                    <button
                        type="button"
                        class="theme-text-muted absolute inset-y-0 right-0 flex items-center pr-3 transition-colors hover:text-[var(--color-heading)] focus:outline-none"
                        @click="showConfirmPassword = !showConfirmPassword"
                        :aria-label="showConfirmPassword ? 'Hide password' : 'Show password'"
                    >
                        <svg v-if="!showConfirmPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.483 8.613 8.242 4.5 12 4.5c3.758 0 8.517 4.113 9.964 7.178.07.147.07.315 0 .462-1.447 3.065-4.206 7.178-9.964 7.178-3.758 0-8.517-4.113-9.964-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password_confirmation" />
            </div>

            <!-- Submit -->
            <div>
                <PrimaryButton class="w-full py-2.5" :disabled="form.processing">
                    <span v-if="form.processing" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ t('auth.creating_account') }}
                    </span>
                    <span v-else>{{ t('auth.register') }}</span>
                </PrimaryButton>
            </div>
        </form>

        <!-- Footer link -->
        <div class="theme-border mt-4 border-t pt-4 text-center">
            <p class="theme-text-muted text-sm">
                {{ t('auth.have_account') }}
                <Link
                    :href="route('login')"
                    class="theme-link hover:underline"
                >
                    {{ t('auth.login') }}
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
