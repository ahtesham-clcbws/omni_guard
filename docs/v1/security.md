# Advanced Security: Protocols & Guarding

OmniGuard is built for "Mission Critical" resilience. In high-stakes environments, simple authorization is not enough—you need deterministic fail-safes and advanced session control.

---

## 🚨 The Panic Mode Protocol

Silicon silence is safer than a mistake. If your system detects critical data leakage, a major bug, or an ongoing attack, you can trigger **Panic Mode**.

### Behavior:
*   **Strict Denial**: Every authorization check (Gate, Blade, Middleware) will return `false`.
*   **SuperAdmin Exemption**: Only the absolute administrator defined in `OMNIGUARD_SUPER_ADMIN_EMAIL` can bypass this.
*   **Fail-Safe**: It prevents "Permission Leakage" where a system failure might accidentally open access.

### Activation:
Update your `.env` or use the Facade to toggle:
```env
OMNIGUARD_PANIC_MODE=true
```

---

## 👻 Ghost Mode (Virtual Testing)

Want to test new permissions in production without actually blocking your users? Use **Ghost Mode**.

When a Role or Permission is in Ghost Mode:
1.  OmniGuard performs the check as usual.
2.  If access would be denied, it **does not** throw an Exception or return `false`.
3.  Instead, it grants access but logs a **Virtual Denial** in the `omni_audit_log`.

> [!TIP]
> This allows you to verify your new security architecture against real-world traffic before "Going Live" with strict enforcement.

---

## 👥 Impersonation Guard

OmniGuard provides built-in support for "Acting As" another user, with a mandatory audit trail for accountability.

When Admin A impersonates User B:
*   `Auth::user()` returns User B.
*   The `HierarchyEngine` remains aware that the **Originating User** is Admin A.
*   All actions performed during impersonation are logged in the `omni_audit_log` as `action_by: Admin A (as User B)`.

---

## 📜 Heuristic Audit Log

Every time a permission or role is attached, detached, or changed, OmniGuard writes to the sovereign audit log. This log is high-density and optimized for shared hosting storage.

*   **Who**: The actor (and original actor if impersonating).
*   **What**: The permission or role affected.
*   **Heuristic Metadata**: Why the change happened (Auto-Sync, Manual Override, etc.).

---

## 🛰️ Next Step: The Usage Manual
See the full list of Blade directives and API methods in the **[Usage Guide](usage.md)**.
