<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicPageHero from "@/Components/PublicSite/PublicPageHero.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const cms = computed(() => {
    const value = usePage().props.institution;
    return value && typeof value === "object" && !Array.isArray(value) ? value : {};
});
const name = computed(() => cms.value.name || "Bright Future Educational Institute");
const established = computed(() => cms.value.established || 2010);
const type = computed(() => {
    const value = String(cms.value.type || "university").toLowerCase();
    return ["school", "college", "university"].includes(value) ? value : "university";
});

const images = {
    leader: "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=400&h=400&q=80",
    faculty1: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&h=400&q=80",
    faculty2: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=400&h=400&q=80",
    faculty3: "https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=400&h=400&q=80",
    faculty4: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=400&h=400&q=80",
    faculty5: "https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=400&h=400&q=80",
    faculty6: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&h=400&q=80",
    faculty7: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&h=400&q=80",
    faculty8: "https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&h=400&q=80",
};

const leadership = computed(() => {
    if (cms.value.leadership) {
        return cms.value.leadership;
    }

    const heads = {
        school: {
            name: "Dr. Ahmed Khan",
            designation: "Principal",
            qualification: "PhD Education, University of the Punjab",
            experience: "22 years in school leadership and curriculum development",
            photo: images.leader,
        },
        college: {
            name: "Dr. Ahmed Khan",
            designation: "Principal",
            qualification: "PhD Physics, Quaid-i-Azam University",
            experience: "20 years in college administration and science education",
            photo: images.leader,
        },
        university: {
            name: "Dr. Ahmed Khan",
            designation: "Vice Chancellor",
            qualification: "PhD Computer Science, University of Cambridge",
            experience: "25 years in higher education, research, and academic leadership",
            photo: images.leader,
        },
    };

    const roles = {
        school: [
            { name: "Ms. Ayesha Malik", designation: "Vice Principal", qualification: "MPhil Education", experience: "16 years", photo: images.faculty1 },
            { name: "Mr. Imran Qureshi", designation: "Academic Director", qualification: "MS Mathematics", experience: "14 years", photo: images.faculty2 },
            { name: "Dr. Sana Raza", designation: "Head of Secondary", qualification: "PhD Computer Science", experience: "12 years", photo: images.faculty3 },
            { name: "Mr. Hassan Ali", designation: "Head of Primary", qualification: "MBA, MPhil Management", experience: "15 years", photo: images.faculty4 },
            { name: "Dr. Nadia Sheikh", designation: "Head of Student Affairs", qualification: "PhD Educational Leadership", experience: "18 years", photo: images.faculty5 },
            { name: "Mr. Usman Tariq", designation: "Head of Examinations", qualification: "MEd Assessment", experience: "13 years", photo: images.faculty6 },
            { name: "Mr. Bilal Ahmad", designation: "Head of Administration", qualification: "MBA", experience: "11 years", photo: images.faculty7 },
            { name: "Ms. Hina Farooq", designation: "Head of Early Years", qualification: "MA Education", experience: "10 years", photo: images.faculty8 },
        ],
        college: [
            { name: "Ms. Ayesha Malik", designation: "Vice Principal", qualification: "MPhil Education", experience: "16 years", photo: images.faculty1 },
            { name: "Mr. Imran Qureshi", designation: "Academic Director", qualification: "MS Mathematics", experience: "14 years", photo: images.faculty2 },
            { name: "Dr. Sana Raza", designation: "Head of Computer Science", qualification: "PhD Computer Science", experience: "12 years", photo: images.faculty3 },
            { name: "Mr. Hassan Ali", designation: "Head of Science", qualification: "MBA, MPhil Management", experience: "15 years", photo: images.faculty4 },
            { name: "Dr. Nadia Sheikh", designation: "Head of Student Affairs", qualification: "PhD Educational Leadership", experience: "18 years", photo: images.faculty5 },
            { name: "Mr. Usman Tariq", designation: "Controller of Examinations", qualification: "MEd Assessment", experience: "13 years", photo: images.faculty6 },
            { name: "Mr. Bilal Ahmad", designation: "Director Administration", qualification: "MBA", experience: "11 years", photo: images.faculty7 },
            { name: "Ms. Hina Farooq", designation: "Head of Commerce", qualification: "MCom, MPhil", experience: "10 years", photo: images.faculty8 },
        ],
        university: [
            { name: "Prof. Khalid Mehmood", designation: "Pro-Vice Chancellor", qualification: "PhD Engineering", experience: "21 years", photo: images.faculty7 },
            { name: "Ms. Ayesha Malik", designation: "Registrar", qualification: "MPhil Education", experience: "16 years", photo: images.faculty1 },
            { name: "Mr. Imran Qureshi", designation: "Academic Director", qualification: "MS Mathematics", experience: "14 years", photo: images.faculty2 },
            { name: "Dr. Sana Raza", designation: "Head of Computer Science", qualification: "PhD Computer Science", experience: "12 years", photo: images.faculty3 },
            { name: "Mr. Hassan Ali", designation: "Head of Business Studies", qualification: "MBA, MPhil Management", experience: "15 years", photo: images.faculty4 },
            { name: "Dr. Nadia Sheikh", designation: "Director Quality Enhancement", qualification: "PhD Educational Leadership", experience: "18 years", photo: images.faculty5 },
            { name: "Mr. Usman Tariq", designation: "Controller of Examinations", qualification: "MEd Assessment", experience: "13 years", photo: images.faculty6 },
            { name: "Ms. Hina Farooq", designation: "Director Student Affairs", qualification: "MA Education", experience: "10 years", photo: images.faculty8 },
        ],
    };

    return [heads[type.value] || heads.university, ...(roles[type.value] || roles.university)];
});

