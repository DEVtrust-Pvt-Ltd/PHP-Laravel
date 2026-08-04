<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import LoadingSpinner from '@/Components/LoadingSpinner.vue';
import { sessionRepository } from '@/Repositories/sessionRepository';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { onBeforeUnmount, onMounted, ref } from 'vue';

defineProps({
    canResetPassword: { type: Boolean },
    status: { type: String },
});

const showPassword = ref(false);
let checkingSession = false;
const form = useForm({ email: '', password: '', remember: false });

const redirectAuthenticatedUser = async () => {
    if (checkingSession) return;
    checkingSession = true;
    try {
        const response = await sessionRepository.status();
        if (response.ok) {
            router.visit(route('dashboard'), { replace: true });
        }
    } catch {
        // A temporary network failure should not block the login form.
    } finally {
        checkingSession = false;
    }
};
const handleVisibility = () => {
    if (document.visibilityState === 'visible') redirectAuthenticatedUser();
};

onMounted(() => {
    redirectAuthenticatedUser();
    window.addEventListener('pageshow', redirectAuthenticatedUser);
    window.addEventListener('focus', redirectAuthenticatedUser);
    document.addEventListener('visibilitychange', handleVisibility);
});
onBeforeUnmount(() => {
    window.removeEventListener('pageshow', redirectAuthenticatedUser);
    window.removeEventListener('focus', redirectAuthenticatedUser);
    document.removeEventListener('visibilitychange', handleVisibility);
});

const submit = () => {
    sessionRepository.login(form, {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign in" />

        <header class="mb-8">
            <p class="text-sm font-semibold text-indigo-600">Welcome back</p>
            <h1 class="mt-2 text-3xl font-bold tracking-[-0.03em] text-slate-900 sm:text-4xl">Sign in to your account</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Pick up where you left off and keep your team moving forward.</p>
        </header>

        <div v-if="status" role="status" class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.86-9.84a.75.75 0 0 0-1.22-.88l-3.24 4.5-1.95-1.95a.75.75 0 0 0-1.06 1.06l2.57 2.57a.75.75 0 0 0 1.14-.1l3.76-5.2Z" clip-rule="evenodd" /></svg>
            {{ status }}
        </div>

        <form class="space-y-5" @submit.prevent="submit">
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email address</label>
                <div class="group relative">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 transition group-focus-within:text-indigo-600" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6.5h16v11H4v-11Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="m5 7.5 7 5 7-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <input id="email" v-model="form.email" type="email" required autofocus autocomplete="username" placeholder="you@company.com" class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-4 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500" />
                </div>
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between gap-4">
                    <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-semibold text-indigo-600 transition hover:text-indigo-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">Forgot password?</Link>
                </div>
                <div class="group relative">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 transition group-focus-within:text-indigo-600" viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8.5 10V7.5a3.5 3.5 0 1 1 7 0V10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" placeholder="Enter your password" class="block h-12 w-full rounded-xl border-slate-200 bg-white pl-11 pr-12 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500" />
                    <button type="button" class="absolute right-3 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500" :aria-label="showPassword ? 'Hide password' : 'Show password'" @click="showPassword = !showPassword">
                        <svg v-if="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z" stroke="currentColor" stroke-width="1.7"/><circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.7"/></svg>
                        <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m4 4 16 16M9.8 6.3A9.7 9.7 0 0 1 12 6c6 0 9.5 6 9.5 6a15 15 0 0 1-2.3 3M6.6 7.6A15.8 15.8 0 0 0 2.5 12s3.5 6 9.5 6c1.1 0 2.2-.2 3.1-.5M10.3 10.3a2.5 2.5 0 0 0 3.4 3.4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    </button>
                </div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <label class="flex w-fit cursor-pointer items-center gap-3 text-sm text-slate-600">
                <input v-model="form.remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                Keep me signed in
            </label>

            <button type="submit" :disabled="form.processing" class="flex h-12 w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:translate-y-0 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                <LoadingSpinner v-if="form.processing" label="Signing in" />
                {{ form.processing ? 'Signing in…' : 'Sign in to WorkBoard' }}
                <svg v-if="!form.processing" class="h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M4 10h12m0 0-4-4m4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </form>

        <p class="mt-8 text-center text-sm text-slate-500">
            New to WorkBoard?
            <Link :href="route('register')" class="ml-1 font-bold text-indigo-600 transition hover:text-indigo-800">Create an account</Link>
        </p>
    </GuestLayout>
</template>
