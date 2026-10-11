# Cheque Printing System

A Laravel 12.69.3 application foundation for designing, filling, and printing business bank cheques.

## Phase 1 scope

Implemented: Breeze authentication, role-based dashboards, active-account middleware, database schema,
Eloquent models and factories, named routes, placeholder pages, demo seed data, English amount
conversion, helpers, and automated tests.

Bank/user/template/cheque/settings write actions are deliberate no-ops. They redirect with a message
and do not save or delete data. Print/PDF routes render placeholders. CRUD, tenant authorization
policies for record content, template design, alignment, cheque creation, and printing belong to later
phases. Existing Breeze profile and account-management features remain functional.

## Requirements

- PHP 8.2+ (64-bit), with Laravel extensions and PDO MySQL
- MySQL 8+
- Composer 2
- Node.js 20.19+ or 22.12+ and npm for the installed Vite 7 toolchain
- PDO SQLite for the isolated in-memory test database

The original brief listed Node 18+. The installed Vite 7 package requires
`^20.19.0 || >=22.12.0`, so Node 18 is insufficient. No packages were replaced.

## Installation

Create an empty MySQL database named `cheque_printer`, then run:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use `Copy-Item .env.example .env` instead of `cp` if needed.
Set these values in your local `.env`:

```dotenv
APP_NAME="Cheque Printing System"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cheque_printer
DB_USERNAME=your_mysql_user
DB_PASSWORD=your_mysql_password
DEMO_MODE=false
```

Never commit `.env`. Use your own MySQL credentials.

```bash
php artisan migrate --seed
npm install
npm run build
php artisan storage:link
php artisan serve
```

Open http://127.0.0.1:8000/login. For frontend development, run `npm run dev`
in a second terminal.

## Demo accounts

| Role | Email | Password | Dashboard |
| --- | --- | --- | --- |
| Admin | admin@example.com | password | /admin/dashboard |
| User | user@example.com | password | /user/dashboard |

These are public demo credentials, not production secrets. Remove or change demo accounts before
deployment. The demo seeder targets an empty database and is not intended to be run repeatedly
against existing accounts. `php artisan migrate:fresh --seed` deletes all existing tables and data.

The seeder also creates Islami Bank Bangladesh (default), BRAC Bank, and Dutch-Bangla Bank.

## Verification

```bash
php artisan route:list
php artisan migrate:fresh --seed
php artisan test
npm run build
```

The tests use a separate SQLite in-memory database; migration and browser verification use the
configured application database. To inspect migration rollback on a disposable database, run
`php artisan migrate:rollback --step=5`, followed by `php artisan migrate`.

Browser checks:

1. Log in as admin; confirm `/admin/dashboard`.
2. Log out and log in as user; confirm `/user/dashboard`.
3. Visit `/admin/dashboard` as user; expect HTTP 403.
4. Log out; confirm authenticated pages redirect to login.

## Amount conversion

```php
amount_in_words(1250.50);
// One Thousand Two Hundred Fifty Taka and Fifty Paisa Only

format_money(1250.50);
// BDT 1,250.50
```

Amounts in words are generated in English using the Indian numbering system (lakh, crore).
They round half-up to two decimal places and must be finite, non-negative values within the
`decimal(15,2)` schema range. Conversion returns words.
The required float API should be called with care at monetary boundaries; stored amounts remain
decimal strings through Eloquent's casts.

## Schema notes

- Deleting a creator retains banks/templates with a NULL creator.
- Deleting a bank cascades to its templates and cheques.
- Deleting a template clears the cheque template reference.
- Deleting a user cascades to their cheques and settings.
- The requested settings unique key enforces one key per non-NULL user.
  MySQL permits duplicate keys when `user_id` is NULL; a global-settings uniqueness
  strategy is needed when global settings CRUD is implemented.
- Bank names use a unique index, which also provides the requested indexing.
- `DEMO_MODE` is configuration only in Phase 1; write restrictions belong to later phases.

## Folder structure

```text
app/
  Helpers/helpers.php
  Http/
    Controllers/Admin/    Admin dashboard, users, banks placeholders
    Controllers/User/     User dashboard, banks, templates, cheques, settings placeholders
    Middleware/           Admin and active-account checks
    Requests/             Validated authentication/profile input and no-op foundation requests
  Models/                 User, Bank, ChequeTemplate, Cheque, Setting
  Services/               AmountInWordsService
bootstrap/app.php         Routing and middleware registration
config/cheque.php          Currency, dimensions, paths, upload limit, demo configuration
database/
  factories/              Model factories
  migrations/             Default Laravel tables and five foundation migrations
  seeders/                Demo accounts and banks
resources/views/
  admin/                  Admin placeholders
  user/                   User placeholders
  components/             Page header and Breeze components
  layouts/                Shared Breeze layout and role layouts
routes/                   Application and Breeze routes
tests/
  Feature/                Authentication, access, route, helper and Breeze tests
  Unit/                   English amount conversion tests
```
