<script setup>
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const visible = ref(false);
let timeout;
const message = computed(() => page.props.flash?.success || page.props.flash?.error);
const type = computed(() => page.props.flash?.error ? 'error' : 'success');

watch(message, (value) => {
    clearTimeout(timeout);
    visible.value = Boolean(value);
    if (value) timeout = setTimeout(() => { visible.value = false; }, 4500);
}, { immediate: true });
</script>

<template>
    <Transition enter-active-class="transition duration-200" enter-from-class="translate-y-2 opacity-0" leave-active-class="transition duration-150" leave-to-class="translate-y-2 opacity-0">
        <div v-if="visible && message" role="status" class="fixed bottom-5 right-5 z-[100] flex max-w-sm items-start gap-3 rounded-xl border bg-white px-4 py-3 shadow-xl" :class="type === 'error' ? 'border-rose-200 text-rose-800' : 'border-emerald-200 text-emerald-800'">
            <span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full text-xs text-white" :class="type === 'error' ? 'bg-rose-500' : 'bg-emerald-500'">{{ type === 'error' ? '!' : '✓' }}</span>
            <p class="text-sm font-medium">{{ message }}</p>
            <button type="button" class="ml-2 text-slate-400 hover:text-slate-700" aria-label="Dismiss notification" @click="visible = false">×</button>
        </div>
    </Transition>
</template>
