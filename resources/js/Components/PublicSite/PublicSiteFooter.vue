<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import InstitutionLogo from "@/Components/PublicSite/InstitutionLogo.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";
import PublicModal from "@/Components/PublicSite/PublicModal.vue";

const page = usePage();
const cms = computed(() => page.props.institution || {});
const name = computed(() => cms.value.name || "Bright Future Educational Institute");
const phone = computed(() => cms.value.phone || "+92 42 111 233 348");
const email = computed(() => cms.value.email || "info@example.edu.pk");
const address = computed(() => cms.value.address || "123 Education Road");
const city = computed(() => cms.value.city || "Lahore, Pakistan");
const officeHours = computed(() => cms.value.office_hours || "Monday – Friday, 8:00 AM – 4:00 PM");
const social = computed(() => ({
    facebook: cms.value.social?.facebook || "https://www.facebook.com",
    instagram: cms.value.social?.instagram || "https://www.instagram.com",
    youtube: cms.value.social?.youtube || "https://www.youtube.com",
    linkedin: cms.value.social?.linkedin || "https://www.linkedin.com",
}));

const year = computed(() => new Date().getFullYear());
const legal = ref(null);

const institutionLinks = [
    { label: "About", href: route("publicSite.about") },
    { label: "Vision & Mission", href: `${route("publicSite.about")}#vision` },
    { label: "Leadership", href: `${route("publicSite.about")}#leadership` },
    { label: "Achievements", href: `${route("publicSite.about")}#achievements` },
];

const columns = [
    {
        title: "Academics",
        links: [
            { label: "Programs", href: route("publicSite.academics") },
            { label: "Departments", href: `${route("publicSite.academics")}#departments` },
            { label: "Faculty", href: `${route("publicSite.academics")}#faculty` },
            { label: "Academic Calendar", href: `${route("publicSite.academics")}#calendar` },
        ],
    },
    {
        title: "Admissions",
        links: [
            { label: "Apply Now", href: route("publicSite.admissions") },
            { label: "Eligibility", href: `${route("publicSite.admissions")}#eligibility` },
            { label: "Fee Structure", href: `${route("publicSite.admissions")}#fees` },
            { label: "Scholarships", href: `${route("publicSite.admissions")}#scholarships` },
        ],
    },
];

const legalCopy = {
    privacy: {
        title: "Privacy Policy",
        body: "Bright Future Educational Institute collects only the information needed to respond to enquiries and administer admissions. Personal data submitted through public forms is used for institutional communication and is not sold to third parties.",
    },
    terms: {
        title: "Terms & Conditions",
        body: "Content on this website is provided for general information about programmes, admissions, and campus life. Programme availability, fees, and dates may be updated by the institution. Enrolment is governed by the official prospectus and admission offer.",
    },
    accessibility: {
        title: "Accessibility",
        body: "We aim to keep this website usable with keyboard navigation, readable contrast, and descriptive labels. If you encounter a barrier, please contact info@example.edu.pk so we can improve access.",
    },
};
</script>

<template>
    <footer class="ps-footer">
        <div class="ps-container">
            <div class="ps-footer-grid">
                <div>
                    <Link :href="route('publicSite.home')" class="ps-brand" :aria-label="name">
                        <span class="ps-brand-mark">
                            <InstitutionLogo />
                        </span>
                        <span class="ps-brand-name" style="color: #fff">{{ name }}</span>
                    </Link>
                    <p class="ps-mt-4" style="max-width: 22rem">
                        A professional educational institution in Lahore committed to academic excellence, character, and student success.
                    </p>
                    <h3 class="ps-mt-6">Institution</h3>
                    <ul class="ps-footer-list">
                        <li v-for="link in institutionLinks" :key="link.label">
                            <Link :href="link.href">{{ link.label }}</Link>
                        </li>
                    </ul>
                    <div class="ps-social ps-mt-6">
                        <a :href="social.facebook" aria-label="Facebook" target="_blank" rel="noopener noreferrer">
                            <PublicIcon name="facebook" class="h-4 w-4" />
                        </a>
                        <a :href="social.instagram" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                            <PublicIcon name="instagram" class="h-4 w-4" />
                        </a>
                        <a :href="social.youtube" aria-label="YouTube" target="_blank" rel="noopener noreferrer">
                            <PublicIcon name="youtube" class="h-4 w-4" />
                        </a>
                        <a :href="social.linkedin" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                            <PublicIcon name="linkedin" class="h-4 w-4" />
                        </a>
                    </div>
                </div>

                <div v-for="column in columns" :key="column.title">
                    <h3>{{ column.title }}</h3>
                    <ul class="ps-footer-list">
                        <li v-for="link in column.links" :key="link.label">
                            <Link :href="link.href">{{ link.label }}</Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3>Contact</h3>
                    <ul class="ps-footer-list">
                        <li>{{ address }}<br />{{ city }}</li>
                        <li><a :href="`tel:${phone.replace(/\s/g, '')}`">{{ phone }}</a></li>
                        <li><a :href="`mailto:${email}`">{{ email }}</a></li>
                        <li>{{ officeHours }}</li>
                    </ul>
                </div>
            </div>

            <div class="ps-footer-bottom">
                <p class="ps-mb-0">
                    © {{ year }} {{ name }}. All Rights Reserved.
                </p>
                <div class="ps-row">
                    <button type="button" class="ps-util-link" style="display: inline; color: inherit" @click="legal = 'privacy'">
                        Privacy Policy
                    </button>
                    <button type="button" class="ps-util-link" style="display: inline; color: inherit" @click="legal = 'terms'">
                        Terms & Conditions
                    </button>
                    <button type="button" class="ps-util-link" style="display: inline; color: inherit" @click="legal = 'accessibility'">
                        Accessibility
                    </button>
                </div>
            </div>
        </div>

        <PublicModal
            :open="Boolean(legal)"
            :title="legal ? legalCopy[legal].title : ''"
            @close="legal = null"
        >
            <p v-if="legal">{{ legalCopy[legal].body }}</p>
        </PublicModal>
    </footer>
</template>
