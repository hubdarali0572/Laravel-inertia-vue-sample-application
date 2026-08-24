<script setup>
import { Head, useForm, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicPageHero from "@/Components/PublicSite/PublicPageHero.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const cms = computed(() => {
    const value = usePage().props.institution;
    return value && typeof value === "object" && !Array.isArray(value) ? value : {};
});
const name = computed(() => cms.value.name || "Bright Future Educational Institute");
const phone = computed(() => cms.value.phone || "+92 42 111 233 348");
const email = computed(() => cms.value.email || "info@example.edu.pk");
const address = computed(() => cms.value.address || "123 Education Road");
const city = computed(() => cms.value.city || "Lahore, Pakistan");
const officeHours = computed(() => cms.value.office_hours || "Monday – Friday, 8:00 AM – 4:00 PM");
const mapEmbed = computed(
    () =>
        cms.value.map?.embed ||
        "https://www.google.com/maps?q=123+Education+Road,+Lahore,+Pakistan&hl=en&z=15&output=embed",
);
const mapDirections = computed(
    () =>
        cms.value.map?.directions ||
        "https://www.google.com/maps/dir/?api=1&destination=123+Education+Road,+Lahore,+Pakistan",
);
const social = computed(() => ({
    facebook: cms.value.social?.facebook || "https://www.facebook.com",
    instagram: cms.value.social?.instagram || "https://www.instagram.com",
    youtube: cms.value.social?.youtube || "https://www.youtube.com",
    linkedin: cms.value.social?.linkedin || "https://www.linkedin.com",
}));

const flash = computed(() => usePage().props.flash || {});

const channels = computed(() => [
    { title: "Phone", icon: "phone", body: phone.value, href: `tel:${phone.value.replace(/\s/g, "")}` },
    { title: "Email", icon: "mail", body: email.value, href: `mailto:${email.value}` },
    { title: "Campus Address", icon: "pin", body: `${address.value}, ${city.value}`, href: mapDirections.value },
    { title: "Office Hours", icon: "calendar", body: officeHours.value, href: null },
]);

const departments = [
    { name: "Admissions", icon: "clipboard", phone: "+92 42 111 233 348", email: "admissions@example.edu.pk" },
    { name: "Examination", icon: "book", phone: "+92 42 111 233 349", email: "exams@example.edu.pk" },
    { name: "Accounts", icon: "briefcase", phone: "+92 42 111 233 350", email: "accounts@example.edu.pk" },
    { name: "Administration", icon: "building", phone: "+92 42 111 233 351", email: "admin@example.edu.pk" },
    { name: "Student Affairs", icon: "users", phone: "+92 42 111 233 352", email: "students@example.edu.pk" },
    { name: "IT Helpdesk", icon: "monitor", phone: "+92 42 111 233 353", email: "itsupport@example.edu.pk" },
];

const socialLinks = computed(() => [
    { name: "Facebook", icon: "facebook", href: social.value.facebook, body: "News, events, and campus updates." },
    { name: "Instagram", icon: "instagram", href: social.value.instagram, body: "Student life and campus photography." },
    { name: "YouTube", icon: "youtube", href: social.value.youtube, body: "Lectures, ceremonies, and recorded events." },
    { name: "LinkedIn", icon: "linkedin", href: social.value.linkedin, body: "Careers, alumni, and institutional news." },
]);

const form = useForm({
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
});

