<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { onMounted, nextTick } from "vue";
import AOS from "aos";
import "aos/dist/aos.css";

onMounted(() => {
    nextTick(() => {
        AOS.init({
            duration: 800,
            once: true,
            mirror: false,
        });
    });
});

onMounted(() => {
    AOS.init();
    setTimeout(() => {
        AOS.refresh();
    }, 100); // Forces a re-scan of the page after 100ms
});

const stats = [
    {
        title: "Open job requests",
        value: "0",
        iconPath: "M13 7h8m0 0v8m0-8l-8 8-4-4-6 6",
    },
    {
        title: "In review (Inspections)",
        value: "0",
        iconPath: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    {
        title: "Trainings today",
        value: "0",
        iconPath: "M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z",
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="max-w-[1700px] mx-auto space-y-4 lg:space-y-8 p-4 lg:p-8">
            <!-- Welcome Banner - Added Gradient for a more premium look -->
            <div
                data-aos="fade-down"
                class="bg-gradient-to-r from-slate-500 to-slate-400 rounded-2xl p-6 lg:p-10 text-white relative overflow-hidden shadow-lg"
            >
                <div class="relative z-10">
                    <h2
                        class="text-xs lg:text-3xl font-bold opacity-90 uppercase tracking-widest"
                    >
                        OVERVIEW whole System
                    </h2>
                    <p
                        class="mt-3 text-white/80 text-sm lg:text-base max-w-lg leading-relaxed"
                    >
                        Quick snapshot of open job requests, inspections in
                        review and trainings scheduled for today.
                    </p>
                </div>
                <!-- Decorative Circles -->
                <div
                    class="absolute -right-10 -top-10 w-40 h-40 lg:w-64 lg:h-64 bg-white/10 rounded-full blur-2xl"
                ></div>
                <div
                    class="absolute right-20 bottom-0 w-20 h-20 bg-white/5 rounded-full blur-xl"
                ></div>
            </div>

            <!-- Stats Grid -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6"
            >
                <div
                    v-for="(stat, index) in stats"
                    :key="stat.title"
                    data-aos="fade-up"
                    :data-aos-delay="index * 100"
                    data-aos-duration="1000"
                    data-aos-easing="ease-out-back"
                    class="group bg-white p-5 lg:p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-start transition-all duration-300 ease-in-out hover:-translate-y-1 hover:shadow-xl hover:border-blue-200"
                >
                    <div>
                        <!-- Title with slightly more letter spacing -->
                        <p
                            class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]"
                        >
                            {{ stat.title }}
                        </p>

                        <!-- Value with a subtle scale effect on card hover -->
                        <p
                            class="text-3xl lg:text-4xl font-black text-gray-800 mt-2 transition-transform duration-500 group-hover:scale-105 origin-left"
                        >
                            {{ stat.value }}
                        </p>
                    </div>

                    <!-- Icon Container: Changes color on card hover -->
                    <div
                        class="bg-gray-50 p-3 rounded-xl text-gray-400 transition-all duration-300 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:rotate-3"
                    >
                        <svg
                            class="w-6 h-6 lg:w-8 lg:h-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                :d="stat.iconPath"
                            />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
