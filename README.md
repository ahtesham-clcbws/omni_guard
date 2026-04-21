# 🛡️ OmniGuard

**A simple, helpful authorization manager for Laravel (11/12/13).**

OmniGuard is a tool designed to help you manage roles and permissions in your Laravel applications without adding too much complexity. It focuses on being lightweight, easy to understand, and efficient enough to run on any hosting environment.

---

## 👋 How it helps you
OmniGuard is built to be a "helping hand" for developers:
- **Easy Setup**: Get up and running in minutes with a simple installation command.
- **Auto-Discovery**: It can automatically look at your code and suggest permissions for you, saving you from manual entry.
- **Fast Performance**: Uses a simple bitmasking system to keep your application snappy even if you have many permissions.
- **Shared Hosting Friendly**: Designed to use very little memory, so it works perfectly on budget hosting.
- **Safe Modes**: Includes "Panic Mode" to quickly lock down your site and "Ghost Mode" to test changes safely.

---

## 📦 Getting Started

To add OmniGuard to your project:

```bash
composer require omniguard/omniguard
php artisan omniguard:install
php artisan migrate
```

Then, just add the `HasOmniGuard` trait to your User model:

```php
use OmniGuard\Traits\HasOmniGuard;

class User extends Authenticatable
{
    use HasOmniGuard;
}
```

---

## 🛠️ Common Usage

### In your Blade views
Use the simple `@omniguard` directive to show or hide parts of your UI:
```blade
@omniguard('edit.posts')
    <button>Edit Post</button>
@else
    <span>View only</span>
@endomniguard
```

### In your code
Registering your controllers for auto-discovery is easy:
```php
#[OmniResource(group: 'Blog')]
class PostController extends Controller
{
    // OmniGuard will help manage your permissions here
}
```

---

## 📖 Learn More
We have prepared easy-to-follow guides in the **[docs/](docs/v1/index.md)** folder to help you with everything from installation to multi-tenancy.

---

## 💎 Credits & Support
This project is maintained with care by **[Ahtesham](https://github.com/ahtesham-clcbws)** and **[Broadway Web Services](https://www.clcbws.com)**. We are always happy to help if you have questions or need assistance.

- **WhatsApp**: [+91 9810763314](https://wa.me/919810763314)
- **Email**: support@clcbws.com

---

## 📄 License
OmniGuard is proprietary software from Broadway Web Services. You are welcome to use it exactly as provided under our standard license terms.
