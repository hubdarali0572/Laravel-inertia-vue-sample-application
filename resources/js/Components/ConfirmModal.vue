<script setup>
import { onMounted, onUnmounted, watch } from "vue";
import { useI18n } from "@/composables/useI18n";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        required: true,
    },
    message: {
        type: String,
        required: true,
    },
    confirmLabel: {
        type: String,
        default: 'Confirm',
    },
    cancelLabel: {
        type: String,
        default: 'Cancel',
    },
    badge: {
        type: String,
        default: null,
    },
    badgeInitial: {
        type: String,
        default: null,
    },
    icon: {
        type: String,
        default: 'delete',
        validator: (value) => ['delete', 'warning', 'logout'].includes(value),
    },
    warning: {
        type: [String, Boolean],
        default: true,
    },
    loading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close', 'confirm']);
const { t } = useI18n();

const handleKeydown = (event) => {
    if (event.key === 'Escape' && props.show) {
        emit('close');
    }
};

watch(
    () => props.show,
    (isOpen) => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    },
);

onMounted(() => {
    document.addEventListener('keydown', handleKeydown);
    if (props.show) {
        document.body.style.overflow = 'hidden';
    }
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
    document.body.style.overflow = '';
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="theme-modal-overlay">
                <div class="theme-modal-backdrop" @click="emit('close')"></div>

                <Transition
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div v-if="show" class="theme-modal-panel" role="dialog" aria-modal="true">
                        <!-- Header -->
                        <div class="theme-modal-header">
                            <h3 class="theme-modal-title">{{ title }}</h3>
                            <button
                                type="button"
                                class="theme-modal-close"
                                :aria-label="t('common.close')"
                                @click="emit('close')"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="theme-modal-body">
                            <div
                                class="theme-modal-icon"
                                :class="icon === 'warning' ? 'theme-modal-icon-warning' : (icon === 'logout' ? 'theme-modal-icon-logout' : 'theme-modal-icon-danger')"
                            >
                                <svg v-if="icon === 'delete'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <svg v-else-if="icon === 'logout'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>

                            <p class="theme-modal-message">{{ message }}</p>

                            <div v-if="badge" class="theme-modal-badge">
                                <div
                                    v-if="badgeInitial"
                                    class="theme-avatar theme-avatar-sm me-2 uppercase"
                                >
                                    {{ badgeInitial }}
                                </div>
                                <span class="theme-heading truncate text-xs font-semibold">{{ badge }}</span>
                            </div>

                            <p v-if="warning" class="theme-modal-warning">
                                {{ typeof warning === 'string' ? warning : t('common.irreversible') }}
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="theme-modal-footer">
                            <button
                                type="button"
                                class="theme-modal-btn-cancel"
                                :disabled="loading"
                                @click="emit('close')"
                            >
                                {{ cancelLabel }}
                            </button>
                            <button
                                type="button"
                                class="theme-modal-btn-danger"
                                :disabled="loading"
                                @click="emit('confirm')"
                            >
                                <svg
                                    v-if="loading"
                                    class="me-1.5 h-3.5 w-3.5 animate-spin"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>{{ confirmLabel }}</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
