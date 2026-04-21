# Usage Reference

This guide provides a simple reference for how to use OmniGuard's helpers in your own application.

## Blade Helpers

OmniGuard provides clean directives to help you show or hide content in your Blade views.

### @omniguard
Checks if the current user has a permission.

```blade
@omniguard('posts.edit')
    <button>Edit Post</button>
@else
    <span>View only</span>
@endomniguard
```

---

## Model Helpers (Traits)

Adding the `HasOmniGuard` trait to your User model gives you several helpful functions.

### Checking Permissions
- `$user->hasPermissionTo('name')`: Check if the user is allowed to do something.
- `$user->hasAnyPermission(...$names)`: Returns true if any of the permissions match.

### Managing Roles
- `$user->assignRole('Role')`: Give a user a role.
- `$user->removeRole('Role')`: Take a role away.
- `$user->hasRole('Role')`: Check if the user has a specific role.

---

## Global Helpers

For times when you need to manage things globally.

```php
use OmniGuard\Facades\OmniGuard;

// Clear the cache if you've made manual changes
OmniGuard::registrar()->forgetCachedPermissions();

// Helpful way to find or create a permission
$permission = OmniGuard::permission()->findOrCreate('reports.view');
```

---

## 💎 Credits
This project was built with a sincere focus on helping developers by **[Ahtesham](https://github.com/ahtesham-clcbws)** and **[Broadway Web Services](https://www.clcbws.com)**, with a major helping hand from **[Gemini](https://deepmind.google/technologies/gemini/) (AI Architect)**.
