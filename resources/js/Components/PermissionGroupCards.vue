<script setup>
import { useI18n } from "@/composables/useI18n";

defineProps({
    groups: {
        type: Object,
        required: true,
    },
    modelValue: {
        type: Array,
        required: true,
    },
    embedded: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);
const { t } = useI18n();

const permissionLabel = (name) => {
    const action = (name || "").split(" ")[0];
    const translated = t(`permissions.${action}`);

    return translated.startsWith("permissions.") ? action : translated;
};

const selectedIds = (value) => value.map((id) => Number(id));

const isChecked = (ids, permissionId) =>
    selectedIds(ids).includes(Number(permissionId));

const selectedCount = (ids, permissions) =>
    permissions.filter((permission) => isChecked(ids, permission.id)).length;

const isGroupFull = (ids, permissions) =>
    permissions.length > 0 &&
    permissions.every((permission) => isChecked(ids, permission.id));

const togglePermission = (ids, permissionId, checked) => {
    const current = selectedIds(ids);
    const id = Number(permissionId);

    if (checked) {
        if (!current.includes(id)) {
            emit("update:modelValue", [...current, id]);
        }
        return;
    }

    emit(
        "update:modelValue",
        current.filter((value) => value !== id),
    );
};

const toggleGroup = (ids, permissions, checked) => {
    const current = selectedIds(ids);
    const groupIds = permissions.map((permission) => Number(permission.id));

    if (checked) {
        emit("update:modelValue", [...new Set([...current, ...groupIds])]);
        return;
    }

    emit(
        "update:modelValue",
        current.filter((id) => !groupIds.includes(id)),
    );
};
</script>

<template>
    <div :class="embedded ? '' : 'theme-form-card'">
        <div class="border border-slate-200 dark:border-slate-700 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-indigo-50/50 dark:bg-indigo-900/10 border-b border-slate-200 dark:border-slate-700">
                            <th class="py-3 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider dark:text-slate-400">
                            {{ t('roles.modules') || 'MODULES' }}
                            </th>
                            <th class="py-3 px-4 text-xs font-semibold text-indigo-600 uppercase tracking-wider text-right dark:text-indigo-400">
                                {{ t('roles.permissions') || 'PERMISSIONS' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700 bg-white dark:bg-slate-900">
                        <tr
                            v-for="(permissions, groupName) in groups"
                            :key="groupName"
                            class="hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors"
                        >
                            <td class="py-3 px-4 align-middle whitespace-nowrap">
                                <span class="font-semibold text-sm text-slate-700 dark:text-slate-200 capitalize">
                                    {{ t(groupName) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 align-middle text-right">
                                <div class="flex flex-wrap justify-end gap-2 items-center">
                                    <!-- All Checkbox -->
                                    <label
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 border rounded-md text-sm font-semibold cursor-pointer transition-colors border-indigo-200 bg-indigo-50/30 text-indigo-600 hover:bg-indigo-50 dark:border-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400 dark:hover:bg-indigo-900/40"
                                        :class="{ 'ring-1 ring-indigo-600 bg-indigo-50 dark:bg-indigo-900/50': isGroupFull(modelValue, permissions) }"
                                    >
                                        <span class="uppercase text-xs tracking-wider">{{ t('common.all') || 'ALL' }}</span>
                                        <input
                                            type="checkbox"
                                            class="theme-form-checkbox h-3.5 w-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 dark:border-slate-600 dark:bg-slate-900 dark:ring-offset-slate-800"
                                            :checked="isGroupFull(modelValue, permissions)"
                                            @change="toggleGroup(modelValue, permissions, $event.target.checked)"
                                        />
                                    </label>

                                    <!-- Individual Permissions Checkboxes -->
                                    <label
                                        v-for="permission in permissions"
                                        :key="permission.id"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 border rounded-md text-sm font-medium cursor-pointer transition-colors border-slate-200 bg-white text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700/50"
                                        :class="{ 'border-indigo-300 bg-indigo-50 text-indigo-700 dark:border-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300': isChecked(modelValue, permission.id) }"
                                    >
                                        <span class="uppercase text-xs tracking-wider">{{ permissionLabel(permission.name) }}</span>
                                        <input
                                            type="checkbox"
                                            class="theme-form-checkbox h-3.5 w-3.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 dark:border-slate-600 dark:bg-slate-900 dark:ring-offset-slate-800"
                                            :checked="isChecked(modelValue, permission.id)"
                                            @change="togglePermission(modelValue, permission.id, $event.target.checked)"
                                        />
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
