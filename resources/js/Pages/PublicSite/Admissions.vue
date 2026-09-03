<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicPageHero from "@/Components/PublicSite/PublicPageHero.vue";
import PublicAccordion from "@/Components/PublicSite/PublicAccordion.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const cms = computed(() => {
    const value = usePage().props.institution;
    return value && typeof value === "object" && !Array.isArray(value) ? value : {};
});
const type = computed(() => {
    const value = String(cms.value.type || "university").toLowerCase();
    return ["school", "college", "university"].includes(value) ? value : "university";
});
const typeLabel = computed(
    () => ({ school: "School", college: "College", university: "University" })[type.value],
);
const studentPortalUrl = computed(() => cms.value.student_portal_url || "/login");

const steps = [
    { step: 1, title: "Select Program", body: "Choose a programme that matches your academic background and goals." },
    { step: 2, title: "Check Eligibility", body: "Confirm minimum qualifications, subject requirements, and age criteria where applicable." },
    { step: 3, title: "Submit Application", body: "Complete the online application with accurate personal and academic details." },
    { step: 4, title: "Upload Documents", body: "Attach scanned copies of required certificates, photographs, and identity documents." },
    { step: 5, title: "Pay Application Fee", body: "Pay the non-refundable application fee through the approved banking channels." },
    { step: 6, title: "Test / Interview", body: "Appear in the entry test and/or interview if required for your selected programme." },
    { step: 7, title: "Admission Decision", body: "Review merit lists and admission offers published by the admissions office." },
    { step: 8, title: "Enrollment", body: "Submit dues, complete enrolment formalities, and collect your student credentials." },
];

const eligibilityByType = {
    school: [
        { program: "Early Years", level: "Foundation", icon: "star", requirement: "Age 3+" },
        { program: "Primary", level: "Primary", icon: "book", requirement: "Early Years completion or equivalent" },
        { program: "Middle School", level: "Middle", icon: "users", requirement: "Primary completion" },
        { program: "Secondary School", level: "Secondary", icon: "cap", requirement: "Middle School completion" },
        { program: "O-Level", level: "O-Level", icon: "clipboard", requirement: "Middle or equivalent with subject aptitude" },
        { program: "A-Level", level: "A-Level", icon: "chart", requirement: "O-Level / Matric with required grades" },
    ],
    college: [
        { program: "FSc Pre-Medical", level: "Intermediate", icon: "heart", requirement: "Matric Science with required marks" },
        { program: "FSc Pre-Engineering", level: "Intermediate", icon: "cog", requirement: "Matric Science with required marks" },
        { program: "ICS", level: "Intermediate", icon: "monitor", requirement: "Matric with mathematics" },
        { program: "I.Com", level: "Intermediate", icon: "briefcase", requirement: "Matric or equivalent" },
        { program: "Associate Degree in Computer Science", level: "Associate Degree", icon: "monitor", requirement: "Intermediate or equivalent" },
        { program: "Associate Degree in Business", level: "Associate Degree", icon: "briefcase", requirement: "Intermediate or equivalent" },
    ],
    university: [
        { program: "BS Computer Science", level: "Undergraduate", icon: "monitor", requirement: "Intermediate / A-Level with mathematics" },
        { program: "BS Software Engineering", level: "Undergraduate", icon: "cog", requirement: "Intermediate / A-Level with mathematics" },
        { program: "BS Information Technology", level: "Undergraduate", icon: "monitor", requirement: "Intermediate / A-Level" },
        { program: "BBA", level: "Undergraduate", icon: "briefcase", requirement: "Intermediate / A-Level" },
        { program: "BS Mathematics", level: "Undergraduate", icon: "chart", requirement: "Intermediate / A-Level with mathematics" },
        { program: "MBA", level: "Graduate", icon: "briefcase", requirement: "Bachelor’s degree with required CGPA" },
        { program: "MS Programs", level: "Graduate", icon: "cap", requirement: "Relevant bachelor’s degree" },
        { program: "PhD Programs", level: "Doctoral", icon: "star", requirement: "MS / MPhil in a relevant field" },
    ],
};

