import { router } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';

type VisitOptions = Record<string, unknown>;

export const projectRepository = {
    search(filters: Record<string, unknown>, options: VisitOptions = {}) {
        router.get(route('projects.index'), filters, { preserveState: true, replace: true, ...options });
    },
    create(form: InertiaForm<Record<string, unknown>>, options: VisitOptions = {}) {
        form.post(route('projects.store'), options);
    },
    update(form: InertiaForm<Record<string, unknown>>, projectId: number, options: VisitOptions = {}) {
        form.patch(route('projects.update', projectId), options);
    },
    delete(form: InertiaForm<Record<string, unknown>>, projectId: number, options: VisitOptions = {}) {
        form.delete(route('projects.destroy', projectId), options);
    },
    addMember(form: InertiaForm<Record<string, unknown>>, projectId: number, options: VisitOptions = {}) {
        form.post(route('projects.members.store', projectId), options);
    },
    createMember(form: InertiaForm<Record<string, unknown>>, projectId: number, options: VisitOptions = {}) {
        form.post(route('projects.members.create', projectId), options);
    },
    removeMember(projectId: number, userId: number, options: VisitOptions = {}) {
        router.delete(route('projects.members.destroy', [projectId, userId]), options);
    },
};
