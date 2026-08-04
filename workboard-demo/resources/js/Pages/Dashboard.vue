<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import type { PageProps, Task } from '@/Types/models';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Summary { projects: number; todo: number; in_progress: number; done: number; overdue: number }
interface RecentProject { id: number; name: string; key: string; task_count: number; updated_at: string | null }
const props = withDefaults(defineProps<{
    summary: Summary;
    dueSoon: Task[];
    recentProjects?: RecentProject[];
}>(), { recentProjects: () => [] });

const user = usePage<PageProps>().props.auth.user!;
const totalTasks = computed(() => props.summary.todo + props.summary.in_progress + props.summary.done);
const completion = computed(() => totalTasks.value ? Math.round((props.summary.done / totalTasks.value) * 100) : 0);
const greeting = computed(() => {
    const hour = new Date().getHours();
    return hour < 12 ? 'Good morning' : hour < 18 ? 'Good afternoon' : 'Good evening';
});
const formatDate = (date: string | null) => date
    ? new Intl.DateTimeFormat(undefined, { month: 'short', day: 'numeric' }).format(new Date(date + 'T00:00:00'))
    : 'No date';
</script>

<template>
    <div class="contents">
        <Head title="Dashboard" />
        <AuthenticatedLayout>
        <template #header>
            <div><p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Overview</p><h2 class="mt-1 text-xl font-bold text-slate-900">Dashboard</h2></div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 py-7 sm:px-6 lg:px-8">
            <section class="relative overflow-hidden rounded-3xl bg-slate-900 px-6 py-8 text-white shadow-xl shadow-slate-200 sm:px-9 sm:py-10">
                <div class="absolute -right-20 -top-32 h-80 w-80 rounded-full bg-indigo-500/25 blur-3xl"></div>
                <div class="relative flex flex-col justify-between gap-8 md:flex-row md:items-end">
                    <div>
                        <p class="text-sm font-semibold text-indigo-300">{{ greeting }}, {{ user.name.split(' ')[0] }}</p>
                        <h1 class="mt-2 max-w-2xl text-3xl font-bold tracking-tight sm:text-4xl">Let’s make today count.</h1>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">You have {{ summary.in_progress }} active tasks and {{ summary.overdue }} overdue across your workspace.</p>
                    </div>
                    <Link :href="route('projects.index')" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-bold text-slate-900 transition hover:bg-indigo-50">Open projects <span>→</span></Link>
                </div>
            </section>

            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm font-medium text-slate-500">Projects</p><span class="grid h-9 w-9 place-items-center rounded-xl bg-indigo-50 text-indigo-600">▦</span></div><p class="mt-4 text-3xl font-bold text-slate-900">{{ summary.projects }}</p><p class="mt-1 text-xs text-slate-400">Visible workspaces</p></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm font-medium text-slate-500">In progress</p><span class="grid h-9 w-9 place-items-center rounded-xl bg-amber-50 text-amber-600">↻</span></div><p class="mt-4 text-3xl font-bold text-slate-900">{{ summary.in_progress }}</p><p class="mt-1 text-xs text-slate-400">Currently active</p></article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm font-medium text-slate-500">Completed</p><span class="grid h-9 w-9 place-items-center rounded-xl bg-emerald-50 text-emerald-600">✓</span></div><p class="mt-4 text-3xl font-bold text-slate-900">{{ summary.done }}</p><p class="mt-1 text-xs text-slate-400">{{ completion }}% completion rate</p></article>
                <article class="rounded-2xl border p-5 shadow-sm" :class="summary.overdue ? 'border-rose-200 bg-rose-50/60' : 'border-slate-200 bg-white'"><div class="flex items-center justify-between"><p class="text-sm font-medium text-slate-500">Overdue</p><span class="grid h-9 w-9 place-items-center rounded-xl bg-rose-100 text-rose-600">!</span></div><p class="mt-4 text-3xl font-bold" :class="summary.overdue ? 'text-rose-700' : 'text-slate-900'">{{ summary.overdue }}</p><p class="mt-1 text-xs text-slate-400">Needs attention</p></article>
            </section>

            <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6"><div><h3 class="font-bold text-slate-900">Upcoming deadlines</h3><p class="mt-0.5 text-xs text-slate-400">Due in the next 7 days</p></div><Link :href="route('projects.index')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">View all</Link></div>
                    <div v-if="dueSoon.length" class="divide-y divide-slate-100">
                        <Link v-for="task in dueSoon" :key="task.id" :href="route('projects.show', task.project_id)" class="flex items-center gap-4 px-5 py-4 transition hover:bg-slate-50 sm:px-6">
                            <span class="h-2.5 w-2.5 shrink-0 rounded-full" :class="task.priority === 'high' ? 'bg-rose-500' : task.priority === 'medium' ? 'bg-amber-400' : 'bg-slate-300'"></span>
                            <div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">{{ task.title }}</p><p class="mt-0.5 truncate text-xs text-slate-400">{{ task.assignee?.name || 'Unassigned' }}</p></div>
                            <time class="shrink-0 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-600">{{ formatDate(task.due_date) }}</time>
                        </Link>
                    </div>
                    <div v-else class="px-6 py-14 text-center"><div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-emerald-50 text-xl text-emerald-600">✓</div><p class="mt-3 text-sm font-semibold text-slate-700">All clear</p><p class="mt-1 text-xs text-slate-400">Nothing is due this week.</p></div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-5 py-4"><h3 class="font-bold text-slate-900">Recent projects</h3><p class="mt-0.5 text-xs text-slate-400">Your latest workspaces</p></div>
                    <div v-if="recentProjects.length" class="divide-y divide-slate-100">
                        <Link v-for="project in recentProjects" :key="project.id" :href="route('projects.show', project.id)" class="flex items-center gap-3 px-5 py-4 transition hover:bg-slate-50">
                            <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-indigo-50 text-xs font-bold text-indigo-700">{{ project?.key?.slice(0, 2) }}</span>
                            <div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-800">{{ project.name }}</p><p class="mt-0.5 text-xs text-slate-400">{{ project.task_count }} tasks · {{ project.key }}</p></div><span class="text-slate-300">›</span>
                        </Link>
                    </div>
                    <div v-else class="px-5 py-12 text-center"><p class="text-sm text-slate-500">No projects yet.</p><Link :href="route('projects.index')" class="mt-2 inline-block text-sm font-semibold text-indigo-600">Create your first project</Link></div>
                </section>
            </div>
        </div>
        </AuthenticatedLayout>
    </div>
</template>
