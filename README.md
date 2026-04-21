# 🛡️ OmniGuard Sovereign Orchestrator

**The Absolute Authority for Laravel (11/12/13).**

OmniGuard is not a wrapper. It is not an extension. It is a **Sovereign Orchestration Brain** that forks, absorbs, and enhances the core intelligence of proven authorization engines into a single, zero-dependency runtime. 

Designed for mission-critical stability and extreme performance on budget **$1 shared hosting**, OmniGuard moves beyond simple CRUD to handle features, services, and hierarchical state-based access.

---

## 💎 The Sovereign Philosophy
OmniGuard operates on the "Fork-and-Absorb" strategy. We have absorbed the core logic of industry standards directly into our namespace to ensure your application remains protected without the bloat of external runtime dependencies.
- **Zero Runtime Dependencies**: Your vendor folder stays clean. No version conflicts.
- **Contract-Based Code**: Strict DTO-driven state management (via `OmniGuard\Data`).
- **Absolute Authority**: Heuristic discovery maps your code to permissions automatically, while giving you 100% override control.

---

## 🚀 Elite Features

### 🧠 Heuristic Intelligence Engine
OmniGuard skips manual configuration. Its brain scans your Models, Controllers, Livewire components, and Blade views, using semantic synonym mapping and fuzzy matching to suggest and map permissions instantly.

### ⚡ JIT Binary Bitmasking
Performance at scale. OmniGuard assigns every permission a unique power-of-two index. Authorization checks use high-speed **Binary Integer Comparison** in Redis/Session for $O(1)$ speed—orders of magnitude faster than conventional string-based array searching.

### 🍃 $1 Hosting Efficiency
Engineered for ultimate parsimony. Our Discovery Engine utilizes **Chunked Scanning** and file-streaming to stay comfortably under the strict memory limits of budget shared hosting environments.

### 🛡️ Panic Mode & Ghost Mode
- **Panic Mode Protocol**: A deterministic fail-safe. On system error, all access reverts to **Strict Denial** (except SuperAdmins).
- **Ghost Mode**: Safely test new roles in production. OmniGuard runs the checks but never denies access—it just logs a "Virtual Denial" for your review.

---

## 🪜 Three-Tier Authorization
1. **Tier 1: Personal Overrides**: Highest priority individual user permissions.
2. **Tier 2: Role Hierarchy**: Roles with `sort_order` ranking and recursive inheritance.
3. **Tier 3: Heuristic Default**: Automatic codebase mapping.

---

## 📦 Installation

```bash
composer require omniguard/omniguard
php artisan omniguard:install
php artisan omniguard:sync
```

---

## 🛠️ Usage

### Blade Directives
```blade
@omniguard('student.view')
    <!-- Authorized content -->
@else
    <!-- Forbidden content -->
@endomniguard
```

### Writing to the Registry
```php
#[OmniResource(group: 'Billing', icon: 'cash')]
class InvoiceManager extends Component
{
    // ...
}
```

---

## 📖 Extended Documentation
The absolute, "Laravel-grade" manual for OmniGuard is available in the **[docs/](docs/v1/index.md)** directory. It covers everything from basic setup to JIT Bitmasking and SaaS orchestration.

---

## 💎 Credits
OmniGuard Sovereign is architected and maintained by **[Ahtesham](https://github.com/ahtesham-clcbws)** and the team at **[Broadway Web Services](https://www.clcbws.com)**, with a major helping hand from **[Gemini](https://deepmind.google/technologies/gemini/) (AI Architect)**.

---

## 📞 Support & Inquiry
For enterprise support, priority feature requests, or custom orchestration consulting, please reach out via:
- **WhatsApp**: [+91 9810763314](https://wa.me/919810763314)
- **Website**: [clcbws.com](https://www.clcbws.com)
- **Email**: support@clcbws.com

---

## 📄 License
The OmniGuard core is released under the [MIT License](LICENSE.md).
