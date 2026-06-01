# Quick Start Guide - Database Integration

## 🎯 TL;DR (Too Long; Didn't Read)

You created tables in phpMyAdmin. Now you have migrations to track them in Git. Here's what to do:

### Step 1: Backup (Do This First!)
```sql
-- In phpMyAdmin SQL tab, copy-paste this to export your data:
mysqldump -u root db_cirengapaweh > backup.sql
```

### Step 2: Delete Old Tables (if you want fresh migrations)
```sql
DROP TABLE order_status_histories;
DROP TABLE chat_messages;
DROP TABLE chat_sessions;
DROP TABLE deliveries;
DROP TABLE payments;
DROP TABLE order_items;
DROP TABLE orders;
DROP TABLE promo_products;
DROP TABLE promos;
DROP TABLE partner_locations;
DROP TABLE products;
-- KEEP users table!
```

### Step 3: Run Migrations
```powershell
php artisan migrate
```

### Step 4: Verify It Worked
```powershell
php artisan migrate:status
# Should show all green ✓ checks
```

---

## What Changed in Your Project

### ✅ Added
- 8 migration files (database/migrations/)
- 9 model files with relationships (app/Models/)
- 4 documentation files

### 🔄 Updated
- Product.php model (added relationships)
- .github/copilot-instructions.md (updated architecture docs)

### ❌ Unchanged
- All your existing views, controllers, routes
- Your phpMyAdmin data (if you keep the tables)

---

## File Structure After Setup

```
cirengapaweh-web/
├── database/
│   └── migrations/
│       ├── 0001_01_00_000000_create_users_table.php (existing)
│       ├── 0001_01_01_000001_create_cache_table.php (existing)
│       ├── 0001_01_01_000002_create_jobs_table.php (existing)
│       ├── 0001_01_02_000000_add_role_to_users_table.php ✨ NEW
│       ├── 0001_01_03_000000_create_products_table.php ✨ NEW
│       ├── 0001_01_04_000000_create_promos_table.php ✨ NEW
│       ├── 0001_01_05_000000_create_partner_locations_table.php ✨ NEW
│       ├── 0001_01_06_000000_create_orders_table.php ✨ NEW
│       ├── 0001_01_07_000000_create_payments_table.php ✨ NEW
│       ├── 0001_01_08_000000_create_deliveries_table.php ✨ NEW
│       └── 0001_01_09_000000_create_chat_tables.php ✨ NEW
├── app/
│   └── Models/
│       ├── User.php (existing)
│       ├── Product.php (updated with relationships)
│       ├── PartnerLocation.php (existing)
│       ├── Promo.php ✨ NEW
│       ├── Order.php ✨ NEW
│       ├── OrderItem.php ✨ NEW
│       ├── Payment.php ✨ NEW
│       ├── Delivery.php ✨ NEW
│       ├── OrderStatusHistory.php ✨ NEW
│       ├── ChatSession.php ✨ NEW
│       └── ChatMessage.php ✨ NEW
├── DATABASE_SETUP_SUMMARY.md ✨ NEW (This file!)
├── DATABASE_SYNC_GUIDE.md ✨ NEW (Detailed setup)
├── MIGRATION_CHECKLIST.md ✨ NEW (Checklist)
└── SCHEMA_REFERENCE.md ✨ NEW (SQL reference)
```

---

## Common Questions

### Q: Will migrations affect my existing data?
**A:** Only if you delete the tables first. If you keep your existing tables and just add models, your data is safe.

### Q: Do I have to run migrations?
**A:** No! If your tables are already correct in phpMyAdmin, you can just use the models. Migrations are for version control and team collaboration.

### Q: What if I mess up?
**A:** Just run `php artisan migrate:rollback` and it will undo the last batch of migrations. Your data isn't deleted, just the schema changes are reversed.

### Q: Can I see what migrations will do before running them?
**A:** Yes! Run `php artisan migrate --pretend` to see what SQL will execute without actually running it.

### Q: How do I use the models?
**A:** In your controllers or artisan tinker:
```php
// Get a product
$product = Product::find(1);

// Create an order
$order = Order::create([...]);

// Get all orders for a user
$orders = User::find(1)->orders;

// Get order with items
$order = Order::with('items.product')->find(1);
```

---

## Troubleshooting

### Migration fails with "Table already exists"
```powershell
# Either delete the table in phpMyAdmin first:
# DROP TABLE products;

# Or tell Laravel to ignore it:
php artisan migrate --force
```

### "Foreign key constraint fails"
This means parent table doesn't exist yet. Check migration order—they run in alphabetical order by timestamp.

### "Class not found" error
Run `composer dump-autoload` to refresh PHP's class loader.

### Want to undo everything?
```powershell
php artisan migrate:reset
# Drops ALL tables (including users!)

# Then re-run:
php artisan migrate
```

---

## What Each Migration Does

| File | Purpose |
|------|---------|
| `add_role_to_users_table.php` | Adds role and is_active columns to users |
| `create_products_table.php` | Creates products table |
| `create_promos_table.php` | Creates promos and promo_products tables |
| `create_partner_locations_table.php` | Creates partner_locations table |
| `create_orders_table.php` | Creates orders and order_items tables |
| `create_payments_table.php` | Creates payments table |
| `create_deliveries_table.php` | Creates deliveries and order_status_histories tables |
| `create_chat_tables.php` | Creates chat_sessions and chat_messages tables |

---

## Model Relationships Cheat Sheet

```php
// Get related data
$order->items;              // OrderItems in order
$order->user;               // User who placed order
$order->payment;            // Payment record
$order->delivery;           // Delivery info
$order->statusHistories;    // Status change history

$product->promos;           // Promos this product is in
$product->orderItems;       // All orders containing this product

$promo->products;           // Products in this promo

$user->orders;              // User's orders
$user->chatMessages;        // User's chat messages

$session->messages;         // Messages in chat session
$message->user;             // User who sent message (admin)
$message->session;          // Chat session
```

---

## Next Steps

1. ✅ Read this file (you're doing it!)
2. 📖 Read DATABASE_SYNC_GUIDE.md for details
3. 🔄 Run `php artisan migrate`
4. ✓ Check `php artisan migrate:status`
5. 🧪 Test with `php artisan tinker` → `Product::count()`
6. 📝 Create controllers for new features
7. 🚀 Build admin pages
8. 💳 Integrate Midtrans

---

## You're Ready! 🚀

Everything is set up. Just run `php artisan migrate` and you're good to go!

For detailed help, read:
- **DATABASE_SYNC_GUIDE.md** - Complete reference
- **SCHEMA_REFERENCE.md** - Expected database structure
- **.github/copilot-instructions.md** - Architecture overview
