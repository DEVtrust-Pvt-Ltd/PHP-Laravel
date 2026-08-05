/// <reference types="vite/client" />
import type { PageProps as ApplicationPageProps } from '@/Types/models';
import type { DefineComponent } from 'vue';

declare module '*.vue' {
    const component: DefineComponent<Record<string, unknown>, Record<string, unknown>, unknown>;
    export default component;
}

declare module '@inertiajs/core' {
    interface PageProps extends ApplicationPageProps {}
}

declare function route(name?: string, params?: unknown, absolute?: boolean): string;
