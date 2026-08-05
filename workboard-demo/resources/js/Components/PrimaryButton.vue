<script setup>
import LoadingSpinner from '@/Components/LoadingSpinner.vue';

defineProps({
    loading: { type: Boolean, default: false },
    loadingText: { type: String, default: 'Saving…' },
});
</script>

<template>
    <button
        :disabled="loading || $attrs.disabled"
        :aria-busy="loading"
        class="inline-flex items-center justify-center gap-2 rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 disabled:cursor-not-allowed disabled:opacity-60"
    >
        <template v-if="loading">
            <LoadingSpinner label="Submitting" />
            <span>{{ loadingText }}</span>
        </template>
        <slot v-else />
    </button>
</template>
