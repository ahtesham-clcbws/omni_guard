# JIT Binary Bitmasking

Performance is a first-class citizen in OmniGuard. For small applications, standard string comparisons are fast enough. For enterprise applications with hundreds of permissions, scalability becomes a bottleneck.

OmniGuard solves this through **Just-In-Time (JIT) Binary Bitmasking**.

---

## ⚡ What is Bitmasking?

In a bitmasking system, every unique permission is assigned a specific power-of-two index:
*   `user.view` = 1 (2^0)
*   `user.create` = 2 (2^1)
*   `user.update` = 4 (2^2)
*   `user.delete` = 8 (2^3)

A Role's aggregate permission state is then stored as a **single integer**. 
> **Example**: A role with `view` and `update` permissions has a bitmask value of `1 + 4 = 5`.

---

## 🚀 The O(1) Advantage

When OmniGuard checks if a user can `user.update` (value 4), it performs a **Binary AND Comparison** between the User's Bitmask (5) and the required Permission (4).

```php
// Binary: 0101 (5) AND 0100 (4) = 0100 (4)
// If result == required, access is GRANTED.
```

This operation happens at the CPU/Memory level and is exponentially faster than looping through arrays of permission strings. This is how OmniGuard maintains **O(1) efficiency** regardless of how many permissions your system has.

---

## 🏗️ The BitmaskRegistrar

You don't have to manage these numbers. The `BitmaskRegistrar` handles it automatically:
1.  During `omniguard:sync`, it assigns a power-of-two index to each unique permission slug.
2.  It persists these indexes in the JIT cache.
3.  When a user logs in, it hydrates their session with the calculated integer mask.

---

## 🍃 Optimized for $1 Hosting

Because bitmasking relies on simple integer math rather than heavy string processing or recursive database queries, it dramatically reduces the memory footprint of your authorization layer. 

OmniGuard can perform thousands of checks per second while consuming only a few kilobytes of RAM—making it the perfect choice for budget shared hosting environments.

---

## 🛰️ Next Step: Enterprise SaaS
Learn how to context-scope these permissions using the **[SaaS & Multitenancy Guide](saas-multitenancy.md)**.