const values = [
    { title: "Excellence", icon: "star", body: "We pursue high academic standards and continuous improvement in teaching, learning, and institutional practice." },
    { title: "Integrity", icon: "check", body: "Honesty, fairness, and ethical conduct guide our classrooms, assessments, and professional relationships." },
    { title: "Innovation", icon: "flask", body: "We adopt effective methods, technologies, and ideas that strengthen learning without losing academic rigor." },
    { title: "Respect", icon: "users", body: "Every student, parent, and colleague is treated with dignity in an inclusive and courteous community." },
    { title: "Leadership", icon: "cap", body: "We prepare learners to take responsibility, collaborate well, and lead with purpose in their fields." },
    { title: "Responsibility", icon: "briefcase", body: "We act as stewards of student welfare, public trust, and our contribution to wider society." },
];

const achievements = computed(() => {
    const programCount = type.value === "university" ? "30+" : type.value === "college" ? "18+" : "12+";
    const items = [
        { title: "15+ Years of Excellence", body: "A consistent record of academic growth since 2010." },
        { title: "95% Graduate Success Rate", body: "Students progress to universities, professional programmes, and careers." },
        { title: `${programCount} Programs`, body: "A structured academic portfolio matched to our institution type." },
        { title: "National Recognition", body: "Faculty and student achievements acknowledged in academic and co-curricular forums." },
        { title: "Accredited Programmes", body: "Programme accreditation details are published when formally conferred by the relevant body." },
    ];

    if (type.value !== "school") {
        items.push({ title: "10,000+ Alumni", body: "Graduates contributing across education, industry, public service, and enterprise." });
    }

    return items;
});

const accreditation = computed(() => cms.value.accreditation || []);
const objectives = [
    "Deliver high-quality teaching aligned with recognized academic standards.",
    "Develop confident, ethical, and responsible learners.",
    "Provide an inclusive environment that supports diverse abilities and backgrounds.",
    "Prepare students for higher education, careers, and civic life.",
    "Contribute meaningfully to the educational and social development of our community.",
];
</script>

