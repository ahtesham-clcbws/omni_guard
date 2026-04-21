# Role Hierarchy & Ranking

The **HierarchyEngine** is the sovereign core of OmniGuard. Unlike basic permission systems, OmniGuard understands that some roles are inherently more powerful than others.

---

## 🪜 The Ranking System

Every Role in OmniGuard possesses a `sort_order` attribute. 
*   **Lower Value = Higher Rank**.
*   An `Admin` with `sort_order: 1` is more powerful than a `Teacher` with `sort_order: 5`.

### Why it Matters
OmniGuard uses this ranking to automatically prevent lower-ranking users from performing actions on higher-ranking users, even if they share certain permissions.

> [!TIP]
> This "Vertical Protection" ensures that a Branch Manager cannot delete the CEO, even if both have the `user.delete` permission.

---

## 🏗️ Recursive Inheritance

OmniGuard supports **Recursive Dot-Notation Propagation**. This is managed by the `PermissionWalker`.

If you grant a role the `student` permission, OmniGuard automatically assumes the role has access to all child permissions (e.g., `student.view`, `student.delete`).

### Propagation Levels:
1.  **Direct Match**: Explicitly checking `student.view`.
2.  **Parent Match**: Granting `student` grants all sub-actions.
3.  **Recursive Search**: Use `PermissionWalker::walk('student.view')` to find all ancestors.

---

## 🛡️ Defining Hierarchy

You can manage hierarchy directly through the `Role` model:

```php
use OmniGuard\Models\Role;

// Creating a High-Ranking Admin
Role::create([
    'name' => 'super-admin',
    'sort_order' => 1,
]);

// Creating a Staff User
Role::create([
    'name' => 'staff',
    'sort_order' => 10,
]);
```

---

## 🔍 Authorization Logic
When a check is performed via `Gate::check()` or `@omniguard()`, the `HierarchyEngine` intercepts the request:

1.  **Panic Check**: Is the system in Panic Mode? If yes, deny all (except SuperAdmin).
2.  **SuperAdmin Check**: Is the user the absolute administrator? If yes, allow.
3.  **Target Ranking**: If the action involves a target User, compare `sort_order`. If `UserRank >= TargetRank`, deny.
4.  **Standard Logic**: Proceed to permission-based checks.

---

## 🛰️ Next Step: The Brain
See how OmniGuard can automatically detect these permissions in your code using the **[Heuristics Guide](heuristics.md)**.
