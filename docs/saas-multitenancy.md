# SaaS & Multi-Tenancy

OmniGuard includes helpful support for SaaS applications. You can easily keep permissions and roles isolated for different teams, schools, or organizations.

## Enabling Teams

In your `config/omniguard.php` file, just enable the teams feature:

```php
'teams' => true,
'column_names' => [
    'team_foreign_key' => 'team_id',
],
```

---

## Keeping Things Isolated

When teams are enabled, OmniGuard will automatically filter permissions based on the current team you are working with.

### Setting the current team

You can tell OmniGuard which team is currently active with a simple helper:

```php
setPermissionsTeamId($team->id);
```

Once this is set, everything else happens automatically.

---

## Roles: Global vs. Team-specific

OmniGuard is flexible with how you manage roles:
- **Global Roles**: If a role has no `team_id`, it can be used anywhere in your application.
- **Team Roles**: If a role has a `team_id`, it only works for that specific team.

---

## Easy Automation

We suggest using a simple middleware to set the team automatically for your users:

```php
namespace App\Http\Middleware;

use Closure;

class ScopeOmniGuard
{
    public function handle($request, Closure $next)
    {
        if ($teamId = $request->user()?->active_team_id) {
            setPermissionsTeamId($teamId);
        }

        return $next($request);
    }
}
```
