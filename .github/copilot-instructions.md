# Cireng A'paweh Web Platform - AI Agent Guidelines

## Project Overview
**Cireng A'paweh** is a Laravel 13 web platform for a Sundanese snack brand (Cireng). It features a dual-interface architecture:
- **Customer-facing** web (responsive UI with Blade templates, Tailwind CSS, vanilla JS)
- **Admin CMS Dashboard** (product, location, and promo management via JSON APIs)

**Tech Stack:** Laravel 13 (PHP 8.3), Vite (Tailwind + JS bundling), SQLite (testing/local)

**Current Branch:** `cahya` (team development branch - never commit to `master` directly)

---

## Architecture & Key Components

### 1. **Route Structure** (`routes/web.php`)
- **Customer routes:** `/`, `/detail-produk`, `/checkout` (GET/POST)
- **Admin routes:** `/admin/*` prefix with resource controllers for:
  - `ProductController` (CRUD `/admin/produk`)
  - `LocationController` (CRUD `/admin/lokasi`)
  - (Promo, User management views exist but controllers TBD)

### 2. **Core Models** (PSR-4 autoload: `App\`)
| Model | Table | Key Fields | Relationships |
|-------|-------|-----------|---|
| `Product` | products | name, price, category (fast_food\|frozen_food), image, is_active, description | hasMany(OrderItem), belongsToMany(Promo) |
| `PartnerLocation` | partner_locations | name, address, operating_hours, image, is_active | — |
| `User` | users | name, email, password, role (superadmin\|staff), is_active | hasMany(Order), hasMany(ChatMessage) |
| `Promo` | promos | title, image, discount_type, discount_value, start_date, end_date, is_active | belongsToMany(Product, promo_products) |
| `Order` | orders | invoice_number, customer_name, customer_email, shipping_address, total_amount, status, user_id | belongsTo(User), hasMany(OrderItem), hasOne(Payment), hasOne(Delivery), hasMany(OrderStatusHistory) |
| `OrderItem` | order_items | order_id, product_id, quantity, unit_price | belongsTo(Order), belongsTo(Product) |
| `Payment` | payments | order_id, transaction_id, payment_type, amount, status | belongsTo(Order) |
| `Delivery` | deliveries | order_id, courier_name, tracking_number, status | belongsTo(Order) |
| `ChatSession` | chat_sessions | customer_name, customer_phone, status (open\|closed) | hasMany(ChatMessage) |
| `ChatMessage` | chat_messages | session_id, user_id, sender_type, message | belongsTo(ChatSession), belongsTo(User) |

**Status:** All migrations created (9 migration files). See `DATABASE_SYNC_GUIDE.md` for setup instructions.

### 3. **Controllers Pattern**
- Return **JSON responses** (not views) for admin operations: `{ success: bool, message: string, errors?: [...] }`
- HTTP status codes: 201 (created), 422 (validation), 500 (server error)
- **File uploads:** Store in `storage/assets/img/produk` via `store()` method
- All controllers wrap try-catch for error logging via `\Log::error()`

**Example (ProductController):**
```php
public function store(Request $request)
{
    $validated = $request->validate([...]);
    $imagePath = $request->file('image')->store('assets/img/produk', 'public');
    Product::create([...]);
    return response()->json(['success' => true, ...], 201);
}
```

### 4. **View Organization** (`resources/views/`)
```
layouts/
  app.blade.php          (customer site template)
  admin.blade.php        (admin dashboard template)
pages/
  index.blade.php        (homepage)
  produk.blade.php       (product detail page)
  checkout.blade.php     (checkout flow)
components/
  navbar, hero, promo, footer, ctaWA (customer components)
  checkout/order-item.blade.php, address-card.blade.php
admin/
  dashboard.blade.php, produk.blade.php, lokasi.blade.php, etc.
```

**Blade conventions:**
- Use `@forelse($items as $item)` for null-safe loops
- Asset paths: `{{ asset('assets/img/...') }}` or `{{ asset('storage/...') }}` for uploads
- Currency formatting: `{{ number_format($price, 0, ',', '.') }}` (Indonesian locale)

### 5. **Frontend Architecture**

**CSS Strategy (Native CSS Variables):**
- Global variables in `public/css/global.css`:
  - Colors: `--charcoal-grey`, `--clean-white`, `--primary-brand-red`, `--accent-cheese-yellow`
  - Spacing: `--gap-small`, `--gap-medium`, `--gap-large`
  - Shadows: `--shadow-main`, `--shadow-lighter`
- **No Tailwind in customer pages**; **Tailwind 4.0 in admin** (via Vite plugin)
- Responsive breakpoints use flexbox + media queries (no Bootstrap grid for customer)

**JavaScript Patterns:**
- **Admin:** Vanilla JS with Fetch API for async CRUD (`public/js/admin/produk.js`, `lokasi.js`)
- **Modals:** CSS class toggle `.show` for visibility (e.g., `productModal.classList.toggle('show')`)
- **Form submission:** POST via FormData + CSRF token from input with name `_token`
- **Search:** Client-side filtering via `textContent.includes(searchTerm)`

**Vite Config (`vite.config.js`):**
```js
input: ['resources/css/app.css', 'resources/js/app.js']
// Tailwind 4.0 + Laravel plugin (auto-refresh on Blade changes)
```

---

## Development Workflows

### Setup & Running
```powershell
# Initial setup
composer run-script setup

# Development server (hot reload, concurrent processes)
composer run-script dev
# Runs: Laravel serve + queue listener + pail logs + Vite dev server

# Tests
composer run-script test
```

