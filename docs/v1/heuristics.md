# The Heuristic Brain

OmniGuard moves beyond manual permission entry. Its **Multi-Discovery Hub** scans your codebase using heuristic intelligence to automatically map actions and roles.

---

## 🧠 Brain Logic: Synonym Mapping

The `HeuristicMapper` is the unit responsible for "understanding" your code intents. It decomposes method names and applies semantic synonym mapping to determine permissions.

### Common Mappings:
*   **View**: `get`, `see`, `show`, `index`, `read`, `display`.
*   **Create**: `add`, `store`, `new`, `post`, `insert`.
*   **Update**: `edit`, `patch`, `modify`, `save`, `change`.
*   **Delete**: `remove`, `destroy`, `trash`, `wipe`.

> **Example**: A method named `getStudentDetails()` in a `StudentController` will be automatically mapped to the `student.view` permission slug.

---

## 🔍 The Discovery Scanners

OmniGuard uses chunked scanning to discover permissions without impacting memory performance on budget hosting.

### Supported Discovery Hubs:
1.  **Model Hub**: Detects models implementing `HasOmniGuard`.
2.  **Controller Hub**: Scans public methods in standard Laravel controllers.
3.  **Livewire Hub**: Analyzes Livewire components for public methods and synthesis.
4.  **Attribute Hub**: The most powerful hub, which reads PHP 8 attributes.

---

## 🛠️ Decorating with #[OmniResource]

While heuristics are automatic, you can use the `#[OmniResource]` attribute for 100% precise control and UI metadata.

```php
namespace App\Livewire;

use OmniGuard\Attributes\OmniResource;
use Livewire\Component;

#[OmniResource(group: 'Finance', icon: 'cash')]
class InvoiceManager extends Component
{
    public function recordPayment()
    {
        // OmniGuard maps this to 'invoice.pay' (heuristic)
        // Groups it under 'Finance' and adds the 'cash' icon.
    }
}
```

---

## ⚡ Synchronizing the Registry

To run the brain and populate your permission tables, use the sovereign sync command:

```bash
php artisan omniguard:sync
```

This command scans your designated directories (configured in `omniguard.php`) and reconciles the heuristic map with your database. It handles:
*   ✨ Adding new permissions found in code.
*   🗑️ Marking old permissions as "Ghost" mode if they are no longer found.
*   💾 Caching the results for $O(1)$ performance.

---

## 🛰️ Next Step: JIT Performance
Learn how OmniGuard achieves industrial-grade speed using the **[Bitmasking Guide](bitmasking.md)**.
