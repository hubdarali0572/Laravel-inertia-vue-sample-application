<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-zinc-950 dark:text-gray-100 flex flex-col justify-center">
        <!-- Hero Section -->
        <main class="relative isolate px-6 lg:px-8">
            <!-- Background Decorative Blur -->
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#FF2D20] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"></div>
            </div>

            <div class="mx-auto max-w-3xl py-20 text-center">
                <!-- Tech Badge -->
                <div class="mb-8 flex flex-wrap justify-center gap-3">
                    <span class="rounded-full px-4 py-1.5 text-xs font-semibold leading-6 ring-1 ring-gray-900/10 dark:ring-white/10 bg-white/50 dark:bg-zinc-900/50 backdrop-blur shadow-sm">
                        <span class="text-red-600">Laravel v{{ laravelVersion }}</span> + Inertia + Vue 3
                    </span>
                    <span class="rounded-full px-4 py-1.5 text-xs font-semibold leading-6 ring-1 ring-gray-900/10 dark:ring-white/10 bg-white/50 dark:bg-zinc-900/50 backdrop-blur shadow-sm">
                        PHP v{{ phpVersion }}
                    </span>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-6xl">
                    Precision Engineering for <span class="text-red-600">Digital Media</span>
                </h1>
                
                <p class="mt-6 text-lg leading-8 text-gray-600 dark:text-gray-300">
                    Seamlessly build reactive interfaces with the <strong>Laravel-Inertia-Vue</strong> ecosystem. 
                    Our core integrates <strong>Spatie Media Library</strong> and <strong>Image Intervention</strong> 
                    to automate high-performance image processing and optimized asset delivery for enterprise-grade scalability.
                </p>

                <!-- Action Buttons -->
                <div v-if="canLogin" class="mt-10 flex items-center justify-center gap-x-4">
                    <!-- If Logged In -->
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-md bg-red-600 px-8 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition duration-200"
                    >
                        Go to Dashboard
                    </Link>

                    <!-- If Guest -->
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md bg-white dark:bg-zinc-900 px-8 py-3.5 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-800 transition duration-200"
                        >
                            Sign In
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="rounded-md bg-red-600 px-8 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 transition duration-200"
                        >
                           Sign Up
                        </Link>
                    </template>
                </div>
            </div>
            
        </main>
    </div>
</template>