### Database & Migrations
- **Local DB:** SQLite (`:memory:` in tests, file-based for dev)
- **Create new migration:** `php artisan make:migration create_products_table`
- **Run migrations:** `php artisan migrate`
- **Seed data:** `php artisan db:seed` (use `database/seeders/DatabaseSeeder.php`)

### Testing
- **PHPUnit config:** `phpunit.xml` (Unit + Feature test suites)
- **Test database:** SQLite in-memory with schema auto-refresh
- **Run:** `php artisan test` or `./vendor/bin/phpunit tests/Feature/YourTest.php`

---

## Critical Patterns & Conventions

### 1. **File Upload Handling**
Files stored in **public disk** (`storage/app/public`) with versioned paths:
```php
$imagePath = $request->file('image')->store('assets/img/produk', 'public');
// Returns: 'assets/img/produk/filename.png'
// Access in views: {{ asset('storage/' . $product->image) }}
// Symlink: `php artisan storage:link` (ensures public/storage → storage/app/public)
```

### 2. **JSON API Responses**
Always return JSON from admin endpoints (even for validation errors):
```php
return response()->json([
    'success' => false,
    'message' => 'Validasi gagal',
    'errors' => $e->errors()  // Includes per-field errors
], 422);
```

### 3. **Model Attribute Casting**
```php
protected $casts = [
    'is_active' => 'boolean',      // Automatic bool coercion
    'price' => 'integer',           // Cast to int (no decimals)
    'created_at' => 'datetime',     // Carbon instance
];
```

### 4. **Localization (Indonesian)**
- Currency: `number_format($price, 0, ',', '.')` → "Rp 15.000" (no decimals)
- All UI text in views is Indonesian; English only in code comments
- Date/time uses Carbon (see config if locale needs setting)

### 5. **Form Method Spoofing**
Blade forms use `@csrf` macro + hidden `_method` field for PUT/DELETE:
```html
<form action="/admin/produk/{{ $id }}" method="POST">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    <!-- form fields -->
</form>
```
JS equivalent (Fetch API):
```js
const formData = new FormData(form);
formData.append('_method', 'PUT');  // For PUT/DELETE via POST
```

### 6. **Image Validation**
```php
'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
// JPEG, PNG, GIF only; 2MB max
```

---

## Team Conventions (From README)

### Git Workflow
- **Branch per developer:** Work on `<your-name>` branch (e.g., `cahya`, `ansyah`)
- **Never push to `master`** (reserved for releases)
- **Commit types:** `feat:`, `fix:`, `refactor:`, `docs:`, etc.

### Line Ending & Case Sensitivity
- **Windows:** `git config --global core.autocrlf true`
- **Linux/Mac:** `git config --global core.autocrlf input`
- **File naming:** Lowercase + hyphens (e.g., `logo-utama.png`, NOT `LogoUtama.png`)

---

## Key Files by Purpose

| Purpose | File(s) |
|---------|---------|
| **Database schema** | `database/migrations/*.php` |
| **Models + relationships** | `app/Models/*.php` |
| **Admin logic** | `app/Http/Controllers/*.php` |
| **Routes definition** | `routes/web.php` |
| **Admin UI styling** | `public/css/admin/*.css` |
| **Customer styling** | `public/css/*.css`, `public/css/page/*.css`, `public/css/components/*.css` |
| **Admin JS interactions** | `public/js/admin/*.js` |
| **Blade templates** | `resources/views/**/*.blade.php` |
| **Frontend build config** | `vite.config.js`, `resources/css/app.css`, `resources/js/app.js` |
| **Dependencies** | `composer.json`, `package.json` |

---

## Common Tasks

### Add a New Admin Page (e.g., Promo Management)
1. **Create controller:** `php artisan make:controller PromoController`
2. **Add routes:** In `routes/web.php` under `/admin` prefix
3. **Create view:** `resources/views/admin/promo.blade.php`
4. **Add admin menu:** Link in admin layout (TBD: no unified nav component yet)
5. **Add JS handlers:** `public/js/admin/promo.js` (follow ProductController pattern)

### Add a Product Field (e.g., Nutrition Info)
1. **Create migration:** `php artisan make:migration add_nutrition_to_products_table`
2. **Update model:** Add field to `$fillable` in `Product::class`
3. **Update controller:** Add validation rule for new field
4. **Update view:** Form input + table column in `admin/produk.blade.php`
5. **Update JS:** Handle new field in `public/js/admin/produk.js`

### Styling a New Component
- **Admin:** Use Tailwind classes (in Blade, wrapped via `@apply` or inline)
- **Customer:** Use CSS variables from `global.css` + responsive flexbox
- **Icons:** Material Design Icons (`<span class="material-symbols-outlined">icon_name</span>`)

---

## Notes & Gotchas

1. **Missing Migrations:** `products` and `partner_locations` tables may not have dedicated migrations—verify they exist in DB before running operations.
2. **Admin Auth:** Login UI exists (`admin.login`) but no guard configured yet; check `config/auth.php` before assuming admin authentication is active.
3. **Images without symlink:** If uploads break, run `php artisan storage:link` to create public symlink.
4. **Concurrent dev server:** `composer run-script dev` runs 4 processes—use separate terminals if troubleshooting specific components.
5. **Session driver:** Uses `file` in dev (check `.env`); don't assume in-memory caching for user data.

---

## Resources
- Laravel 13 docs: https://laravel.com/docs/13.x
- Blade templating: https://laravel.com/docs/13.x/blade
- Vite + Tailwind: https://vitejs.dev + https://tailwindcss.com
- Fetch API: MDN Web Docs

---

**Last updated:** June 1, 2026 | Branch: `cahya`
