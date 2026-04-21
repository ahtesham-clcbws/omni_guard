# Usage & API Reference

OmniGuard provides a clean, intuitive API for both backend logic and frontend templates.

---

## 🎨 Blade Directives

Use the `@omniguard` directive to hide or show content based on sovereign authority. It automatically handles hierarchy, superadmin status, and bitmasking checks.

### Basic Check
```blade
@omniguard('student.view')
    <p>Welcome to the Student Dashboard.</p>
@else
    <p>Unauthorized Access.</p>
@endomniguard
```

### Multiple Permissions (Any)
```blade
@omniguardAny(['student.view', 'teacher.view'])
    <!-- Content for either role -->
@endomniguardAny
```

---

## 🧱 Livewire Integration

OmniGuard is deeply integrated with Livewire 3 synthesis. You can use direct authorization in your components:

```php
use Livewire\Component;

class StudentManager extends Component
{
    public function delete($id)
    {
        $this->authorize('student.delete'); // Standard Laravel authorization
        
        // ...
    }
}
```

---

## 🛰️ The OmniGuard Facade

The Facade is your main entry point for controlling the orchestrator's state.

### Tenant Context
```php
use OmniGuard\Facades\OmniGuard;

// Set the active tenant
OmniGuard::setTenant($id);

// Check if tenant is active
if (OmniGuard::hasTenant()) { /* ... */ }
```

### System Controls
```php
// Check if system is in Panic Mode
if (OmniGuard::panicMode()) {
    return redirect()->to('maintenance');
}
```

---

## 🎛️ Artisan Commands

OmniGuard provides several critical commands for managing your sovereign registry.

### `omniguard:install`
Initializes the package, publishes config, and prepares migrations.

### `omniguard:sync`
The most important command. It runs the Heuristic Multi-Discovery Hub to scan your codebase and reconcile permissions in the database.

**Flags**:
*   `--force`: Overwrite existing permission metadata.
*   `--dry-run`: See what would be changed without writing to DB.

### `omniguard:bitmask`
Freshly hydrates the JIT Binary Bitmask cache. Automatically run after a sync, but can be triggered manually if cache is corrupted.

---

## 🛡️ Exception Handling

If an authorization check fails, OmniGuard throws an `OmniGuard\Exceptions\UnauthorizedException`. You can catch this in your `App\Exceptions\Handler`:

```php
use OmniGuard\Exceptions\UnauthorizedException;

public function register()
{
    $this->renderable(function (UnauthorizedException $e, $request) {
        return response()->view('errors.403', [], 403);
    });
}
```
