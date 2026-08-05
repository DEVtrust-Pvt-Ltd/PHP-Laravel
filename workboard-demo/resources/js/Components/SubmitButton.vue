<script setup>
import LoadingSpinner from '@/Components/LoadingSpinner.vue';

const props = defineProps({
    loading: { type: Boolean, default: false },
    loadingText: { type: String, default: 'Please wait…' },
    variant: { type: String, default: 'primary' },
});

const variants = {
    primary: 'border-transparent bg-indigo-600 text-white shadow-sm hover:bg-indigo-700 focus-visible:ring-indigo-500',
    outline: 'border-indigo-600 bg-white text-indigo-700 hover:bg-indigo-50 focus-visible:ring-indigo-500',
    danger: 'border-transparent bg-red-600 text-white shadow-sm hover:bg-red-700 focus-visible:ring-red-500',
};
</script>

<template>
    <button
        type="submit"
        :disabled="loading || $attrs.disabled"
        :aria-busy="loading"
        class="inline-flex min-h-10 items-center justify-center gap-2 rounded-lg border px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60"
        :class="variants[props.variant] || variants.primary"
    >
        <template v-if="loading">
            <LoadingSpinner label="Submitting" />
            <span>{{ loadingText }}</span>
        </template>
        <slot v-else />
    </button>
</template>
