# Installation & Setup

OmniGuard is a simple and helpful authorization manager. It is designed to be easily integrated into your application.

## Prerequisites

- **PHP**: 8.2 or higher.
- **Laravel**: 11.x, 12.x, or 13.x (preview).
- **Storage**: Redis or Database (Recommended for $1 shared hosting).

---

## 1. Composer Installation

Add OmniGuard to your project with a simple command:

```bash
composer require omniguard/omniguard
```

---

## 2. Service Provider Registration

OmniGuard should automatically register its service provider. If it doesn't, add the following to your `bootstrap/providers.php` or `config/app.php`:

```php
OmniGuard\OmniGuardServiceProvider::class,
```

---

## 3. Publication & Migrations

OmniGuard manages its own tables to ensure zero-conflict with your existing data. Publish the configuration and migration files:

```bash
php artisan omniguard:install
```

This will create:
- `config/omniguard.php`: The central brain configuration.
- `database/migrations/*_create_omniguard_tables.php`: The relational structure.

Run the migrations to establish the authority:

```bash
php artisan migrate
```

---

## 4. Setting up the User Model

To help your User model manage permissions, add the `HasOmniGuard` trait:

```php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use OmniGuard\Traits\HasOmniGuard;

class User extends Authenticatable
{
    use HasOmniGuard;

    // ...
}
```

---

## 5. The Helper Configuration

The `config/omniguard.php` file is where you define the ultimate behavior of the system.

### Key Settings:
- `models`: If you wish to extend the core `Role` or `Permission` models, define them here.
- `table_names`: Custom table names for your database.
- `teams`: Enable multi-tenant / team-based authorization.
- `enable_wildcard_permission`: Allow complex string-based permissions (e.g., `user.*`).

---

## 6. Initial Sync (Heuristic Discovery)

OmniGuard's brain needs to scan your codebase to understand your initial permission structure. Run the sync command:

```bash
php artisan omniguard:sync
```

This will crawl your controllers and livewire components, identifying `#[OmniResource]` attributes and mapping them to the database.

---

## Next Steps

Now that you have everything set up, explore the **[Concepts of Hierarchy](hierarchy.md)** or dive into the **[Discovery Brain](heuristics.md)**.
