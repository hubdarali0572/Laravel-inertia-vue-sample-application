<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section
        class="theme-form-card overflow-hidden border-rose-200 dark:border-rose-500/30"
    >
        <div
            class="flex flex-col gap-2 border-b border-rose-200 bg-gradient-to-r from-rose-50 to-rose-100/50 px-6 py-4 dark:border-rose-500/30 dark:from-rose-500/10 dark:to-rose-500/5"
        >
            <h2
                class="text-xs font-semibold uppercase tracking-wider text-rose-700 dark:text-rose-300"
            >
                Danger Zone
            </h2>
            <p class="text-xs text-rose-600/80 dark:text-rose-400/80">
                Permanently remove your account and all associated data.
            </p>
        </div>

        <div class="p-6 md:p-8">
            <div
                class="flex flex-col gap-5 rounded-xl border border-rose-200 bg-rose-50/50 p-5 dark:border-rose-500/30 dark:bg-rose-500/5 sm:flex-row sm:items-center"
            >
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-600 dark:bg-rose-500/20 dark:text-rose-400"
                >
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                        />
                    </svg>
                </div>

                <div class="min-w-0 flex-1">
                    <h3
                        class="text-sm font-bold text-slate-900 dark:text-slate-100"
                    >
                        Delete Account
                    </h3>
                    <p
                        class="mt-1 text-xs leading-relaxed text-slate-600 dark:text-slate-400"
                    >
                        Once deleted, all resources and data will be permanently
                        removed. This action cannot be undone.
                    </p>
                </div>

                <button
                    type="button"
                    @click="confirmUserDeletion"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-rose-600/20 transition-colors hover:bg-rose-700 active:scale-[0.98] dark:focus:ring-offset-slate-900"
                >
                    Delete Account
                </button>
            </div>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div
                class="bg-white p-6 dark:bg-slate-800"
            >
                <h2
                    class="text-lg font-bold text-slate-900 dark:text-slate-100"
                >
                    Confirm account deletion
                </h2>

                <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                    Please enter your password to permanently delete your
                    account and all associated data.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Enter your password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="closeModal"
                        class="theme-btn-secondary"
                    >
                        Cancel
                    </button>

                    <button
                        type="button"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="deleteUser"
                    >
                        Delete Account
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>
