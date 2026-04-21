# SaaS & Multi-Tenancy (OmniTenant)

OmniGuard is natively built for SaaS. Its **OmniTenant Engine** allows you to isolate permissions and roles within a specific tenant context (Team, School, Organization) without writing custom database scopes.

## Enabling Teams

In `config/omniguard.php`, enable the `teams` flag:

```php
'teams' => true,
'column_names' => [
    'team_foreign_key' => 'team_id',
],
```

---

## The Tenant Scope

When `teams` is enabled, all authorization checks become **Context-Aware**. OmniGuard will automatically filter permissions and roles by the current "Active Tenant ID."

### Setting the Active Tenant

You can set the tenant globally using the `setPermissionsTeamId` helper:

```php
setPermissionsTeamId($organization->id);
```

Once set, all calls like `$user->hasPermissionTo('billing.view')` will only look for that permission within the database records associated with that specific `team_id`.

---

## Global vs. Local Roles

OmniGuard supports **Sovereign Global Roles**. In your database, if a Role has a `null` team_id, it is considered "Global Authority" and is accessible to users across all tenants.

| Role | Team ID | Access Level |
| :--- | :--- | :--- |
| `Super Admin` | `null` | All Tenants |
| `Organization Manager` | `101` | Only Tenant 101 |

---

## Multi-Tenant Middleware

To automate this, OmniGuard suggests a central middleware that identifies the tenant from the URL or session:

```php
namespace App\Http\Middleware;

use Closure;

class ScopeOmniGuard
{
    public function handle($request, Closure $next)
    {
        if ($tenant = $request->user()?->active_tenant_id) {
            setPermissionsTeamId($tenant);
        }

        return $next($request);
    }
}
```

---

## Next Steps

Learn how OmniGuard achieves industrial performance at scale in **[Performance: JIT Bitmasking](bitmasking.md)**.
