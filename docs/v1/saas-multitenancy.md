# SaaS & Multitenancy (OmniTenant)

OmniGuard is natively aware of multi-tenant environments. Unlike other systems where you have to manually filter permissions by tenant IDs on every query, OmniGuard provides a **Sovereign Tenant Context** engine.

---

## 🏢 The OmniTenant Philosophy

In a SaaS application, a user might have different roles in different companies.
*   User is an **Admin** in "Company A".
*   User is a **Staff** in "Company B".

OmniGuard scopes roles and permissions to a specific `tenant_id` automatically when a tenant context is active.

---

## 🛠️ Setting the Tenant Context

Use the `OmniGuard` facade to set the active tenant during your application's bootstrap phase (e.g., in a Middleware or ServiceProvider).

```php
use OmniGuard\Facades\OmniGuard;

// Set by ID
OmniGuard::setTenant($tenantId);

// Or set by Model
OmniGuard::setTenant($organization);
```

Once set, all authorization checks will only consider roles and permissions associated with that specific tenant.

---

## 🔗 Scoped Roles

When creating roles for a specific organization, ensure the `scope` is set correctly. OmniGuard handles this through the `omni_roles` table.

```php
use OmniGuard\Models\Role;

// A global role (available everywhere)
Role::create(['name' => 'super-executive', 'scope' => 'global']);

// A tenant-scoped role
Role::create(['name' => 'manager', 'tenant_id' => 123, 'scope' => 'tenant']);
```

---

## 🛡️ Scoped Authorization Logic

If you check a permission while a tenant is active, the `TenantManager` ensures:
1.  **Global Roles** are always included in the check.
2.  **Tenant Roles** are only included if their `tenant_id` matches the current active `OmniGuard::getTenantId()`.

This isolation ensures total security and prevents "cross-tenant leakage" where a role from one company might grant access to another company's data.

---

## 🛰️ Next Step: Advanced Security
Learn about deterministic fail-safes in the **[Security Guide](security.md)**.