const submit = () => {
    form.post(route("publicSite.contact.submit"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <PublicSiteLayout>
        <Head title="Contact">
            <meta
                head-key="description"
                name="description"
                content="Contact Bright Future Educational Institute in Lahore for admissions, programmes, fees, and campus information."
            />
            <link head-key="canonical" rel="canonical" :href="route('publicSite.contact')" />
        </Head>

        <PublicPageHero
            image="/images/hero-contact.jpg"
            object-position="center 40%"
            alt="Campus reception and visitor office"
            kicker="Get in Touch"
            title="We're Here to Help"
            description="Questions about admissions, programmes, fees, or campus life? Reach the relevant office in Lahore or send a message — our team responds during working hours."
            :crumbs="[{ label: 'Contact' }]"
            :facts="[
                { icon: 'calendar', label: officeHours },
                { icon: 'pin', label: city },
                { icon: 'phone', label: phone },
                { icon: 'clipboard', label: 'Admissions Desk' },
            ]"
        />

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Contact Information</h2>
                    <p class="ps-lead">Reach the main campus in Lahore by phone, email, or in person during office hours.</p>
                </div>
                <div class="ps-grid ps-grid-2">
                    <article
                        v-for="(item, index) in channels"
                        :key="item.title"
                        class="ps-unit-card ps-unit-card-wide"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 2) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <span class="ps-unit-icon" aria-hidden="true">
                            <PublicIcon :name="item.icon" />
                        </span>
                        <div class="ps-unit-wide-copy">
                            <p class="ps-badge">{{ item.title }}</p>
                            <a
                                v-if="item.href"
                                class="ps-unit-name ps-contact-value"
                                :href="item.href"
                                :target="item.icon === 'pin' ? '_blank' : undefined"
                                :rel="item.icon === 'pin' ? 'noopener noreferrer' : undefined"
                            >
                                {{ item.body }}
                            </a>
                            <h3 v-else class="ps-unit-name">{{ item.body }}</h3>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Send a Message</h2>
                    <p class="ps-lead">Share your enquiry and the relevant office will respond during working hours.</p>
                </div>
                <form class="ps-contact-form" data-aos="fade-up" @submit.prevent="submit">
                    <span class="ps-unit-card-accent" aria-hidden="true" />
                    <p v-if="flash.success" class="ps-success">{{ flash.success }}</p>
                    <div class="ps-form-grid">
                        <div>
                            <label class="ps-form-label" for="name">Full Name</label>
                            <input id="name" v-model="form.name" class="ps-input" type="text" autocomplete="name" required />
                            <p v-if="form.errors.name" class="ps-error">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="ps-form-label" for="email">Email</label>
                            <input id="email" v-model="form.email" class="ps-input" type="email" autocomplete="email" required />
                            <p v-if="form.errors.email" class="ps-error">{{ form.errors.email }}</p>
                        </div>
                        <div>
                            <label class="ps-form-label" for="phone">Phone</label>
                            <input id="phone" v-model="form.phone" class="ps-input" type="tel" autocomplete="tel" required />
                            <p v-if="form.errors.phone" class="ps-error">{{ form.errors.phone }}</p>
                        </div>
                        <div>
                            <label class="ps-form-label" for="subject">Subject</label>
                            <input id="subject" v-model="form.subject" class="ps-input" type="text" required />
                            <p v-if="form.errors.subject" class="ps-error">{{ form.errors.subject }}</p>
                        </div>
                    </div>
                    <div class="ps-mt-4">
                        <label class="ps-form-label" for="message">Message</label>
                        <textarea id="message" v-model="form.message" class="ps-textarea" required />
                        <p v-if="form.errors.message" class="ps-error">{{ form.errors.message }}</p>
                    </div>
                    <button type="submit" class="ps-btn ps-btn-primary ps-mt-5" :disabled="form.processing">
                        {{ form.processing ? "Sending…" : "Send Message" }}
                        <PublicIcon name="arrow" />
                    </button>
                </form>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Department Contacts</h2>
                    <p class="ps-lead">Direct lines and email addresses for the offices most often contacted by students and parents.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in departments"
                        :key="item.name"
                        class="ps-unit-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <div class="ps-unit-card-top">
                            <span class="ps-unit-icon" aria-hidden="true">
                                <PublicIcon :name="item.icon" />
                            </span>
                            <span class="ps-unit-index">{{ String(index + 1).padStart(2, "0") }}</span>
                        </div>
                        <h3 class="ps-unit-name">{{ item.name }}</h3>
                        <p class="ps-unit-copy">
                            <a class="ps-contact-value" :href="`tel:${item.phone.replace(/\s/g, '')}`">{{ item.phone }}</a>
                        </p>
                        <a class="ps-unit-deadline" :href="`mailto:${item.email}`">
                            <PublicIcon name="mail" />
                            <span>{{ item.email }}</span>
                        </a>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Campus Location</h2>
                    <p class="ps-lead">{{ address }}, {{ city }}</p>
                </div>
                <div class="ps-map-wrap" data-aos="fade-up">
                    <iframe
                        class="ps-map"
                        :src="mapEmbed"
                        title="Bright Future Educational Institute location map"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    />
                </div>
                <div class="ps-actions">
                    <a :href="mapDirections" class="ps-btn ps-btn-primary" target="_blank" rel="noopener noreferrer">
                        Get Directions
                        <PublicIcon name="pin" />
                    </a>
                </div>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Social Media</h2>
                    <p class="ps-lead">Follow official channels for notices, student life, and institutional updates.</p>
                </div>
                <div class="ps-grid ps-grid-2">
                    <a
                        v-for="(item, index) in socialLinks"
                        :key="item.name"
                        class="ps-unit-card ps-unit-card-wide"
                        :href="item.href"
                        target="_blank"
                        rel="noopener noreferrer"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 2) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <span class="ps-unit-icon" aria-hidden="true">
                            <PublicIcon :name="item.icon" />
                        </span>
                        <div class="ps-unit-wide-copy">
                            <h3 class="ps-unit-name">{{ item.name }}</h3>
                            <p class="ps-unit-copy">{{ item.body }}</p>
                        </div>
                    </a>
                </div>
            </div>
        </section>
    </PublicSiteLayout>
</template>