const feesByType = {
    school: [
        { program: "Early Years", admission: 15000, tuition: 42000, other: 8000 },
        { program: "Primary", admission: 18000, tuition: 48000, other: 9000 },
        { program: "Middle School", admission: 20000, tuition: 54000, other: 10000 },
        { program: "O-Level", admission: 25000, tuition: 72000, other: 12000 },
        { program: "A-Level", admission: 28000, tuition: 85000, other: 14000 },
    ],
    college: [
        { program: "FSc Pre-Medical", admission: 20000, tuition: 55000, other: 10000 },
        { program: "FSc Pre-Engineering", admission: 20000, tuition: 55000, other: 10000 },
        { program: "ICS", admission: 22000, tuition: 58000, other: 11000 },
        { program: "I.Com", admission: 18000, tuition: 48000, other: 9000 },
        { program: "Associate Degree Programs", admission: 25000, tuition: 65000, other: 12000 },
    ],
    university: [
        { program: "BS Computer Science", admission: 25000, tuition: 110000, other: 15000 },
        { program: "BS Software Engineering", admission: 25000, tuition: 115000, other: 15000 },
        { program: "BBA", admission: 22000, tuition: 95000, other: 12000 },
        { program: "MBA", admission: 30000, tuition: 140000, other: 18000 },
        { program: "MS Programs", admission: 28000, tuition: 125000, other: 16000 },
        { program: "PhD Programs", admission: 35000, tuition: 150000, other: 20000 },
    ],
};

const documents = [
    { name: "CNIC / B-Form", icon: "clipboard", body: "Valid CNIC or Form-B of the applicant." },
    { name: "Recent Photograph", icon: "user", body: "Passport-size colour photograph on a white background." },
    { name: "Previous Academic Certificate", icon: "cap", body: "Certificate of the last institution attended." },
    { name: "Result Card", icon: "chart", body: "Official mark sheet of the qualifying examination." },
    { name: "Domicile", icon: "pin", body: "Domicile certificate of the applicant or parent." },
    { name: "Transfer Certificate", icon: "download", body: "Required only if transferring from another institution." },
];

const scholarships = [
    {
        title: "Merit Scholarship",
        icon: "star",
        eligibility: "Outstanding previous academic results and high entry-test standing.",
        benefits: "Tuition fee concession of up to 50% for the first academic year.",
        deadline: "15 September 2026",
    },
    {
        title: "Need-Based Scholarship",
        icon: "heart",
        eligibility: "Demonstrated financial need with supporting documentation.",
        benefits: "Partial tuition support assessed by the scholarship committee.",
        deadline: "15 September 2026",
    },
    {
        title: "Sports Scholarship",
        icon: "trophy",
        eligibility: "Verified achievement at board, district, or national level in a recognized sport.",
        benefits: "Fee concession and access to coaching facilities.",
        deadline: "10 September 2026",
    },
    {
        title: "Special Talent Scholarship",
        icon: "bolt",
        eligibility: "Exceptional performance in science, arts, debate, or innovation competitions.",
        benefits: "Award letter and academic fee relief for one year.",
        deadline: "10 September 2026",
    },
    {
        title: "Kinship Scholarship",
        icon: "users",
        eligibility: "Two or more siblings enrolled concurrently at the institution.",
        benefits: "Tuition concession of up to 25% for the additional sibling.",
        deadline: "15 September 2026",
    },
    {
        title: "Financial Assistance",
        icon: "briefcase",
        eligibility: "Enrolled students facing sudden financial hardship.",
        benefits: "Installment plans or emergency assistance as approved.",
        deadline: "Open throughout the session",
    },
];

const dates = [
    { label: "Application Opening", date: "01 August 2026" },
    { label: "Application Deadline", date: "15 September 2026" },
    { label: "Entry Test", date: "22 September 2026" },
    { label: "Interview", date: "28 September 2026" },
    { label: "Merit List", date: "05 October 2026" },
    { label: "Fee Submission", date: "12 October 2026" },
];

const faqs = [
    { q: "When do admissions open?", a: "Admissions for academic session 2026–27 open on 01 August 2026. The application deadline is 15 September 2026." },
    { q: "How can I apply?", a: "Submit the online application from the Admissions page, upload the required documents, and pay the application fee. You may also visit the admissions office during office hours for guidance." },
    { q: "What documents are required?", a: "Applicants should prepare CNIC or B-Form, a recent photograph, previous academic certificate, result card, domicile, and a transfer certificate if moving from another institution." },
    { q: "Is there an admission test?", a: "Selected programmes require an entry test and/or interview. Test dates are published under Important Dates and on the notices board." },
    { q: "How can I pay the application fee?", a: "The application fee can be paid through the designated bank challan or the approved online payment channel listed on the application form. Keep the receipt for your records." },
    { q: "Are scholarships available?", a: "Yes. Merit, need-based, sports, special talent, kinship, and financial assistance options are available. Review eligibility, benefits, and deadlines on the Admissions page." },
];

