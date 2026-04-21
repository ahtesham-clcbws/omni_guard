# Usage Reference

This guide provides a comprehensive API reference for interacting with the OmniGuard Sovereign Orchestrator.

## Blade Directives

OmniGuard provides clean, semantic Blade directives to control your UI based on authority.

### @omniguard
Checks if the current user has a specific permission.

```blade
@omniguard('posts.edit')
    <button>Edit Post</button>
@else
    <span>Viewing Mode</span>
@endomniguard
```

### @omnirole
Checks if the current user has a specific role (or one of a set of roles).

```blade
@omnirole('Administrator|Manager')
    <p>Staff Dashboard</p>
@endomnirole
```

---

## The HasOmniGuard Trait

Adding this trait to your User model unlocks the core orchestration Methods.

### Permission Methods
- `$user->hasPermissionTo('permission.name')`: Check for a specific authority.
- `$user->hasDirectPermission('permission.name')`: Check if granted directly to the user (ignoring roles).
- `$user->hasAnyPermission(...$permissions)`: Returns true if any match.

### Role Methods
- `$user->assignRole('Role Name')`: Grant a role to the user.
- `$user->removeRole('Role Name')`: Revoke a role.
- `$user->syncRoles(...$roles)`: Wipe existing roles and set new ones.
- `$user->hasRole('Role Name')`: Check role membership.
- `$user->getTopRole()`: Returns the Role model with the lowest `sort_order`.

---

## The OmniGuard Facade

For global orchestration and management.

```php
use OmniGuard\Facades\OmniGuard;

// Get the central registrar
$registrar = OmniGuard::registrar();

// Clear the entire authority cache
OmniGuard::registrar()->forgetCachedPermissions();

// Find or Create authority records
$permission = OmniGuard::permission()->findOrCreate('reports.view');
```

---

## Common Orchestration Tasks

### Granting Permission to a Role
```php
$role = Role::findByName('Manager');
$role->givePermissionTo('orders.edit');
```

### Checking Rank
```php
if ($user->getTopRole()->hasHigherRankThan(Role::findByName('User'))) {
    // Highly authorized logic
}
```

---

## 💎 Support & Enterprise Credits
OmniGuard is a proprietary software architected by **Ahtesham** and **Broadway Web Services**. A major thank you to **Gemini (AI Architect)** for the mission-critical helping hand in building this Sovereign engine.
