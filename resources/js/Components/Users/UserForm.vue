<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { useI18n } from "@/composables/useI18n";

const props = defineProps({
    roles: {
        type: Array,
        default: () => [],
    },
    user: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["cancel"]);

const { t } = useI18n();

const form = useForm({
    _method: "post",
    name: "",
    email: "",
    role_id: "",
    password: "",
    password_confirmation: "",
    image: null,
});

const imagePreview = ref(null);
const imageInput = ref(null);
const showPassword = ref(false);
const isDropdownOpen = ref(false);
const searchRole = ref("");
const dropdownRef = ref(null);

const isEditing = computed(() => !!props.user);

const filteredRoles = computed(() =>
    props.roles.filter((role) =>
        role.name.toLowerCase().includes(searchRole.value.toLowerCase()),
    ),
);

const selectedRoleName = computed(() => {
    const role = props.roles.find((item) => item.id === form.role_id);
    return role ? role.name.toUpperCase() : t("common.select_role");
});

const fillForm = (user) => {
    form.clearErrors();
    form._method = "put";
    form.name = user?.name ?? "";
    form.email = user?.email ?? "";
    form.role_id = user?.role_id ?? user?.role?.id ?? "";
    form.password = "";
    form.password_confirmation = "";
    form.image = null;
    imagePreview.value = user?.profile_image ?? null;

    if (imageInput.value) {
        imageInput.value.value = "";
    }
};

const resetForm = () => {
    form.clearErrors();
    form._method = "post";
    form.name = "";
    form.email = "";
    form.role_id = "";
    form.password = "";
    form.password_confirmation = "";
    form.image = null;
    imagePreview.value = null;

    if (imageInput.value) {
        imageInput.value.value = "";
    }
};

watch(
    () => props.user,
    (user) => {
        if (user) {
            fillForm(user);
            return;
        }

        resetForm();
    },
    { immediate: true },
);

const selectRole = (role) => {
    form.role_id = role.id;
    isDropdownOpen.value = false;
    searchRole.value = "";
};

const handleImageChange = (event) => {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    form.image = file;

    const reader = new FileReader();
    reader.onload = (loadEvent) => {
        imagePreview.value = loadEvent.target.result;
    };
    reader.readAsDataURL(file);
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isDropdownOpen.value = false;
    }
};

const cancelEdit = () => {
    resetForm();
    emit("cancel");
};

const submit = () => {
    const options = {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            if (isEditing.value) {
                emit("cancel");
            } else {
                resetForm();
            }
        },
        onFinish: () => form.reset("password", "password_confirmation", "image"),
    };

    if (isEditing.value) {
        form.post(route("users.update", props.user.id), options);
        return;
    }

    form.post(route("users.store"), options);
};

onMounted(() => document.addEventListener("click", handleClickOutside));
onUnmounted(() => document.removeEventListener("click", handleClickOutside));
</script>

