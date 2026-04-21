# Performance: JIT Binary Bitmasking

OmniGuard is engineered for scale. Standard authorization engines rely on string-based array searching (e.g., `in_array`), which slows down as you add more permissions.

OmniGuard implements **JIT (Just-In-Time) Binary Bitmasking**, achieving **O(1) Performance** regardless of the number of permissions.

## The Bitmask Protocol

Instead of names, OmniGuard converts your permissions into a unique power-of-two index bit.

| Permission | Bit Index | Binary Value |
| :--- | :--- | :--- |
| `user.view` | 0 | `0001` |
| `user.create` | 1 | `0010` |
| `user.edit` | 2 | `0100` |
| `user.delete` | 3 | `1000` |

---

## O(1) Binary Comparisons

When a user is authorized, their total permission set is hydrated into a single Integer (the **Bitmask**). Authorization checks are then performed using high-speed CPU-level **Binary Comparison**.

```php
// Standard PHP Bitmask check
$authorized = ($userBitmask & $requiredBit) !== 0;
```

This operation is orders of magnitude faster than iterating through a collection of strings and is executed directly in Redis or memory.

---

## Optimized for $1 Shared Hosting

OmniGuard's JIT implementation is specifically optimized for memory-constrained environments ( budget shared hosting with 256MB-512MB RAM).

1.  **Lazy Hydration**: Bitmasks are only calculated once and cached in the session or Redis.
2.  **Flat Memory Usage**: Because authorization is integer-based, the memory footprint remains constant as your user base and permission count grow.

---

## When are Bitmasks Regenerated?

The JIT engine automatically detects changes in the authority registry. If you assign a new permission to a user or role, the Bitmask is marked as "stale" and will be re-calculated on the next request.

```php
// Manually marking authority as stale
OmniGuard::registrar()->clearClassPermissions();
```

---

## Next Steps

Explore the extreme security protocols available in **[Security Protocols: Panic Mode & Ghost Mode](security.md)**.
