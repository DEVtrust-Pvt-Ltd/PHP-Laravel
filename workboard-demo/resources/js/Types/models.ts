export type ProjectRole = 'owner' | 'member' | 'viewer';
export type TaskStatus = 'todo' | 'in_progress' | 'done';
export type TaskPriority = 'low' | 'medium' | 'high';

export interface User { id: number; name: string; email: string; email_verified_at?: string | null }
export interface Member extends User { role: ProjectRole }
export interface Project {
    id: number; name: string; key: string; description: string | null;
    owner: Pick<User, 'id' | 'name'>; members: Member[]; task_count?: number;
}
export interface Task {
    id: number; project_id: number; title: string; description: string | null;
    status: TaskStatus; priority: TaskPriority; assignee: Pick<User, 'id' | 'name'> | null;
    due_date: string | null; position: number; created_at: string; updated_at: string;
}
export interface Activity {
    id: number; action: string; metadata: Record<string, unknown> | null;
    actor: Pick<User, 'id' | 'name'> | null; created_at: string;
}
export interface ProjectPermissions { editProject: boolean; deleteProject: boolean; editTasks: boolean }
export interface Flash { success?: string; error?: string }
export interface PageProps {
    [key: string]: unknown;
    auth: { user: User | null };
    flash: Flash;
}