<template>
    <div class="theme-form-card theme-split-form-card">
        <form class="theme-form-body space-y-1" @submit.prevent="submit">
            <div class="flex flex-col">
                <InputLabel
                    for="user-form-name"
                    :value="t('users.full_name')"
                    class="theme-form-label"
                />
                <TextInput
                    id="user-form-name"
                    v-model="form.name"
                    type="text"
                    class="theme-form-input"
                    required
                    :placeholder="t('users.placeholder_name')"
                />
                <InputError :message="form.errors.name" class="mt-1.5" />
            </div>

            <div class="flex flex-col">
                <InputLabel
                    for="user-form-email"
                    :value="t('users.email')"
                    class="theme-form-label"
                />
                <TextInput
                    id="user-form-email"
                    v-model="form.email"
                    type="email"
                    class="theme-form-input"
                    required
                    :placeholder="t('users.placeholder_email')"
                />
                <InputError :message="form.errors.email" class="mt-1.5" />
            </div>

            <div ref="dropdownRef" class="relative flex flex-col">
                <InputLabel
                    for="user-form-role"
                    :value="t('users.role_label')"
                    class="theme-form-label"
                />
                <div
                    class="theme-form-select"
                    :class="{ 'theme-form-select-open': isDropdownOpen }"
                    @click="isDropdownOpen = !isDropdownOpen"
                >
                    <span
                        :class="
                            form.role_id
                                ? 'theme-heading text-sm font-semibold'
                                : 'theme-text-muted text-sm font-medium'
                        "
                    >
                        {{ selectedRoleName }}
                    </span>
                    <svg
                        class="h-4 w-4 text-slate-400 transition-transform duration-200"
                        :class="{ 'rotate-180': isDropdownOpen }"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M19 9l-7 7-7-7"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <transition name="pop">
                    <div
                        v-if="isDropdownOpen"
                        class="theme-dropdown absolute z-50 mt-1 w-full"
                    >
                        <div class="theme-dropdown-search">
                            <input
                                v-model="searchRole"
                                type="text"
                                class="theme-form-input py-2.5 text-sm"
                                :placeholder="t('common.search_role')"
                                @click.stop
                            />
                        </div>
                        <ul class="max-h-44 overflow-y-auto py-2">
                            <li
                                v-for="role in filteredRoles"
                                :key="role.id"
                                class="theme-dropdown-option"
                                :class="{ 'is-selected': form.role_id === role.id }"
                                @click="selectRole(role)"
                            >
                                {{ role.name.toUpperCase() }}
                                <svg
                                    v-if="form.role_id === role.id"
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </li>
                            <li
                                v-if="filteredRoles.length === 0"
                                class="px-5 py-6 text-center text-xs text-slate-400"
                            >
                                {{ t("common.no_roles_found") }}
                            </li>
                        </ul>
                    </div>
                </transition>
                <InputError :message="form.errors.role_id" class="mt-1.5" />
            </div>

            <div class="flex flex-col">
                <InputLabel
                    for="user-form-password"
                    :value="t('users.password')"
                    class="theme-form-label"
                />
                <div class="relative">
                    <TextInput
                        id="user-form-password"
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="theme-form-input pr-12"
                        :required="!isEditing"
                        :placeholder="t('users.placeholder_password')"
                    />
                    <button
                        type="button"
                        class="theme-text-muted absolute inset-y-0 end-0 flex items-center pe-4 transition-colors hover:text-[var(--color-heading)]"
                        @click="showPassword = !showPassword"
                    >
                        <svg
                            v-if="!showPassword"
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.483 8.613 8.242 4.5 12 4.5c3.758 0 8.517 4.113 9.964 7.178.07.147.07.315 0 .462-1.447 3.065-4.206 7.178-9.964 7.178-3.758 0-8.517-4.113-9.964-7.178Z"
                            />
                            <path
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                            />
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                        >
                            <path
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21"
                            />
                        </svg>
                    </button>
                </div>
                <p
                    v-if="isEditing"
                    class="theme-text-muted mt-1.5 text-[10px] font-bold italic"
                >
                    {{ t("users.password_keep") }}
                </p>
                <InputError :message="form.errors.password" class="mt-1.5" />
            </div>

            <div class="flex flex-col">
                <InputLabel
                    for="user-form-password-confirmation"
                    :value="t('users.password_confirm')"
                    class="theme-form-label"
                />
                <TextInput
                    id="user-form-password-confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="theme-form-input"
                    :required="!isEditing && !!form.password"
                    :placeholder="t('users.placeholder_password_confirm')"
                />
                <InputError
                    :message="form.errors.password_confirmation"
                    class="mt-1.5"
                />
            </div>

            <div class="flex flex-col">
                <InputLabel
                    for="user-form-image"
                    :value="t('users.photo')"
                    class="theme-form-label"
                />
                <div
                    class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3 dark:border-slate-700 dark:bg-slate-900/60"
                >
                    <div
                        class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-white bg-slate-200 dark:border-slate-600 dark:bg-slate-700"
                    >
                        <img
                            v-if="imagePreview"
                            :src="imagePreview"
                            alt=""
                            class="h-full w-full object-cover"
                        />
                        <svg
                            v-else
                            class="h-7 w-7 text-slate-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.5"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                            />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <input
                            ref="imageInput"
                            type="file"
                            class="hidden"
                            accept="image/*"
                            @change="handleImageChange"
                        />
                        <button
                            type="button"
                            class="theme-btn-secondary w-full justify-center text-xs"
                            @click="imageInput?.click()"
                        >
                            {{ t("users.select_photo") }}
                        </button>
                    </div>
                </div>
                <InputError :message="form.errors.image" class="mt-1.5" />
            </div>

            <div
                class="flex flex-wrap justify-end gap-2 border-t border-slate-200 pt-4 dark:border-slate-700"
            >
                <button
                    v-if="isEditing"
                    type="button"
                    class="theme-btn-secondary"
                    @click="cancelEdit"
                >
                    {{ t("common.cancel") }}
                </button>
                <PrimaryButton :disabled="form.processing" class="w-full sm:w-auto">
                    {{
                        isEditing
                            ? t("users.update_button")
                            : t("users.create_button")
                    }}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<style scoped>
.pop-enter-active {
    transition: all 0.2s ease-out;
}

.pop-leave-active {
    transition: all 0.1s ease-in;
}

.pop-enter-from,
.pop-leave-to {
    opacity: 0;
    transform: translateY(-8px) scale(0.98);
}
</style>
