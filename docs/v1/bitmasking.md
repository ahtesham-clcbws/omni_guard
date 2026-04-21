# Performance: Fast Authority Checks

OmniGuard is built to be snappy and efficient. It uses a simple technique called **Bitmasking** to make authorization checks as fast as possible, so your application stays responsive.

## How it works

Instead of using long lists of names, OmniGuard assigns each permission a simple number. 

| Permission | Index |
| :--- | :--- |
| `user.view` | 0 |
| `user.create` | 1 |
| `user.edit` | 2 |

---

## Speedy Comparisons

When a user logs in, OmniGuard combines their permissions into a single, small piece of data. This allows the computer to perform checks almost instantly.

It's a bit like a row of light switches—OmniGuard can check if the right switch is 'on' in a single step, rather than looking through a long list of papers.

---

## Shared Hosting Friendly

This method is specifically designed to be light on your server's memory. This is perfect if you are on a budget shared hosting plan with limited resources:

1.  **Light Memory**: Because it uses simple numbers instead of long strings of text, it takes up very little space.
2.  **Fast**: Your server doesn't have to work hard to check permissions, which helps keep your page loads fast.

---

## Automatic Updates

OmniGuard is smart enough to know when you've changed something. If you add a new role or permission, it will automatically refresh this speed-optimized data for you on the next request.

---

## Next Steps

Learn how OmniGuard can help you with security in the **[Security Helpers Guide](security.md)**.
