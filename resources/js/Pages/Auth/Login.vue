<script setup>
import { ref, onMounted } from "vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const captchaContainer = ref(null);
const showPassword = ref(false);

const form = useForm({
    email: "",
    password: "",
    remember: false,
    "g-recaptcha-response": "",
});

onMounted(() => {
    // Check if grecaptcha is available (loaded from the script in app.blade.php)
    if (window.grecaptcha) {
        window.grecaptcha.render(captchaContainer.value, {
            sitekey: usePage().props.recaptcha_site_key,
            callback: (response) => {
                form["g-recaptcha-response"] = response;
            },
        });
    }
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
            if (window.grecaptcha) {
                window.grecaptcha.reset();
                form["g-recaptcha-response"] = "";
            }
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                Welcome back
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Please enter your details to sign in.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm font-medium text-green-600"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <!-- Email -->
            <div>
                <InputLabel
                    for="email"
                    value="Email Address"
                    class="font-semibold text-gray-700"
                />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full px-4 py-3 rounded-xl border-gray-300 focus:ring-slate-500 focus:border-slate-500 shadow-sm transition"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="name@company.com"
                    autocomplete="username"
                />
                <InputError class="mt-1.5" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="font-semibold text-gray-700"
                    />
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs font-medium text-slate-600 hover:text-slate-500"
                    >
                        Forgot password?
                    </Link>
                </div>

                <div class="mt-1 relative">
                    <TextInput
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full px-4 py-3 pr-12 rounded-xl border-gray-300 focus:ring-slate-500 focus:border-slate-500 shadow-sm transition"
                        v-model="form.password"
                        required
                        placeholder="••••••••"
                        autocomplete="current-password"
                    />

                    <!-- Toggle Button -->
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-slate-600 focus:outline-none transition"
                        @click="showPassword = !showPassword"
                    >
                        <svg
                            v-if="!showPassword"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.483 8.613 8.242 4.5 12 4.5c3.758 0 8.517 4.113 9.964 7.178.07.147.07.315 0 .462-1.447 3.065-4.206 7.178-9.964 7.178-3.758 0-8.517-4.113-9.964-7.178Z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                            />
                        </svg>
                        <svg
                            v-else
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                            />
                        </svg>
                    </button>
                </div>
                <InputError class="mt-1.5" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <Checkbox
                    name="remember"
                    v-model:checked="form.remember"
                    class="rounded text-slate-600 focus:ring-slate-500 border-gray-300"
                />
                <span
                    class="ms-2 text-sm text-gray-600 cursor-pointer select-none"
                    @click="form.remember = !form.remember"
                >
                    Remember me
                </span>
            </div>

            <!-- reCAPTCHA Section -->
            <div>
                <InputLabel 
                    for="recaptcha" 
                    value="Verification" 
                    class="font-semibold text-gray-700" 
                />
                <div class="mt-2">
                    <!-- The container for reCAPTCHA -->
                    <div ref="captchaContainer"></div>
                    <InputError 
                        class="mt-2" 
                        :message="form.errors['g-recaptcha-response']" 
                    />
                </div>
            </div>

            <!-- Submit -->
            <div class="pt-2">
                <PrimaryButton
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-slate-600 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Logging in...
                    </span>
                    <span v-else>Log in</span>
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                Don't have an account?
                <Link
                    :href="route('register')"
                    class="font-semibold text-slate-600 hover:text-slate-500 underline-offset-4 hover:underline"
                >
                    Create an account
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>