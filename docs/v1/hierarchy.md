# Role Hierarchy & Ranking

OmniGuard moves beyond flat role systems. It implements a **Sovereign Rank Protocol** where roles are assigned a `sort_order` that determines their authority over one another.

## The Authority Rank

In OmniGuard, a **Lower Sort Order = Higher Rank**.

- `Super Admin` (sort_order: 1)
- `Administrator` (sort_order: 10)
- `Manager` (sort_order: 50)
- `User` (sort_order: 100)

When performing authorization checks, OmniGuard evaluates the User's highest-ranking role (via `getTopRole()`) to determine their ultimate authority.

---

## Role Inheritance

OmniGuard supports recursive role inheritance. A role can inherit all permissions from a child role. 

### Implementation Example:

```php
$manager = Role::findByName('Manager');
$admin = Role::findByName('Administrator');

// Administrator inherits all permissions currently assigned to Manager
$admin->givePermissionTo($manager->permissions);
```

While inheritance is useful, OmniGuard encourages the use of **Heuristic Mapping** (see Heuristics) to avoid manual permission assignment.

---

## The Super Admin Fail-Safe

OmniGuard provides an absolute override for SuperAdmins. Users with the highest possible rank (configurable via `OMNIGUARD_SUPER_ADMIN_EMAIL`) always pass `Gate` checks, even if Panic Mode is enabled.

In `App\Models\User.php`:

```php
public function isSuperAdmin(): bool
{
    return $this->email === env('OMNIGUARD_SUPER_ADMIN_EMAIL');
}
```

---

## Query Scoping

OmniGuard adds powerful Eloquent scopes to your User model for role-based filtering:

```php
// Get all Administrators
$admins = User::role('Administrator')->get();

// Get users WITHOUT the 'Guest' role
$nonGuests = User::withoutRole('Guest')->get();
```

---

## Rank Comparisons

You can easily compare roles in your business logic:

```php
if ($user->getTopRole()->hasHigherRankThan($anotherRole)) {
    // Perform high-clearance action
}
```

---

## Next Steps

Understand how OmniGuard's brain automatically assigns these roles and permissions in the **[Discovery Brain Guide](heuristics.md)**.
