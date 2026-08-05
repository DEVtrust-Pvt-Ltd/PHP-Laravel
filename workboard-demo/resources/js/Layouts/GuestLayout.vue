<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <main class="relative min-h-screen overflow-hidden bg-[#f6f7fb] text-slate-900">
        <div class="pointer-events-none absolute inset-0 opacity-60 auth-grid"></div>

        <div class="relative mx-auto grid min-h-screen max-w-[1600px] lg:grid-cols-[1.05fr_0.95fr]">
            <section class="relative hidden overflow-hidden bg-[#111827] px-12 py-12 text-white lg:flex lg:flex-col xl:px-20 xl:py-16">
                <div class="pointer-events-none absolute -left-40 -top-40 h-[32rem] w-[32rem] rounded-full bg-indigo-500/20 blur-3xl"></div>
                <div class="pointer-events-none absolute -bottom-32 -right-20 h-[28rem] w-[28rem] rounded-full bg-cyan-400/10 blur-3xl"></div>

                <Link href="/" class="relative flex w-fit items-center gap-3 rounded-xl focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-indigo-500 shadow-lg shadow-indigo-950/40">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M5 7.5 9 12l-4 4.5M11.5 7.5l4 4.5-4 4.5M18 7.5 22 12l-4 4.5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="text-xl font-bold tracking-tight">WorkBoard</span>
                </Link>

                <div class="relative my-auto max-w-xl py-16">
                    <p class="mb-6 inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.06] px-3.5 py-1.5 text-xs font-semibold uppercase tracking-[0.16em] text-indigo-200">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                        Your team workspace
                    </p>
                    <h1 class="text-4xl font-bold leading-[1.12] tracking-[-0.035em] xl:text-6xl">
                        Turn plans into<br />
                        <span class="text-indigo-300">progress.</span>
                    </h1>
                    <p class="mt-6 max-w-lg text-base leading-8 text-slate-300 xl:text-lg">
                        Organize projects, unblock your team, and keep every important deadline in view—from one calm workspace.
                    </p>

                    <div class="mt-12 grid max-w-lg grid-cols-3 gap-3">
                        <div class="rounded-2xl border border-white/10 bg-white/[0.05] p-4 backdrop-blur-sm">
                            <p class="text-2xl font-bold">12k+</p>
                            <p class="mt-1 text-xs text-slate-400">Tasks completed</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.05] p-4 backdrop-blur-sm">
                            <p class="text-2xl font-bold">98%</p>
                            <p class="mt-1 text-xs text-slate-400">On-time delivery</p>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/[0.05] p-4 backdrop-blur-sm">
                            <p class="text-2xl font-bold">4.9</p>
                            <p class="mt-1 text-xs text-slate-400">Team rating</p>
                        </div>
                    </div>
                </div>

                <p class="relative text-xs text-slate-500">© {{ new Date().getFullYear() }} WorkBoard. Make work feel lighter.</p>
            </section>

            <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-10 lg:px-16 xl:px-24">
                <div class="w-full max-w-[460px]">
                    <Link href="/" class="mb-10 flex w-fit items-center gap-3 lg:hidden">
                        <span class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M5 7.5 9 12l-4 4.5M11.5 7.5l4 4.5-4 4.5M18 7.5 22 12l-4 4.5" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="text-lg font-bold tracking-tight">WorkBoard</span>
                    </Link>

                    <div class="rounded-3xl bg-white p-6 shadow-[0_24px_70px_-30px_rgba(15,23,42,0.28)] ring-1 ring-slate-200/70 sm:p-9 lg:bg-transparent lg:p-0 lg:shadow-none lg:ring-0">
                        <slot />
                    </div>

                    <div class="mt-8 flex items-center justify-center gap-5 text-xs text-slate-400">
                        <a href="#" class="transition hover:text-slate-600">Privacy</a>
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <a href="#" class="transition hover:text-slate-600">Terms</a>
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <a href="#" class="transition hover:text-slate-600">Help</a>
                    </div>
                </div>
            </section>
        </div>
    </main>
</template>
