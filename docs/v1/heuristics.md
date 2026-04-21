# The Discovery Brain

OmniGuard includes a helpful **Discovery Brain** that can help you map your codebase structure to permissions automatically, saving you from repetitive manual entry.

## #[OmniResource] Attribute

The primary way to let the brain know about your code is through the `#[OmniResource]` attribute. You can add this to your Controllers or Livewire components.

```php
use OmniGuard\Attributes\OmniResource;

#[OmniResource(group: 'Finances', icon: 'currency-dollar')]
class PayrollController extends Controller
{
    // ...
}
```

When you run `php artisan omniguard:sync`, the brain:
1.  **Looks** for these attributes in your app.
2.  **Analyzes** the public functions inside the class.
3.  **Maps them for you**: If you have a `store()` function, it automatically suggests the `finances.create` permission.

---

## Simple Action Mapping

The brain uses a simple internal list to map common function names to standard permissions, so you don't have to.

| Function Name | Suggested Permission |
| :--- | :--- |
| `store`, `save`, `create` | `create` |
| `index`, `show`, `list` | `view` |
| `update`, `patch`, `edit` | `edit` |
| `destroy`, `remove`, `delete` | `delete` |

---

## Helpful Grouping

The `group` parameter helps organize your permissions.

- Resource: `Payroll`
- Function: `calculate()`
- **Result**: `payroll.calculate`

If a function name doesn't match the standard list, OmniGuard will still try its best to identify what it does or suggest a new permission for you.

---

## Shared Hosting Friendly

The sync process is designed to be very light on memory, which is perfect if you are on a budget hosting plan.

```bash
php artisan omniguard:sync --chunk=50
```

This command will look at 50 files at a time, keeping things running smoothly without overloading the server.

---

## You Are Still In Control

The Discovery Brain is just a helper. If you want to create permissions manually in your database or code, OmniGuard will always respect been-hand-crafted data and will never overwrite it.

```php
OmniGuard::permission()->findOrCreate('payroll.custom_report');
```

---

## Next Steps

Learn how OmniGuard keeps things fast in the **[Performance Guide](bitmasking.md)**.
