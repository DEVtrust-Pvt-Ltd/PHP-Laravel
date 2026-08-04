<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { Project } from '@/Types/models';
import InputError from '@/Components/InputError.vue';
import SubmitButton from '@/Components/SubmitButton.vue';
import { projectRepository } from '@/Repositories/projectRepository';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = withDefaults(defineProps<{ projects?: Project[]; filters?: { search?: string } }>(), {
    projects: () => [], filters: () => ({}),
});
const showingCreate = ref(false);
const search = ref(props.filters.search || '');
const form = useForm({ name: '', key: '', description: '' });

const submit = () => projectRepository.create(form, { onSuccess: () => { form.reset(); showingCreate.value = false; } });
const runSearch = () => projectRepository.search({ search: search.value || undefined });
const clearSearch = () => { search.value = ''; runSearch(); };
</script>

<template>
    <Head title="Projects" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4"><div><p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Workspace</p><h2 class="mt-1 text-xl font-bold text-slate-900">Projects</h2></div><button type="button" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-700" @click="showingCreate = !showingCreate"><span class="text-lg leading-none">{{ showingCreate ? '×' : '+' }}</span>{{ showingCreate ? 'Close' : 'New project' }}</button></div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
            <form v-if="showingCreate" class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-lg shadow-indigo-100/40 sm:p-7" @submit.prevent="submit">
                <div class="mb-6"><h3 class="text-lg font-bold text-slate-900">Create a new project</h3><p class="mt-1 text-sm text-slate-500">Give your team a focused place to plan and deliver work.</p></div>
                <div class="grid gap-5 md:grid-cols-[1fr_0.55fr]">
                    <label class="grid gap-2 text-sm font-semibold text-slate-700">Project name<input v-model="form.name" required maxlength="120" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Website refresh" /><InputError :message="form.errors.name" /></label>
                    <label class="grid gap-2 text-sm font-semibold text-slate-700">Project key<input v-model="form.key" required maxlength="12" class="rounded-xl border-slate-300 text-sm uppercase focus:border-indigo-500 focus:ring-indigo-500" placeholder="WEB-101" /><InputError :message="form.errors.key" /></label>
                    <label class="grid gap-2 text-sm font-semibold text-slate-700 md:col-span-2">Description<textarea v-model="form.description" maxlength="2000" class="rounded-xl border-slate-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3" placeholder="What does success look like?" /><InputError :message="form.errors.description" /></label>
                </div>
                <div class="mt-6 flex justify-end gap-3"><button type="button" class="rounded-lg px-4 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-100" @click="showingCreate = false">Cancel</button><SubmitButton :loading="form.processing" loading-text="Creating…">Create project</SubmitButton></div>
            </form>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <form class="relative w-full sm:max-w-md" @submit.prevent="runSearch"><svg class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 20 20" fill="none"><circle cx="8.5" cy="8.5" r="5.5" stroke="currentColor" stroke-width="1.6"/><path d="m13 13 4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg><input v-model="search" class="h-11 w-full rounded-xl border-slate-200 bg-white pl-10 pr-20 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Search projects…" /><button v-if="search" type="button" class="absolute right-14 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700" aria-label="Clear search" @click="clearSearch">×</button><button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-200">Find</button></form>
                <p class="text-sm text-slate-500">{{ projects.length }} {{ projects.length === 1 ? 'project' : 'projects' }}</p>
            </div>

            <div v-if="projects.length" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <Link v-for="project in projects" :key="project.id" :href="route('projects.show', project.id)" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-indigo-50 text-xs font-bold text-indigo-700">{{ project.key.slice(0, 2) }}</span><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">{{ project.task_count }} tasks</span></div>
                    <p class="mt-5 text-xs font-bold uppercase tracking-wider text-indigo-600">{{ project.key }}</p><h3 class="mt-1 text-lg font-bold text-slate-900 transition group-hover:text-indigo-700">{{ project.name }}</h3>
                    <p class="mt-2 line-clamp-2 min-h-10 text-sm leading-5 text-slate-500">{{ project.description || 'No project description yet.' }}</p>
                    <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4"><p class="text-xs text-slate-400">Owner: <span class="font-medium text-slate-600">{{ project.owner.name }}</span></p><span class="text-indigo-500 transition group-hover:translate-x-1">→</span></div>
                </Link>
            </div>
            <div v-else class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><div class="mx-auto grid h-14 w-14 place-items-center rounded-2xl bg-indigo-50 text-2xl text-indigo-600">▦</div><h3 class="mt-4 font-bold text-slate-800">{{ filters.search ? 'No matching projects' : 'Create your first project' }}</h3><p class="mt-1 text-sm text-slate-500">{{ filters.search ? 'Try a different project name.' : 'Projects group the work your team will plan and track.' }}</p><button v-if="!filters.search" type="button" class="mt-5 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-bold text-white" @click="showingCreate = true">Create project</button><button v-else type="button" class="mt-5 text-sm font-semibold text-indigo-600" @click="clearSearch">Clear search</button></div>
        </div>
    </AuthenticatedLayout>
</template>
