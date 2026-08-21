<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="theme-form-error">
            {{ message }}
        </p>
    </div>
</template>
