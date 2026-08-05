import { router } from '@inertiajs/vue3';
import type { InertiaForm } from '@inertiajs/vue3';
type VisitOptions = Record<string, unknown>;

export const taskRepository = {
    create(form: InertiaForm<Record<string, unknown>>, projectId: number, options: VisitOptions = {}) {
        form.post(route('projects.tasks.store', projectId), options);
    },
    update(form: InertiaForm<Record<string, unknown>>, taskId: number, options: VisitOptions = {}) {
        form.patch(route('tasks.update', taskId), options);
    },
    move(taskId: number, data: Record<string, unknown>, options: VisitOptions = {}) {
        router.post(route('tasks.move', taskId), data, options);
    },
    delete(taskId: number, options: VisitOptions = {}) {
        router.delete(route('tasks.destroy', taskId), options);
    },
};
