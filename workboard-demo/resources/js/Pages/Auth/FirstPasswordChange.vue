<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({ current_password: '', password: '', password_confirmation: '' });
const submit = () => form.put(route('password.first-change.update'), {
    onSuccess: () => form.reset(),
    onFinish: () => form.reset('current_password'),
});
</script>

<template>
    <GuestLayout>
        <Head title="Set your password" />
        <div class="mb-7">
            <span class="grid h-12 w-12 place-items-center rounded-2xl bg-indigo-50 text-xl text-indigo-600">🔐</span>
            <p class="mt-5 text-sm font-semibold text-indigo-600">First-time setup</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Create your own password</h1>
            <p class="mt-3 text-sm leading-6 text-slate-500">Your account was created by a project owner. Replace the temporary password before entering WorkBoard.</p>
        </div>

        <form class="space-y-4" @submit.prevent="submit">
            <label class="grid gap-2 text-sm font-semibold text-slate-700">Temporary password<input v-model="form.current_password" required autofocus type="password" autocomplete="current-password" class="h-12 rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" /></label>
            <InputError :message="form.errors.current_password" />
            <label class="grid gap-2 text-sm font-semibold text-slate-700">New password<input v-model="form.password" required type="password" autocomplete="new-password" class="h-12 rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" /></label>
            <InputError :message="form.errors.password" />
            <label class="grid gap-2 text-sm font-semibold text-slate-700">Confirm new password<input v-model="form.password_confirmation" required type="password" autocomplete="new-password" class="h-12 rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" /></label>
            <SubmitButton class="mt-2 w-full" :loading="form.processing" loading-text="Setting password…">Set password and continue</SubmitButton>
        </form>

        <Link :href="route('logout')" method="post" as="button" class="mt-6 w-full text-center text-sm font-semibold text-slate-500 hover:text-slate-800">Sign out</Link>
    </GuestLayout>
</template>