const eligibility = computed(() => eligibilityByType[type.value] || eligibilityByType.university);
const fees = computed(() =>
    (feesByType[type.value] || feesByType.university).map((row) => ({
        ...row,
        total: row.admission + row.tuition + row.other,
    })),
);

const rupees = (value) => `Rs ${Number(value).toLocaleString("en-PK")}`;
</script>

<template>
    <PublicSiteLayout>
        <Head title="Admissions">
            <meta
                head-key="description"
                name="description"
                content="Admissions are open for academic session 2026–27. Review eligibility, fees, scholarships, required documents, and important dates."
            />
            <link head-key="canonical" rel="canonical" :href="route('publicSite.admissions')" />
        </Head>

        <PublicPageHero
            image="/images/hero-admissions.jpg"
            object-position="center 38%"
            alt="Students beginning their academic journey"
            kicker="Academic Session 2026–27"
            title="Admissions Open 2026–27"
            description="Begin your educational journey with an institution committed to excellence, innovation, and student success. Review eligibility, fees, scholarships, and key dates below."
            :crumbs="[{ label: 'Admissions' }]"
            :facts="[
                { icon: 'calendar', label: 'Apply by 15 September 2026' },
                { icon: 'clipboard', label: 'Entry Test 22 September' },
                { icon: 'star', label: 'Scholarships Available' },
                { icon: 'pin', label: 'Lahore Campus' },
            ]"
        >
            <template #actions>
                <a href="#apply" class="ps-btn ps-btn-primary">Apply Online</a>
                <Link :href="`${route('publicSite.news')}#downloads`" class="ps-btn ps-btn-outline">
                    Download Prospectus
                </Link>
            </template>
        </PublicPageHero>

        <section id="eligibility" class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Eligibility</h2>
                    <p class="ps-lead">Minimum academic requirements for {{ typeLabel.toLowerCase() }} programmes currently offered.</p>
                </div>
                <div class="ps-grid ps-grid-2">
                    <article
                        v-for="(item, index) in eligibility"
                        :key="item.program"
                        class="ps-unit-card ps-unit-card-wide"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 2) * 80"
                    >
                        <span class="ps-unit-card-accent" aria-hidden="true" />
                        <span class="ps-unit-icon" aria-hidden="true">
                            <PublicIcon :name="item.icon" />
                        </span>
                        <div class="ps-unit-wide-copy">
                            <div class="ps-unit-wide-head">
                                <p class="ps-badge">{{ item.level }}</p>
                                <span class="ps-unit-index">{{ String(index + 1).padStart(2, "0") }}</span>
                            </div>
                            <h3 class="ps-unit-name">{{ item.program }}</h3>
                            <p class="ps-unit-copy">{{ item.requirement }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Required Documents</h2>
                    <p class="ps-lead">Prepare these documents before submitting an application. Scanned colour copies are accepted with the online form.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in documents"
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
                        <p class="ps-unit-copy">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="scholarships" class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Scholarships</h2>
                    <p class="ps-lead">Six award routes covering academic merit, financial need, sport, talent, kinship, and emergency assistance.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in scholarships"
                        :key="item.title"
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
                        <h3 class="ps-unit-name">{{ item.title }}</h3>
                        <div class="ps-unit-meta">
                            <p class="ps-unit-meta-row"><strong>Eligibility:</strong> {{ item.eligibility }}</p>
                            <p class="ps-unit-meta-row"><strong>Benefits:</strong> {{ item.benefits }}</p>
                        </div>
                        <p class="ps-unit-deadline">
                            <PublicIcon name="calendar" />
                            <span>Deadline: {{ item.deadline }}</span>
                        </p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container" style="max-width: 48rem">
                <div class="ps-section-head">
                    <h2 class="ps-h2">Admission FAQ</h2>
                </div>
                <PublicAccordion v-for="item in faqs" :key="item.q" :question="item.q">
                    {{ item.a }}
                </PublicAccordion>
            </div>
        </section>
    </PublicSiteLayout>
</template>
