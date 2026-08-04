import { router } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';
type VisitOptions = Record<string, unknown>;

export const sessionRepository = {
    async status() {
        return fetch(route('auth.status'), {
            credentials: 'same-origin',
            cache: 'no-store',
            headers: { Accept: 'application/json' },
        });
    },
    login(form: InertiaForm<Record<string, unknown>>, options: VisitOptions = {}) {
        form.post(route('login'), { replace: true, ...options });
    },
    logout(options: VisitOptions = {}) {
        router.post(route('logout'), {}, { replace: true, preserveState: false, ...options });
    },
};
