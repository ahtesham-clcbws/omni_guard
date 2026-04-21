# Installation Guide

Setting up **OmniGuard** is a streamlined process designed to bring sovereign authority to your Laravel application in minutes.

---

## 📦 Requirements
*   **PHP**: 8.2 or higher
*   **Laravel**: 11.x, 12.x, or 13.x

---

## 🛠️ Step 1: Install via Composer

Install the package via composer. Since OmniGuard is zero-dependency at runtime, this will be a lightweight install.

```bash
composer require omniguard/omniguard
```

---

## 🛠️ Step 2: Publish Assets

You must publish the configuration and migrations to take total control over the database schema and engine behavior.

```bash
php artisan vendor:publish --tag="omniguard-config"
php artisan vendor:publish --tag="omniguard-migrations"
```

---

## 🛠️ Step 3: Run Migrations

OmniGuard uses six sovereign tables. These are prefixed by default with `omni_` to avoid collisions with other packages.

```bash
php artisan migrate
```

### The Sovereign Schema:
*   `omni_roles`: The hierarchical roles table.
*   `omni_permissions`: The permission registry.
*   `omni_model_has_roles`: Pivot for user roles mapping.
*   `omni_model_has_permissions`: Pivot for direct user overrides.
*   `omni_role_has_permissions`: Pivot for role-to-permission mapping.
*   `omni_audit_log`: Heuristic Audit trail for all permission changes.

---

## 🛠️ Step 4: Configure your User Model

Add the `HasOmniGuard` trait to your `User` model. This trait replaces standard permission traits and provides the necessary ranking logic for the hierarchy engine.

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

## 🛠️ Step 5: Initialize the SuperAdmin

In your `.env` file, define the email of the absolute administrator. The **HierarchyEngine** will always grant full access to this user, regardless of role state.

```env
OMNIGUARD_SUPER_ADMIN_EMAIL=admin@clcbws.com
```

---

## 🛰️ Next Step: The Multi-Discovery Hub
Now that OmniGuard is installed, you can begin automatically discovering permissions from your codebase. Learn more in the **[Heuristics Guide](heuristics.md)**.
