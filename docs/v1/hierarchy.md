# Role Hierarchy & Ranking

OmniGuard provides an easy way to manage how roles relate to each other. It uses a simple **Rank System** where roles are assigned a `sort_order` that determines their priority.

## How it works

In OmniGuard, a **Lower Sort Order = Higher Priority**.

- `Admin` (sort_order: 1)
- `Manager` (sort_order: 10)
- `Staff` (sort_order: 50)
- `User` (sort_order: 100)

When you check permissions, OmniGuard can look at a user's highest role (via `getTopRole()`) to help decide what they are allowed to do.

---

## Role Inheritance

OmniGuard can help you pass permissions from one role to another. For example, an Admin can automatically have all the permissions that a Manager has:

```php
$manager = Role::findByName('Manager');
$admin = Role::findByName('Administrator');

// Administrator gets all Manager permissions
$admin->givePermissionTo($manager->permissions);
```

While this is useful, OmniGuard also works great with **Discovery Brain** (see the Heuristics guide) which can handle most of this for you.

---

## Helpful Admin Override

OmniGuard provides a simple way to set a "SuperUser" who always passes permission checks. This is great for your own account or for maintenance tasks.

You can configure this by setting an email in your `.env`:
`OMNIGUARD_SUPER_ADMIN_EMAIL=your@email.com`

---

## Filtering Users

OmniGuard makes it easy to find users based on their roles:

```php
// Find all Admins
$admins = User::role('Admin')->get();

// Find everyone who ISN'T a Guest
$staff = User::withoutRole('Guest')->get();
```

---

## Next Steps

Learn how OmniGuard's brain helps automate your setup in the **[Discovery Brain Guide](heuristics.md)**.
