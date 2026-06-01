# Database Migration Checklist

## ✅ What's Been Created

### Migrations (in `database/migrations/`)
- [x] 0001_01_02_000000_add_role_to_users_table.php
- [x] 0001_01_03_000000_create_products_table.php
- [x] 0001_01_04_000000_create_promos_table.php
- [x] 0001_01_05_000000_create_partner_locations_table.php
- [x] 0001_01_06_000000_create_orders_table.php
- [x] 0001_01_07_000000_create_payments_table.php
- [x] 0001_01_08_000000_create_deliveries_table.php
- [x] 0001_01_09_000000_create_chat_tables.php

### Models (in `app/Models/`)
- [x] Promo.php (with relationships)
- [x] Order.php (with relationships)
- [x] OrderItem.php (with relationships)
- [x] Payment.php (with relationships)
- [x] Delivery.php (with relationships)
- [x] OrderStatusHistory.php (with relationships)
- [x] ChatSession.php (with relationships)
- [x] ChatMessage.php (with relationships)
- [x] Product.php (updated with relationships)
- [x] PartnerLocation.php (already existed)

### Documentation
- [x] DATABASE_SYNC_GUIDE.md (setup instructions)
- [x] .github/copilot-instructions.md (updated)

---

## 📋 Your Next Steps

### 1. Backup Your Data
```sql
-- In phpMyAdmin, run this to create a backup script
-- Or use MySQL Workbench to export
```

### 2. Clean Up Old Tables (if starting fresh)
```sql
-- Drop in this order (respects foreign keys):
DROP TABLE IF EXISTS order_status_histories;
DROP TABLE IF EXISTS chat_messages;
DROP TABLE IF EXISTS chat_sessions;
DROP TABLE IF EXISTS deliveries;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS promo_products;
DROP TABLE IF EXISTS promos;
DROP TABLE IF EXISTS partner_locations;
DROP TABLE IF EXISTS products;

-- Keep users table! It has your data.
```

### 3. Run Migrations
```powershell
# Migrate from PowerShell
php artisan migrate

# Check status
php artisan migrate:status
```

### 4. Test Models
```powershell
# Open Laravel Tinker
php artisan tinker

# Test a model
Product::count()
Order::with(['user', 'items'])->first()
```

### 5. Verify Foreign Keys
In phpMyAdmin, check that these exist:
- `promo_products` has FK to `promos` and `products`
- `order_items` has FK to `orders` and `products`
- `orders` has FK to `users`
- `payments` has FK to `orders`
- `deliveries` has FK to `orders`
- `chat_messages` has FK to `chat_sessions` and `users`

---

## 🔄 Alternative: Keep Existing Tables

If your existing MySQL tables are already correct, you can:

1. **Don't run migrations** (skip step 3 above)
2. **Just use the Models** - Laravel will work with your existing tables
3. **Record the migration status** by running:
   ```powershell
   php artisan migrate --pretend  # Show what would run without actually running
   ```

---

## 🚨 If Something Goes Wrong

### Rollback (Undo) Last Migration
```powershell
php artisan migrate:rollback

# Or rollback specific batch
php artisan migrate:rollback --step=1
```

### Rollback Everything
```powershell
php artisan migrate:reset  # Drops ALL tables!
```

### Check What Migrations Exist
```powershell
php artisan migrate:status
```

### Re-run All Migrations
```powershell
php artisan migrate:refresh  # Resets then re-migrates everything
```

---

## 📚 Key Files to Review

1. **DATABASE_SYNC_GUIDE.md** - Full setup and troubleshooting guide
2. **.github/copilot-instructions.md** - Updated architecture docs
3. **database/migrations/** - All 8 new migration files
4. **app/Models/** - All 9 model files (8 new + 1 updated)

---

## 💡 Tips

- **Always backup before migrating production data**
- **Test migrations locally first** (you're using SQLite for testing)
- **Foreign keys enforce data integrity** - you can't delete a product if it's in an order
- **Models handle everything** - no need to write raw SQL queries
- **Timestamps are automatic** - created_at and updated_at update themselves

---

**Status: Ready to Migrate!** 🚀

When you're ready, follow steps 1-3 above and share the output if you hit any errors.
