# Security Protocols

OmniGuard is a mission-critical orchestrator. It provides advanced security protocols designed for high-stakes environments where authorization failure is not an option.

## 🔴 Panic Mode Protocol

Panic Mode is a deterministic fail-safe. When your application enters an unstable state (or manually triggered for maintenance), OmniGuard flips its logic to **Strict Denial**.

### Enabling Panic Mode

In your `.env` file:
```bash
OMNIGUARD_PANIC_MODE=true
```

### The Behavior:
- **Normal Users**: All `Gate::check()` calls return `false` instantly. No database calls are made. 
- **Super Admins**: Remain operational. Users defined in `OMNIGUARD_SUPER_ADMIN_EMAIL` retain full access to fix the system.

You can also trigger it programmatically via the Middleware for specific routes:

```php
Route::middleware('omniguard.panic')->group(function () {
    // Critical routes protected by the panic protocol
});
```

---

## 👻 Ghost Mode (Virtual Authorization)

Ghost Mode allows you to safely test new roles or permission changes in production without actually denying access to users.

### The Behavior:
OmniGuard evaluates the authorization as usual, but instead of denying the request, it logs a **"Virtual Denial"** and allows the user through.

```bash
OMNIGUARD_GHOST_MODE=true
```

Use this to audit how a new rank hierarchy would impact your users before committing to the changes.

---

## 🎭 ImpersonationGuard (Act As)

OmniGuard includes a secure high-level impersonation service. This allows an Administrator to "Act As" another user while maintaining a mandatory, immutable audit trail.

### Implementation:

```php
use OmniGuard\Services\ImpersonationGuard;

// Start impersonating student ID 10
ImpersonationGuard::start($studentId);

// Stop impersonating and return to Admin context
ImpersonationGuard::stop();
```

### Audit Trail:
The `ImpersonationGuard` ensures that every action taken while "Acting As" is tagged with the **Original Admin ID** in the logs, ensuring absolute accountability.

---

## Next Steps

Learn the final API surface in the **[Usage Reference](usage.md)**.
