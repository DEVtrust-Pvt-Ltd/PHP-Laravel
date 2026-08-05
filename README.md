# WorkBoard

WorkBoard is a Laravel 12 and Vue 3 project-management application with project
boards, role-based members, task workflows, activity history, an Inertia browser
UI, and a versioned JSON API.

## Requirements

- PHP 8.2+
- Composer 2
- Node.js 20.19+ or 22.12+
- npm
- SQLite with pdo_sqlite, or MySQL/PostgreSQL configured in .env

Node 16 is not supported by Vite 7.

## Stack

- Laravel 12, Sanctum, Inertia Laravel and Eloquent
- Vue 3.5, TypeScript, Inertia Vue 3 and Vite 7
- Tailwind CSS
- PHPUnit, Laravel Pint, ESLint and vue-tsc

## Architecture

The backend follows a thin-controller Service–Repository architecture:

    app/
    ├── Http/Controllers/    Request, authorization and response only
    ├── Http/Requests/       Validation and request authorization
    ├── Http/Resources/      API and Inertia serialization
    ├── Services/            Business rules, workflows and transactions
    ├── Repositories/        Eloquent queries, associations and persistence
    ├── Models/              Relationships, casts and scopes
    └── Policies/            Owner, member and viewer permissions

Controllers contain no raw database queries or direct model persistence.
Repositories use Eloquent associations. Services coordinate project creation,
member onboarding, task workflows, activity recording and password changes.

The Vue application uses:

    resources/js/
    ├── Repositories/        Typed Inertia request operations
    ├── Types/models.ts      Shared application interfaces
    ├── Components/          Reusable UI components
    ├── Layouts/
    └── Pages/

Shared TypeScript types cover users, projects, members, tasks, activities,
permissions, flash messages and Inertia page props.

## Installation

    composer install
    cp .env.example .env
    php artisan key:generate
    npm install

For SQLite:

    touch database/database.sqlite
    php artisan migrate:fresh --seed

For MySQL or PostgreSQL, configure the DB values in .env and then run the same
migration command.

Start the application in two terminals:

    php artisan serve

    npm run dev

Open http://127.0.0.1:8000.

Seeded demo account:

    Email: demo@workboard.test
    Password: password

## Authentication

- The root URL sends guests to login and authenticated users to dashboard.
- Login/logout use Inertia history replacement and server session checks.
- Protected actions use authentication middleware and policies.
- Browser back/forward navigation cannot reuse a logged-out session.

### New-member onboarding

When a project owner adds an email:

1. An existing account is added directly.
2. A missing account opens the create-member modal.
3. The owner provides the name, role and temporary password.
4. The new account is assigned to the project.
5. First login requires the member to replace the temporary password.
6. Dashboard/project access stays blocked until password setup is complete.

After pulling onboarding changes, run:

    php artisan migrate

## Authorization

| Role | Access |
| --- | --- |
| Owner | Project settings, deletion, members and all task operations |
| Member | View project and create, update or move tasks |
| Viewer | Read-only project and task access |

Tasks can only be assigned to users belonging to their project. Removing a
member automatically unassigns that member's project tasks.

## Browser features

- Dashboard progress, overdue work and upcoming deadlines
- Project creation, search, editing and deletion
- Existing/new member assignment and removal
- Task creation, editing, moving and deletion
- Activity history with acting-user information
- Responsive layouts, empty states and confirmation dialogs
- Reusable spinners, submit buttons and flash notifications

## API

All API routes use the /api/v1 prefix.

Obtain a bearer token:

    POST /api/v1/auth/login
    Content-Type: application/json

    {
      "email": "demo@workboard.test",
      "password": "password",
      "device_name": "local"
    }

Use Authorization: Bearer TOKEN for protected requests.

| Area | Endpoints |
| --- | --- |
| Session | POST auth/login, POST auth/logout, GET me |
| Dashboard | GET dashboard |
| Projects | GET/POST projects; GET/PATCH/DELETE projects/{project} |
| Members | GET/POST projects/{project}/members; DELETE members/{user} |
| Tasks | GET/POST projects/{project}/tasks; GET/PATCH/DELETE tasks/{task} |
| Workflow | POST tasks/{task}/move; GET tasks/{task}/activity |

Task lists support status, priority, assignee_id and search filters. The API
contract is available in docs/openapi.yaml.

## Quality commands

    php artisan test
    vendor/bin/pint --test
    npm run type-check
    npm run lint
    npm run build

Feature tests use in-memory SQLite and require the PHP pdo_sqlite extension.

## Vue and TypeScript editor setup

Use Vue Official (Volar), not Vetur. Vetur can analyze the application as Vue 2
and incorrectly report Vue 3 fragments as multiple-template-root errors.

The repository includes workspace settings that:

- recommend Vue Official and ESLint;
- disable Vetur validation;
- select the workspace TypeScript SDK;
- map the @ alias to resources/js;
- declare Vue SFC modules and typed Inertia PageProps;
- set the Vue compiler target to version 3.

After cloning:

1. Run npm install.
2. Install the recommended editor extensions.
3. Disable Vetur for the workspace.
4. Run Developer: Reload Window in VS Code.

## Security

- Passwords use Laravel's hashed model cast.
- Login requests are rate limited and sessions regenerate after login.
- New internal accounts must replace temporary passwords.
- Policies protect projects and tasks.
- Destructive actions require authorization and UI confirmation.