<template>
    <PublicSiteLayout>
        <Head title="About">
            <meta
                head-key="description"
                name="description"
                content="Learn about the history, vision, mission, leadership, and achievements of Bright Future Educational Institute."
            />
            <link head-key="canonical" rel="canonical" :href="route('publicSite.about')" />
        </Head>

        <PublicPageHero
            image="/images/hero-about.jpg"
            object-position="center 40%"
            alt="Campus architecture of Bright Future Educational Institute"
            kicker="Our Institution"
            title="About Bright Future Educational Institute"
            :description="`Founded in ${established}, ${name} has grown into a respected educational institution committed to academic excellence, character development, and holistic student growth in Lahore.`"
            :crumbs="[{ label: 'About' }]"
            :facts="[
                { icon: 'calendar', label: `Established ${established}` },
                { icon: 'pin', label: 'Lahore, Pakistan' },
                { icon: 'cap', label: 'Academic Excellence' },
                { icon: 'users', label: 'Leadership & Character' },
            ]"
        />

        <section class="ps-section">
            <div class="ps-container ps-grid p-8">
                <h2 class="ps-h2">Our History</h2>
                <p class="ps-lead">
                    Founded in 2010, Bright Future Educational Institute has grown into a respected educational institution committed to academic excellence and holistic student development. What began as a focused academic community in Lahore is now a trusted place of learning for students, families, and educators across the region.
                </p>
                <h2 class="ps-h2 ps-mt-8">Educational Philosophy</h2>
                <p class="ps-lead">
                    Our educational philosophy holds that knowledge, character, and service are inseparable. We combine rigorous teaching with pastoral care, modern learning resources, and a culture of respect so that every learner can thrive.
                </p>
                <h2 class="ps-h2 ps-mt-8">Institutional Objectives</h2>
                <ul class="ps-mt-4" style="display: grid; gap: 0.6rem; padding-left: 1.1rem">
                    <li v-for="item in objectives" :key="item">{{ item }}</li>
                </ul>
                <h2 class="ps-h2 ps-mt-8">Community Contribution</h2>
                <p class="ps-lead">
                    Through outreach programmes, parent partnerships, community service, and academic events, the Institute remains an active contributor to Lahore’s educational landscape.
                </p>
            </div>
        </section>

        <section id="vision" class="ps-section ps-section-muted">
            <div class="ps-container ps-grid ps-grid-2">
                <article class="ps-card" data-aos="fade-right">
                    <p class="ps-kicker">Vision</p>
                    <h2 class="ps-h2">Our Vision</h2>
                    <p class="ps-lead">
                        To become a leading educational institution recognized for academic excellence, innovation, character development, and contribution to society.
                    </p>
                </article>
                <article class="ps-card" data-aos="fade-left">
                    <p class="ps-kicker">Mission</p>
                    <h2 class="ps-h2">Our Mission</h2>
                    <p class="ps-lead">
                        To provide accessible, high-quality education that develops knowledgeable, ethical, creative, and responsible individuals prepared to succeed in a rapidly changing world.
                    </p>
                </article>
            </div>
        </section>

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head center" data-aos="fade-up">
                    <h2 class="ps-h2">Core Values</h2>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(value, index) in values"
                        :key="value.title"
                        class="ps-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 90"
                    >
                        <span class="ps-card-icon">
                            <PublicIcon :name="value.icon" />
                        </span>
                        <h3 class="ps-h3 ps-mt-4">{{ value.title }}</h3>
                        <p class="ps-muted ps-mt-2">{{ value.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="leadership" class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Leadership</h2>
                    <p class="ps-lead">Academic and administrative leaders responsible for teaching quality, student welfare, and institutional direction.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(person, index) in leadership"
                        :key="person.name"
                        class="ps-card ps-card-profile"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <div class="ps-card-profile-head">
                            <span class="ps-card-avatar-wrap">
                                <img
                                    class="ps-card-avatar"
                                    :src="person.photo"
                                    :alt="person.name"
                                />
                            </span>
                        </div>
                        <div class="ps-card-profile-body">
                            <h3 class="ps-h3">{{ person.name }}</h3>
                            <p class="ps-card-role">{{ person.designation }}</p>
                            <p class="ps-muted">{{ person.qualification }}</p>
                            <p class="ps-caption">{{ person.experience }}</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="achievements" class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Achievements</h2>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in achievements"
                        :key="item.title"
                        class="ps-card"
                        data-aos="fade-up"
                        :data-aos-delay="(index % 3) * 80"
                    >
                        <h3 class="ps-h3">{{ item.title }}</h3>
                        <p class="ps-muted ps-mt-2">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="ps-section ps-section-muted">
            <div class="ps-container">
                <h2 class="ps-h2">Accreditation</h2>
                <div v-if="accreditation.length" class="ps-grid ps-grid-4 ps-mt-6">
                    <article v-for="item in accreditation" :key="item.title" class="ps-card">
                        {{ item.title }}
                    </article>
                </div>
                <p v-else class="ps-empty ps-mt-4">
                    Accreditation and certification details will be published here when officially supplied by the institution. This website does not display unaudited or assumed affiliations.
                </p>
            </div>
        </section>
    </PublicSiteLayout>
</template>
