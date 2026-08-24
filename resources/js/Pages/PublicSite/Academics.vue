<script setup>
import { Head, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import PublicSiteLayout from "@/Layouts/PublicSite/PublicSiteLayout.vue";
import PublicPageHero from "@/Components/PublicSite/PublicPageHero.vue";
import PublicModal from "@/Components/PublicSite/PublicModal.vue";
import PublicIcon from "@/Components/PublicSite/PublicIcon.vue";

const cms = computed(() => {
    const value = usePage().props.institution;
    return value && typeof value === "object" && !Array.isArray(value) ? value : {};
});
const type = computed(() => {
    const value = String(cms.value.type || "university").toLowerCase();
    return ["school", "college", "university"].includes(value) ? value : "university";
});

const images = {
    leader: "https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80",
    faculty1: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80",
    faculty2: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=600&q=80",
    faculty3: "https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=600&q=80",
    faculty4: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=600&q=80",
    faculty5: "https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=600&q=80",
};

const outcome =
    "Graduates are prepared for the next academic stage, professional training, or relevant career pathways, with a grounding in ethics and independent learning.";

const programCatalog = {
    school: [
        { id: "early-years", name: "Early Years", level: "Foundation", duration: "2 years", eligibility: "Age 3+", faculty: "Early Years", department: "Early Years", description: "A nurturing introduction to school life focusing on language, play, motor skills, and social development.", outcomes: outcome },
        { id: "primary", name: "Primary", level: "Primary", duration: "5 years", eligibility: "Early Years completion or equivalent", faculty: "Primary", department: "Primary", description: "A broad primary curriculum covering literacy, numeracy, science, arts, and personal development.", outcomes: outcome },
        { id: "middle", name: "Middle School", level: "Middle", duration: "3 years", eligibility: "Primary completion", faculty: "Middle", department: "Middle", description: "Specialist subject teaching that strengthens independent study and prepares students for secondary school.", outcomes: outcome },
        { id: "secondary", name: "Secondary School", level: "Secondary", duration: "2 years", eligibility: "Middle School completion", faculty: "Secondary", department: "Secondary", description: "Secondary education with a focus on core subjects, examination habits, and responsible citizenship.", outcomes: outcome },
        { id: "olevel", name: "O-Level", level: "O-Level", duration: "2 years", eligibility: "Middle or equivalent with subject aptitude", faculty: "Secondary", department: "Secondary", description: "Cambridge O-Level pathway with structured subject options and regular assessment.", outcomes: outcome },
        { id: "alevel", name: "A-Level", level: "A-Level", duration: "2 years", eligibility: "O-Level / Matric with required grades", faculty: "Secondary", department: "Secondary", description: "Advanced Level study for university entry, with guidance on subject combinations and applications.", outcomes: outcome },
    ],
    college: [
        { id: "pre-medical", name: "FSc Pre-Medical", level: "Intermediate", duration: "2 years", eligibility: "Matric Science with required marks", faculty: "Science", department: "Biology", description: "Biology, chemistry, and physics for medical and allied health university pathways.", outcomes: outcome },
        { id: "pre-engineering", name: "FSc Pre-Engineering", level: "Intermediate", duration: "2 years", eligibility: "Matric Science with required marks", faculty: "Science", department: "Physics", description: "Mathematics, physics, and chemistry for engineering and technology programmes.", outcomes: outcome },
        { id: "ics", name: "ICS", level: "Intermediate", duration: "2 years", eligibility: "Matric with mathematics", faculty: "Computer Science", department: "Computer Science", description: "Intermediate Computer Science combining programming foundations with mathematics and physics or statistics.", outcomes: outcome },
        { id: "icom", name: "I.Com", level: "Intermediate", duration: "2 years", eligibility: "Matric or equivalent", faculty: "Commerce", department: "Business Administration", description: "Accounting, commerce, and economics for business and professional accounting routes.", outcomes: outcome },
        { id: "adp-cs", name: "Associate Degree in Computer Science", level: "Associate Degree", duration: "2 years", eligibility: "Intermediate or equivalent", faculty: "Computer Science", department: "Computer Science", description: "A two-year associate degree building applied computing skills for work or further study.", outcomes: outcome },
        { id: "adp-ba", name: "Associate Degree in Business", level: "Associate Degree", duration: "2 years", eligibility: "Intermediate or equivalent", faculty: "Commerce", department: "Business Administration", description: "Foundations of management, accounting, and entrepreneurship at associate degree level.", outcomes: outcome },
    ],
    university: [
        { id: "bscs", name: "BS Computer Science", level: "Undergraduate", duration: "4 years", eligibility: "Intermediate / A-Level with mathematics", faculty: "Faculty of Computing", department: "Computer Science", description: "A rigorous computing degree covering programming, algorithms, databases, and software systems.", outcomes: outcome },
        { id: "bsse", name: "BS Software Engineering", level: "Undergraduate", duration: "4 years", eligibility: "Intermediate / A-Level with mathematics", faculty: "Faculty of Computing", department: "Computer Science", description: "Software process, design, quality, and team-based development for professional engineering practice.", outcomes: outcome },
        { id: "bsit", name: "BS Information Technology", level: "Undergraduate", duration: "4 years", eligibility: "Intermediate / A-Level", faculty: "Faculty of Computing", department: "Computer Science", description: "Applied IT systems, networks, and digital services for organizational environments.", outcomes: outcome },
        { id: "bba", name: "BBA", level: "Undergraduate", duration: "4 years", eligibility: "Intermediate / A-Level", faculty: "Faculty of Business", department: "Business Administration", description: "Business administration with pathways in marketing, finance, and management.", outcomes: outcome },
        { id: "bsmath", name: "BS Mathematics", level: "Undergraduate", duration: "4 years", eligibility: "Intermediate / A-Level with mathematics", faculty: "Faculty of Sciences", department: "Mathematics", description: "Pure and applied mathematics preparing students for teaching, analysis, and graduate study.", outcomes: outcome },
        { id: "mba", name: "MBA", level: "Graduate", duration: "2 years", eligibility: "Bachelor’s degree with required CGPA", faculty: "Faculty of Business", department: "Business Administration", description: "A professional master’s programme for leadership, strategy, and managerial decision-making.", outcomes: outcome },
        { id: "ms", name: "MS Programs", level: "Graduate", duration: "2 years", eligibility: "Relevant bachelor’s degree", faculty: "Faculty of Computing", department: "Computer Science", description: "Research-informed master’s study in selected computing and science disciplines.", outcomes: outcome },
        { id: "phd", name: "PhD Programs", level: "Doctoral", duration: "3–5 years", eligibility: "MS / MPhil in a relevant field", faculty: "Faculty of Sciences", department: "Mathematics", description: "Supervised doctoral research contributing original scholarship in approved disciplines.", outcomes: outcome },
    ],
};

const facultyCatalog = {
    school: [
        { name: "Early Years", icon: "star", body: "Play-based learning, language development, and early numeracy in a nurturing setting." },
        { name: "Primary", icon: "book", body: "Core literacy, numeracy, science, and social studies with strong pastoral care." },
        { name: "Middle", icon: "users", body: "Subject specialist teaching that prepares students for secondary pathways." },
        { name: "Secondary", icon: "cap", body: "O-Level and A-Level preparation with examination coaching and career advice." },
        { name: "O-Level", icon: "clipboard", body: "Cambridge O-Level study with structured subject options and regular assessment." },
        { name: "A-Level", icon: "chart", body: "Advanced Level pathways for university entry, with guided subject combinations." },
        { name: "Languages", icon: "book", body: "English, Urdu, and additional language study for confident communication." },
        { name: "STEM", icon: "flask", body: "Science, technology, engineering, and mathematics with practical investigation." },
        { name: "Arts & Sports", icon: "trophy", body: "Creative arts, physical education, and co-curricular activity across year groups." },
    ],
    college: [
        { name: "Science", icon: "flask", body: "Pre-medical and pre-engineering streams with laboratory-intensive teaching." },
        { name: "Humanities", icon: "swatch", body: "Languages, social sciences, and critical thinking for arts and general groups." },
        { name: "Commerce", icon: "briefcase", body: "Accounting, business, and economics for I.Com and commerce pathways." },
        { name: "Computer Science", icon: "monitor", body: "ICS and computing foundations for university study in IT disciplines." },
        { name: "Pre-Medical", icon: "heart", body: "Biology, chemistry, and physics for medical and allied-health university routes." },
        { name: "Pre-Engineering", icon: "cog", body: "Mathematics, physics, and chemistry for engineering and technology programmes." },
        { name: "Languages", icon: "book", body: "Academic English and communication skills for college and university study." },
        { name: "Social Sciences", icon: "users", body: "Civics, economics, and society-focused study for general and arts groups." },
        { name: "Professional Studies", icon: "clipboard", body: "Associate-degree and career-ready pathways in computing and business." },
    ],
    university: [
        { name: "Faculty of Computing", icon: "monitor", body: "Computer science, software engineering, and information technology programmes." },
        { name: "Faculty of Business", icon: "briefcase", body: "Undergraduate and graduate business education with industry engagement." },
        { name: "Faculty of Sciences", icon: "flask", body: "Mathematics and physical sciences with research-informed teaching." },
        { name: "Faculty of Arts", icon: "swatch", body: "Languages, humanities, and communication studies." },
        { name: "Faculty of Engineering", icon: "cog", body: "Applied engineering education supported by laboratories and project work." },
        { name: "Faculty of Social Sciences", icon: "users", body: "Economics, sociology, and civic studies that examine society and public life." },
        { name: "Faculty of Education", icon: "cap", body: "Teacher education, curriculum studies, and professional development for schools." },
        { name: "Faculty of Law", icon: "scale", body: "Legal studies, jurisprudence, and professional preparation for the justice system." },
        { name: "Faculty of Health Sciences", icon: "heart", body: "Allied health, public health, and laboratory-supported clinical pathways." },
    ],
};

const departmentCatalog = {
    school: [
        { name: "Computer Science", icon: "monitor", body: "Computing fundamentals, programming, and applied digital skills." },
        { name: "Mathematics", icon: "chart", body: "Quantitative reasoning, analysis, and examination-focused mathematics." },
        { name: "English", icon: "book", body: "Academic writing, literature, and communication skills." },
        { name: "Science", icon: "flask", body: "Integrated science leading to biology, chemistry, and physics." },
        { name: "Islamiyat & Pakistan Studies", icon: "star", body: "Civic, historical, and ethical education aligned with the school curriculum." },
        { name: "Physical Education", icon: "trophy", body: "Fitness, games, and healthy living across all year groups." },
        { name: "Biology", icon: "leaf", body: "Life sciences and laboratory investigation for secondary science pathways." },
        { name: "Urdu", icon: "book", body: "Language, literature, and communication in the national language." },
        { name: "Art & Design", icon: "swatch", body: "Visual arts, design thinking, and creative studio practice." },
    ],
    college: [
        { name: "Computer Science", icon: "monitor", body: "Computing fundamentals, programming, and applied digital skills." },
        { name: "Mathematics", icon: "chart", body: "Quantitative reasoning, analysis, and examination-focused mathematics." },
        { name: "English", icon: "book", body: "Academic writing, literature, and communication skills." },
        { name: "Business Administration", icon: "briefcase", body: "Management, accounting, and organizational studies." },
        { name: "Physics", icon: "bolt", body: "Mechanics, electricity, and laboratory investigation." },
        { name: "Chemistry", icon: "flask", body: "Theoretical and practical chemistry for science pathways." },
        { name: "Biology", icon: "leaf", body: "Life sciences supporting pre-medical and allied-health study." },
        { name: "Economics", icon: "globe", body: "Microeconomics, markets, and applied economic analysis." },
        { name: "Accounting", icon: "clipboard", body: "Financial accounting, bookkeeping, and commerce foundations." },
    ],
    university: [
        { name: "Computer Science", icon: "monitor", body: "Computing fundamentals, programming, and applied digital skills." },
        { name: "Mathematics", icon: "chart", body: "Quantitative reasoning, analysis, and examination-focused mathematics." },
        { name: "English", icon: "book", body: "Academic writing, literature, and communication skills." },
        { name: "Business Administration", icon: "briefcase", body: "Management, accounting, and organizational studies." },
        { name: "Physics", icon: "bolt", body: "Mechanics, electricity, and laboratory investigation." },
        { name: "Chemistry", icon: "flask", body: "Theoretical and practical chemistry for science pathways." },
        { name: "Biology", icon: "leaf", body: "Life sciences, ecology, and laboratory-based biological study." },
        { name: "Economics", icon: "globe", body: "Microeconomics, development, and applied economic analysis." },
        { name: "Electrical Engineering", icon: "cog", body: "Circuits, electronics, and applied electrical systems." },
    ],
};

const facultyMembersCatalog = {
    school: [
        { name: "Dr. Sana Raza", designation: "Professor", department: "Secondary", qualification: "PhD Computer Science", photo: images.faculty3 },
        { name: "Mr. Imran Qureshi", designation: "Associate Professor", department: "Mathematics", qualification: "MS Mathematics", photo: images.faculty2 },
        { name: "Ms. Ayesha Malik", designation: "Assistant Professor", department: "English", qualification: "MPhil English", photo: images.faculty1 },
        { name: "Mr. Hassan Ali", designation: "Associate Professor", department: "Science", qualification: "MBA, MPhil", photo: images.faculty4 },
        { name: "Dr. Nadia Sheikh", designation: "Professor", department: "Student Affairs", qualification: "PhD Education", photo: images.faculty5 },
        { name: "Dr. Ahmed Khan", designation: "Principal", department: "Administration", qualification: "PhD", photo: images.leader },
    ],
    college: [
        { name: "Dr. Sana Raza", designation: "Professor", department: "Computer Science", qualification: "PhD Computer Science", photo: images.faculty3 },
        { name: "Mr. Imran Qureshi", designation: "Associate Professor", department: "Mathematics", qualification: "MS Mathematics", photo: images.faculty2 },
        { name: "Ms. Ayesha Malik", designation: "Assistant Professor", department: "English", qualification: "MPhil English", photo: images.faculty1 },
        { name: "Mr. Hassan Ali", designation: "Associate Professor", department: "Business Administration", qualification: "MBA, MPhil", photo: images.faculty4 },
        { name: "Dr. Nadia Sheikh", designation: "Professor", department: "Student Affairs", qualification: "PhD Education", photo: images.faculty5 },
        { name: "Dr. Ahmed Khan", designation: "Principal", department: "Administration", qualification: "PhD", photo: images.leader },
    ],
    university: [
        { name: "Dr. Sana Raza", designation: "Professor", department: "Computer Science", qualification: "PhD Computer Science", photo: images.faculty3 },
        { name: "Mr. Imran Qureshi", designation: "Associate Professor", department: "Mathematics", qualification: "MS Mathematics", photo: images.faculty2 },
        { name: "Ms. Ayesha Malik", designation: "Assistant Professor", department: "English", qualification: "MPhil English", photo: images.faculty1 },
        { name: "Mr. Hassan Ali", designation: "Associate Professor", department: "Business Administration", qualification: "MBA, MPhil", photo: images.faculty4 },
        { name: "Dr. Nadia Sheikh", designation: "Professor", department: "Quality Enhancement", qualification: "PhD Education", photo: images.faculty5 },
        { name: "Dr. Ahmed Khan", designation: "Vice Chancellor", department: "Administration", qualification: "PhD", photo: images.leader },
    ],
};

const overviews = {
    school: "Our school curriculum builds strong foundations from the early years through secondary education. Students progress through a carefully sequenced programme that balances core academic subjects, languages, creative arts, physical education, and character development, with pathways toward O-Level and A-Level study.",
    college: "Our college academic programme prepares students for intermediate qualifications, associate degrees, and competitive university entry. Teaching is structured around conceptual clarity, laboratory practice, examination readiness, and career guidance.",
    university: "Our university academic model combines disciplinary depth with interdisciplinary thinking. Faculties and departments deliver undergraduate, graduate, and doctoral programmes supported by laboratories, research culture, quality enhancement, and industry-informed teaching.",
};

const research = [
    { title: "Centre for Computing Research", icon: "flask", body: "Applied research in software systems, data, and digital education." },
    { title: "ORIC", icon: "briefcase", body: "The Office of Research, Innovation and Commercialization supports funded projects, industry linkages, and research ethics." },
    { title: "Quality Enhancement", icon: "check", body: "Programme review, teaching evaluation, and continuous improvement across faculties." },
    { title: "International Office", icon: "globe", body: "Guidance for collaborations, visiting scholars, and outbound student opportunities." },
    { title: "Publications & Conferences", icon: "book", body: "Faculty publications and an annual research seminar for students and staff." },
    { title: "Graduate Research Office", icon: "cap", body: "Supervision support, research training, and academic progress for MS and PhD scholars." },
];

const query = ref("");
const level = ref("");
const faculty = ref("");
const department = ref("");
const duration = ref("");
const selected = ref(null);
const facultyDept = ref("");
const facultyRole = ref("");

function academicIcon(name) {
    const n = String(name || "").toLowerCase();
    if (n.includes("comput") || n.includes("software") || n.includes("information")) return "monitor";
    if (n.includes("business") || n.includes("commerce") || n.includes("account") || n.includes("admin")) return "briefcase";
    if (n.includes("engineer") || n.includes("electrical") || n.includes("stem")) return "cog";
    if (n.includes("law")) return "scale";
    if (n.includes("health") || n.includes("medical") || n.includes("medicine")) return "heart";
    if (n.includes("educat") || n.includes("early") || n.includes("primary") || n.includes("middle") || n.includes("secondary") || n.includes("o-level") || n.includes("a-level")) return "cap";
    if (n.includes("social")) return "users";
    if (n.includes("art") || n.includes("humanit") || n.includes("design")) return "swatch";
    if (n.includes("language") || n.includes("english") || n.includes("urdu")) return "book";
    if (n.includes("math")) return "chart";
    if (n.includes("physic")) return "bolt";
    if (n.includes("chem") || n.includes("science")) return "flask";
    if (n.includes("bio")) return "leaf";
    if (n.includes("econ")) return "globe";
    if (n.includes("islam") || n.includes("pakistan")) return "star";
    if (n.includes("physical") || n.includes("sport")) return "trophy";
    if (n.includes("professional")) return "clipboard";
    return "building";
}

function withAcademicIcons(items) {
    return (Array.isArray(items) ? items : []).map((item) => ({
        ...item,
        icon: item.icon || academicIcon(item.name),
    }));
}

const programs = computed(() => cms.value.programs || programCatalog[type.value] || programCatalog.university);
const faculties = computed(() =>
    withAcademicIcons(cms.value.faculties || facultyCatalog[type.value] || facultyCatalog.university),
);
const departments = computed(() =>
    withAcademicIcons(cms.value.departments || departmentCatalog[type.value] || departmentCatalog.university),
);
const facultyMembers = computed(() => cms.value.faculty_members || facultyMembersCatalog[type.value] || facultyMembersCatalog.university);
const calendar = computed(() => [
    { label: type.value === "university" ? "Semester Start" : "Term Start", date: "01 September 2026" },
    { label: "Registration", date: "18–29 August 2026" },
    { label: "Midterm Exams", date: "09–14 November 2026" },
    { label: "Final Exams", date: "12–23 January 2027" },
    { label: "Holidays", date: "Winter break: 24 Dec 2026 – 02 Jan 2027" },
    { label: "Result Date", date: "10 February 2027" },
]);
const filters = computed(() => ({
    levels: [...new Set(programs.value.map((item) => item.level))],
    faculties: [...new Set(programs.value.map((item) => item.faculty))],
    departments: [...new Set(programs.value.map((item) => item.department))],
    durations: [...new Set(programs.value.map((item) => item.duration))],
}));
const showFaculties = computed(() => type.value !== "school");
const showResearch = computed(() => type.value === "university");

const filteredPrograms = computed(() =>
    programs.value.filter((program) => {
        const haystack = `${program.name} ${program.description}`.toLowerCase();
        return (
            (!query.value || haystack.includes(query.value.toLowerCase())) &&
            (!level.value || program.level === level.value) &&
            (!faculty.value || program.faculty === faculty.value) &&
            (!department.value || program.department === department.value) &&
            (!duration.value || program.duration === duration.value)
        );
    }),
);

const filteredFaculty = computed(() =>
    facultyMembers.value.filter((member) => {
        return (
            (!facultyDept.value || member.department === facultyDept.value) &&
            (!facultyRole.value || member.designation === facultyRole.value)
        );
    }),
);

const facultyDepartments = computed(() => [...new Set(facultyMembers.value.map((item) => item.department))]);
const facultyRoles = computed(() => [...new Set(facultyMembers.value.map((item) => item.designation))]);

watch(
    programs,
    () => {
        if (typeof window === "undefined") {
            return;
        }

        const params = new URLSearchParams(window.location.search);
        const id = params.get("program");
        if (id) {
            selected.value = programs.value.find((item) => item.id === id) || null;
        }
    },
    { immediate: true },
);
</script>

<template>
    <PublicSiteLayout>
        <Head title="Academics">
            <meta
                head-key="description"
                name="description"
                content="Explore faculties, departments, programmes, faculty members, and the academic calendar at Bright Future Educational Institute."
            />
            <link head-key="canonical" rel="canonical" :href="route('publicSite.academics')" />
        </Head>

        <PublicPageHero
            image="/images/hero-academics.jpg"
            object-position="center 45%"
            alt="Academic classrooms and teaching at Bright Future Educational Institute"
            kicker="Teaching & Learning"
            title="Academic Programmes"
            :description="overviews[type]"
            :crumbs="[{ label: 'Academics' }]"
            :facts="[
                { icon: 'book', label: 'Structured Pathways' },
                { icon: 'cap', label: 'Qualified Faculty' },
                { icon: 'flask', label: 'Laboratories' },
                { icon: 'calendar', label: 'Session 2026–27' },
            ]"
        />

        <section class="ps-section">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">{{ showFaculties ? "Faculties / Schools" : "School Sections" }}</h2>
                    <p class="ps-lead">
                        {{
                            showFaculties
                                ? "Nine academic faculties spanning computing, business, sciences, arts, engineering, and the professions."
                                : "Nine school sections guiding learners from the early years through secondary and examination pathways."
                        }}
                    </p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in faculties"
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

        <section id="departments" class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Departments</h2>
                    <p class="ps-lead">Nine academic departments delivering specialist teaching, laboratories, and student support.</p>
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
                        <p class="ps-unit-copy">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>


        <section v-if="showResearch && research.length" class="ps-section ps-section-muted">
            <div class="ps-container">
                <div class="ps-section-head" data-aos="fade-up">
                    <h2 class="ps-h2">Research, Quality & International Office</h2>
                    <p class="ps-lead">Six offices supporting research culture, quality assurance, international collaboration, and scholarly publication.</p>
                </div>
                <div class="ps-grid ps-grid-3">
                    <article
                        v-for="(item, index) in research"
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
                        <p class="ps-unit-copy">{{ item.body }}</p>
                    </article>
                </div>
            </div>
        </section>

        <PublicModal :open="Boolean(selected)" :title="selected?.name || ''" @close="selected = null">
            <p v-if="selected" class="ps-caption">{{ selected.level }} · {{ selected.duration }} · {{ selected.faculty }}</p>
            <p v-if="selected" class="ps-mt-4">{{ selected.description }}</p>
            <p v-if="selected" class="ps-mt-4"><strong>Eligibility:</strong> {{ selected.eligibility }}</p>
            <p v-if="selected?.outcomes" class="ps-mt-4">{{ selected.outcomes }}</p>
        </PublicModal>
    </PublicSiteLayout>
</template>
