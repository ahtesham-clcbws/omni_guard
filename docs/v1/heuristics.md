# The Heuristic Discovery Brain

OmniGuard is a "Cognitive" orchestrator. Instead of manually entering every permission into a database, OmniGuard uses a **Discovery Brain** to map your codebase structure to authorization state automatically.

## #[OmniResource] Attribute

The primary way to signal the Brain is through the `#[OmniResource]` attribute. You can apply this to Controllers, Livewire Components, or even Model classes.

```php
use OmniGuard\Attributes\OmniResource;

#[OmniResource(group: 'Finances', icon: 'currency-dollar')]
class PayrollController extends Controller
{
    // ...
}
```

When you run `php artisan omniguard:sync`, the brain:
1.  **Scans** your application for these attributes.
2.  **Analyzes** the public methods (actions) inside the class.
3.  **Applies Heuristics**: If you have a method `store()`, it automatically maps it to the `finances.create` permission.

---

## Semantic Synonym Mapping

The Brain doesn't just look for exact words. It uses an internal dictionary of **Semantic Synonyms** to map common actions to standardized permissions.

| Method Name | Heuristic Permission |
| :--- | :--- |
| `store`, `save`, `create` | `create` |
| `index`, `show`, `list` | `view` |
| `update`, `patch`, `edit` | `edit` |
| `destroy`, `remove`, `delete` | `delete` |

---

## Fuzzy Matching & Groups

The `group` parameter in the `#[OmniResource]` attribute acts as the root namespace for the discovered permissions.

- Resource: `Payroll`
- Method: `calculate()`
- **Resulting Permission**: `payroll.calculate`

If the method doesn't strictly match a known synonym, OmniGuard uses **Fuzzy Matching** to identify if it targets a broad permission category or if it requires a new, unique permission record.

---

## Industrial Syncing

The sync engine is designed for **$1 Shared Hosting**. It uses a **Chunked Discovery** protocol to ensure memory usage stays flat, even in a repository with thousands of files.

```bash
php artisan omniguard:sync --chunk=50
```

This command will scan 50 files per pass, serializing the results into a temporary cache before finalizing the authority registry.

---

## Manual Overrides

The Heuristic Brain is an assistant, not a dictator. You can manually create permissions in your migrations or via the OmniGuard Facade, and the Brain will automatically **Respect and Protect** them, never overwriting manual data.

```php
OmniGuard::permission()->findOrCreate('payroll.custom_report');
```

---

## Next Steps

Learn how to manage this authority at scale in **[Performance: JIT Bitmasking](bitmasking.md)**.
