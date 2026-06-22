<script setup>
import { ref, computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    permissionGroups: Object,
    role: Object, // Received when editing
    rolePermissions: Array, // IDs received when editing
});

const isEditing = computed(() => !!props.role);

const form = useForm({
    // Populate form if editing, otherwise empty
    name: props.role?.name ?? "",
    permissions: props.rolePermissions ?? [],
});

const toggleGroup = (groupPermissions, event) => {
    const groupIds = groupPermissions.map((p) => p.id);

    if (event.target.checked) {
        // Add all IDs from this group to form.permissions, avoiding duplicates
        const uniqueIds = groupIds.filter(
            (id) => !form.permissions.includes(id),
        );
        form.permissions.push(...uniqueIds);
    } else {
        // Remove all IDs from this group from form.permissions
        form.permissions = form.permissions.filter(
            (id) => !groupIds.includes(id),
        );
    }
};

// Helper function to check if the group is fully selected
const isGroupFull = (groupPermissions) => {
    return (
        groupPermissions.length > 0 &&
        groupPermissions.every((p) => form.permissions.includes(p.id))
    );
};

const submit = () => {
    if (isEditing.value) {
        form.put(route("roles.update", props.role.id));
    } else {
        form.post(route("roles.store"));
    }
};
</script>

<template>
    <Head :title="isEditing ? 'Edit Role' : 'Create Role'" />

    <AuthenticatedLayout>
        <!-- Header -->
        <div
            class="max-w-8xl mx-auto mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4"
        >
            <div>
                <h2 class="text-2xl font-black text-slate-600 tracking-tight">
                    {{
                        isEditing ? "Edit Role Authority" : "Create System Role"
                    }}
                </h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Configure access levels for Users and Roles.
                </p>
            </div>
            <Link
                :href="route('roles.index')"
                class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-600 transition-colors"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"
                        stroke-width="2.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                Back to Role List
            </Link>
        </div>

        <form
            @submit.prevent="submit"
            class="max-w-8xl mx-auto pb-24 space-y-8"
        >
            <!-- Role Identity Card -->
            <div
                class="bg-white rounded-lg border border-slate-200 shadow-sm p-8"
            >
                <div class="max-w-md">
                    <InputLabel
                        for="name"
                        value="Role Name"
                        class="mb-2 text-[14px] uppercase tracking-widest text-slate-600 font-bold"
                    />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="block w-full px-4 py-3 bg-slate-200 border-slate-200 rounded-xl focus:bg-white text-slate-600 font-semibold"
                        :placeholder="
                            isEditing
                                ? 'Update Role Name...'
                                : 'Create New Role ...'
                        "
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
            </div>

            <!-- Permissions Grid: Using 2-Column for 2 Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div
                    v-for="(permissions, groupName) in permissionGroups"
                    :key="groupName"
                    class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col"
                >
                    <!-- Card Header -->
                    <div
                        class="px-6 py-4 bg-slate-400 border-b border-slate-100 flex items-center justify-between"
                    >
                        <h3
                            class="text-[13px] font-black text-white uppercase tracking-wider"
                        >
                            {{ groupName }}
                        </h3>

                        <label
                            class="inline-flex items-center cursor-pointer group"
                        >
                            <input
                                type="checkbox"
                                @change="toggleGroup(permissions, $event)"
                                :checked="isGroupFull(permissions)"
                                class="rounded border-slate-300 text-slate-600 focus:ring-slate-500 h-4 w-4 transition-all"
                            />
                            <span
                                class="ml-2 text-[12px] font-bold text-white uppercase"
                                >Toggle All</span
                            >
                        </label>
                    </div>

                    <!-- Card Body -->
                    <div
                        class="p-3 grid grid-cols-1 lg:grid-cols-2 gap-x-4 gap-y-2"
                    >
                        <div
                            v-for="permission in permissions"
                            :key="permission.id"
                            class="flex items-center p-3 rounded-xl border border-transparent hover:border-slate-100 hover:bg-slate-50/80 transition-all group"
                        >
                            <input
                                :id="'perm-' + permission.id"
                                type="checkbox"
                                v-model="form.permissions"
                                :value="permission.id"
                                class="rounded border-slate-300 text-slate-600 focus:ring-slate-500 h-5 w-5 cursor-pointer transition-all"
                            />
                            <label
                                :for="'perm-' + permission.id"
                                class="ml-3 cursor-pointer flex-1"
                            >
                                <span
                                    class="block text-[13px] font-bold text-slate-700 capitalize tracking-tight group-hover:text-slate-900 transition-colors"
                                >
                                    {{ permission.name }}
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="flex items-center justify-center pt-4">
                <PrimaryButton
                    class="px-14 py-4 bg-slate-600 hover:bg-slate-700 text-white rounded-full shadow-xl transition-all font-black text-xs uppercase tracking-widest active:scale-95"
                    :disabled="form.processing"
                >
                    {{ isEditing ? "Save Changes" : "Create Role" }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
