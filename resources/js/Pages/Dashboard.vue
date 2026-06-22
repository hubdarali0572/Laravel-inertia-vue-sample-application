<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const stats = [
    { title: 'Open job requests', value: '0' },
    { title: 'In review (Inspections)', value: '0' },
    { title: 'Trainings today', value: '0' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-[1700px] mx-auto space-y-4 lg:space-y-8">
            
            <!-- Welcome Banner -->
            <div class="bg-slate-400 rounded-2xl p-6 lg:p-10 text-white relative overflow-hidden shadow-lg">
                <div class="relative z-10">
                    <h2 class="text-[10px] lg:text-3xl font-bold opacity-80 uppercase tracking-widest">OVERVIEW whole System</h2>
                    <p class="mt-3 text-green-50 opacity-90 text-sm lg:text-base max-w-lg leading-relaxed">
                        Quick snapshot of open job requests, inspections in review and trainings scheduled for today.
                    </p>
                </div>
                <div class="absolute -right-10 -top-10 w-40 h-40 lg:w-64 lg:h-64 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <!-- Stats Grid - Responsive columns -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                <div v-for="stat in stats" :key="stat.title" class="bg-white p-5 lg:p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-start">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ stat.title }}</p>
                        <p class="text-3xl lg:text-4xl font-black text-gray-800 mt-2">{{ stat.value }}</p>
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg text-gray-300">
                        <svg class="w-6 h-6 lg:w-8 lg:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>