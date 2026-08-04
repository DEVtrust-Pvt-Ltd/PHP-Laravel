<script setup>
import LoadingSpinner from '@/Components/LoadingSpinner.vue';

defineProps({
    loading: { type: Boolean, default: false },
    loadingText: { type: String, default: 'Deleting…' },
});
</script>

<template>
    <button
        :disabled="loading || $attrs.disabled"
        :aria-busy="loading"
        class="inline-flex items-center justify-center gap-2 rounded-md border border-transparent bg-red-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
    >
        <template v-if="loading">
            <LoadingSpinner label="Deleting" />
            <span>{{ loadingText }}</span>
        </template>
        <slot v-else />
    </button>
</template>
