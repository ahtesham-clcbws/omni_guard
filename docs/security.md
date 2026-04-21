# Security Helpers

OmniGuard includes some helpful security features designed to give you more control over your application's state without any complexity.

## 🔴 Panic Mode

Panic Mode is a simple fail-safe. If you need to quickly lock down your application (for example, during maintenance), OmniGuard can flip its logic to **Deny Access** for everyone except administrators.

### How to use it

In your `.env` file, just set:
```bash
OMNIGUARD_PANIC_MODE=true
```

### The Behavior:
- **Regular Users**: All permission checks will return `false` instantly to keep your site safe.
- **Administrators**: Users defined in your `OMNIGUARD_SUPER_ADMIN_EMAIL` will still have access so they can fix things or perform maintenance.

---

## 👻 Ghost Mode (Auditing Changes)

Ghost Mode is a helpful way to test your permissions before they go "live."

### The Behavior:
In Ghost Mode, OmniGuard evaluates the permissions as usual, but instead of actually denying access, it just logs what *would* have happened. This lets you see if your new roles are working correctly without affecting your users.

```bash
OMNIGUARD_GHOST_MODE=true
```

---

## 🎭 Impersonation (Act As)

OmniGuard includes a helpful impersonation service. This allows an administrator to "Act As" another user when troubleshooting something on their behalf.

### How it works:

```php
use OmniGuard\Services\ImpersonationGuard;

// Helping a user with ID 10
ImpersonationGuard::start($userId);

// When finished, go back to your admin account
ImpersonationGuard::stop();
```

### Staying Accountable:
To keep things transparent, every time someone uses the "Act As" feature, OmniGuard creates a simple log entry so you always know who was helping whom and when it happened.

---

## Next Steps

Check out the complete **[Usage Reference API](usage.md)** to see how to use these helpers in your code.
