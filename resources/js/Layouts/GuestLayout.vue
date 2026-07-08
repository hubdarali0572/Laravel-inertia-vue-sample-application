<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { useDarkMode } from '@/composables/useDarkMode';

const { isDark, toggleDarkMode } = useDarkMode();

const highlights = [
    'Role-based access control',
    'Media library & image processing',
    'Activity logs & audit trails',
];
</script>

<template>
    <div class="min-h-screen flex bg-slate-50 dark:bg-slate-950">
        <!-- Branding panel -->
        <div class="hidden lg:flex lg:w-[45%] xl:w-1/2 relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 text-white">
            <div class="absolute -right-16 -top-16 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -left-10 bottom-20 w-48 h-48 bg-violet-500/15 rounded-full blur-2xl"></div>

            <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 w-full">
                <Link href="/" class="flex items-center gap-3 w-fit">
                    <ApplicationLogo class="h-9 w-auto fill-indigo-400" />
                    <span class="text-sm font-bold tracking-tight">Unified Media</span>
                </Link>

                <div class="max-w-md">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-indigo-300">
                        Platform
                    </p>
                    <h1 class="mt-3 text-3xl xl:text-4xl font-extrabold leading-tight tracking-tight">
                        Precision-Engineered
                        <span class="text-indigo-300">Governance</span>
                        for Digital Media
                    </h1>
                    <p class="mt-4 text-sm leading-relaxed text-white/70">
                        Secure user management, rich media handling, and complete operational transparency — all in one cohesive platform.
                    </p>

                    <ul class="mt-8 space-y-3">
                        <li
                            v-for="item in highlights"
                            :key="item"
                            class="flex items-center gap-3 text-sm text-white/80"
                        >
                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-indigo-500/30 flex items-center justify-center">
                                <svg class="w-3 h-3 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            {{ item }}
                        </li>
                    </ul>
                </div>

                <p class="text-xs text-white/40">
                    &copy; {{ new Date().getFullYear() }} Unified Media Platform
                </p>
            </div>
        </div>

        <!-- Form panel -->
        <div class="flex flex-1 flex-col">
            <header class="flex items-center justify-between px-6 py-5 lg:px-10">
                <Link href="/" class="lg:hidden flex items-center gap-2">
                    <ApplicationLogo class="h-8 w-auto fill-indigo-600 dark:fill-indigo-400" />
                    <span class="text-sm font-bold text-slate-800 dark:text-white">Unified Media</span>
                </Link>

                <div class="flex items-center gap-3 ml-auto">
                    <Link
                        href="/"
                        class="hidden sm:inline-flex text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                    >
                        &larr; Back to home
                    </Link>
                    <button
                        type="button"
                        @click="toggleDarkMode"
                        class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-200/60 dark:hover:bg-slate-800 transition-colors"
                        :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </header>

            <main class="flex flex-1 items-center justify-center px-6 pb-10 lg:px-10">
                <div class="w-full max-w-md">
                    <div class="rounded-2xl bg-white dark:bg-slate-900 p-8 shadow-xl shadow-slate-200/50 dark:shadow-none ring-1 ring-slate-200 dark:ring-slate-800">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
