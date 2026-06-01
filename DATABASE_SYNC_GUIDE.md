# Database Migration & Sync Guide for Cireng A'paweh

## Overview
You've manually created tables in phpMyAdmin, and now we're creating Laravel migrations to match them. This guide explains how to connect everything together.

---

## What Was Created

### 📦 Migrations (8 new files)
Located in `database/migrations/`:

1. **0001_01_02_000000_add_role_to_users_table.php** - Updates the users table with `role` and `is_active` columns
2. **0001_01_03_000000_create_products_table.php** - Products table
3. **0001_01_04_000000_create_promos_table.php** - Promos + many-to-many junction table (promo_products)
4. **0001_01_05_000000_create_partner_locations_table.php** - Partner locations table
5. **0001_01_06_000000_create_orders_table.php** - Orders + order_items tables
6. **0001_01_07_000000_create_payments_table.php** - Payments table
7. **0001_01_08_000000_create_deliveries_table.php** - Deliveries + order_status_histories tables
8. **0001_01_09_000000_create_chat_tables.php** - Chat sessions + chat_messages tables

### 🏗️ Models (7 new files)
Located in `app/Models/`:

- **Promo.php** - Promo model with relationship to products
- **Order.php** - Order model with relationships to user, items, payment, delivery, and status histories
- **OrderItem.php** - Individual order items
- **Payment.php** - Payment information per order
- **Delivery.php** - Delivery tracking per order
- **OrderStatusHistory.php** - Audit trail of order status changes
- **ChatSession.php** - Chat session tracking
- **ChatMessage.php** - Individual chat messages
- **Product.php** - Updated with relationships to promos and order items

---

## How to Proceed

### Step 1: Disable Existing Tables (Backup First!)

Since your tables already exist in MySQL with different structures, you have two options:

#### **Option A: Keep Existing Data** (Recommended)
1. Backup your database first:
   ```bash
   # In phpMyAdmin, export your database
   # or use MySQL dump:
   mysqldump -u root db_cirengapaweh > backup.sql
   ```

2. Drop/rename existing tables to let migrations create them fresh:
   ```sql
   -- In phpMyAdmin SQL tab, run:
   DROP TABLE IF EXISTS order_status_histories;
   DROP TABLE IF EXISTS deliveries;
   DROP TABLE IF EXISTS payments;
   DROP TABLE IF EXISTS order_items;
   DROP TABLE IF EXISTS orders;
   DROP TABLE IF EXISTS chat_messages;
   DROP TABLE IF EXISTS chat_sessions;
   DROP TABLE IF EXISTS promo_products;
   DROP TABLE IF EXISTS promos;
   DROP TABLE IF EXISTS partner_locations;
   DROP TABLE IF EXISTS products;
   ```

3. **Keep the users table** (it has existing data and password hashes)

#### **Option B: Keep Everything As-Is**
If your existing tables match the schema exactly, you can skip migrations and just use the models without running `php artisan migrate`.

---

### Step 2: Run Migrations

If you chose Option A above:

```powershell
# Run all pending migrations
php artisan migrate

# Or run a specific migration file:
php artisan migrate --path=database/migrations/0001_01_02_000000_add_role_to_users_table.php
```

**What this does:**
- Adds `role` and `is_active` columns to the `users` table
- Creates `products`, `promos`, `partner_locations` tables
- Creates order-related tables with proper foreign key relationships
- Creates payment, delivery, and chat tables

---

### Step 3: Verify Your Database

After migrations run, check in phpMyAdmin:
- All tables should exist
- Foreign keys should be properly linked
- Indexes should be created (`idx_products_category`, `idx_orders_status`, etc.)

---

## Using the Models

Now you can query data using Laravel Eloquent:

```php
// Get a product with its promos
$product = Product::with('promos')->find(1);

// Get an order with all its details
$order = Order::with(['user', 'items.product', 'payment', 'delivery', 'statusHistories'])->find(1);

// Create a new order
$order = Order::create([
    'user_id' => auth()->id(),
    'invoice_number' => 'INV-' . time(),
    'customer_name' => 'John Doe',
    'customer_email' => 'john@example.com',
    'customer_phone' => '08123456789',
    'shipping_address' => '123 Main St',
    'total_amount' => 150000,
    'status' => 'unpaid',
]);

// Add items to order
$order->items()->create([
    'product_id' => 1,
    'quantity' => 2,
    'unit_price' => 75000,
]);

// Create payment record
$order->payment()->create([
    'transaction_id' => 'TRX-123',
    'payment_type' => 'credit_card',
    'amount' => 150000,
    'status' => 'settlement',
]);
```

---

## Migration Status

Check migration status anytime:

```powershell
# See all migrations and their status
php artisan migrate:status

# Rollback (undo) last batch of migrations
php artisan migrate:rollback

# Reset everything (drops all tables!)
php artisan migrate:reset

# Refresh (reset + re-run all)
php artisan migrate:refresh
```

---

## Important Notes

1. **If you added migrations, they'll auto-increment:** Migration timestamps ensure they run in order.

2. **Foreign keys:** All relationships are set up with cascading deletes:
   - Deleting a promo will cascade to promo_products
   - Deleting an order will cascade to order_items, payments, deliveries, and status histories
   - Deleting a user will set order.user_id to NULL

3. **Indexes:** Created for fast queries on:
   - `products.category`
   - `orders.invoice_number`, `orders.status`
   - `payments.status`
   - `deliveries.status`

4. **Timestamps:** All tables have `created_at` and `updated_at` automatically managed by Laravel (except `order_status_histories` which uses `changed_at`).

5. **Enums:** Database uses ENUM types for constrained fields (status, category, etc.)—these are type-safe at the database level.

---

## If You Run Into Issues

### "Table already exists"
- Run in phpMyAdmin: `DROP TABLE table_name;`
- Then run `php artisan migrate`

### "Foreign key constraint fails"
- Ensure migrations run in correct order (they have timestamps for this)
- Check parent tables exist before child tables

### "Syntax errors in migration"
- Check the specific migration file for typos
- Run: `php artisan migrate:rollback` to undo the batch
- Fix the migration, then re-run

### Rollback and Start Fresh
```powershell
# This will DROP ALL tables (including users!)
php artisan migrate:reset

# Then re-run all migrations from scratch
php artisan migrate
```

---

## Next Steps

1. **Test the models** - Create a test file to verify relationships work
2. **Update existing controllers** - Use the new models in your Admin controllers
3. **Create controllers for new features** - OrderController, PaymentController, ChatController, etc.
4. **Add form validation** - Create Request classes for each model
5. **Seed test data** - Create a seeder to populate test data

---

**Questions?** Check the status of your migrations with `php artisan migrate:status` and share the output if something goes wrong.
