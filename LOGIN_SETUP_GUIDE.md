# Login Functionality Setup Guide

## ✅ What Has Been Implemented

### 1. **Login Controller** (`app/Http/Controllers/LoginController.php`)
- `showLogin()` - Display login form
- `authenticate()` - Handle login submission with email/password validation
- `logout()` - Destroy session and log out user
- Indonesian error messages

### 2. **Routes** (`routes/web.php`)
- `GET /admin/login` - Show login form
- `POST /admin/login` - Process login
- `POST /admin/logout` - Logout (protected by auth middleware)
- Protected admin routes with auth middleware

### 3. **User Model** (`app/Models/User.php`)
- `role` field (superadmin | staff)
- `is_active` field for user status
- Helper methods: `isAdmin()`, `isSuperAdmin()`
- Relationships: hasMany orders, hasMany chatMessages

### 4. **UserSeeder** (`database/seeders/UserSeeder.php`)
- Creates test admin account
- Creates test staff account

### 5. **Login View** (`resources/views/admin/login.blade.php`)
- Email input field
- Password input with visibility toggle
- Remember me checkbox
- Error display for failed login
- Form preservation (old email value)
- CSRF token

### 6. **Styling** 
- Error box styling in `public/css/admin/login.css`
- Red background with error message
- Responsive design

### 7. **Admin Layout** (`resources/views/layouts/admin.blade.php`)
- Shows logged-in user name and role
- Logout button (form-based, not link)
- Protected routes (auth middleware)

### 8. **JavaScript** (`public/js/admin/login.js`)
- Password visibility toggle
- Simple, no validations (handled by server)

---

## 📋 Test Credentials

After seeding, use these to login:

**Super Admin:**
- Email: `admin@cirengapaweh.com`
- Password: `admin123456`

**Staff:**
- Email: `staff@cirengapaweh.com`
- Password: `staff123456`

---

## 🚀 Setup Steps

### Step 1: Run Migrations (if not done yet)
```powershell
php artisan migrate
```

### Step 2: Seed Test Users
```powershell
php artisan db:seed
```

**Or seed specific seeder:**
```powershell
php artisan db:seed --class=UserSeeder
```

### Step 3: Test Login
1. Go to `http://localhost:8000/admin/login`
2. Enter test credentials above
3. Should redirect to `/admin/dashboard`

### Step 4: Test Logout
1. Click "Keluar" button in admin sidebar
2. Should redirect to `/admin/login`
3. Try accessing `/admin/dashboard` directly - should redirect to login

---

## 🔒 Security Features

### Authentication Guard
- Uses Laravel's `web` guard (session-based)
- Passwords hashed with bcrypt

### Middleware Protection
- `guest` middleware on login routes (prevents already-logged-in users from seeing login page)
- `auth` middleware on protected routes (redirects to login if not authenticated)

### CSRF Protection
- All forms include `@csrf` token
- Regenerates session on login

### Session Regeneration
- Session ID regenerated on successful login
- Session invalidated on logout

---

## 📝 How to Add More Users

### Option 1: Via Seeder
Add to `UserSeeder.php`:
```php
User::firstOrCreate(
    ['email' => 'new@example.com'],
    [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => Hash::make('password123'),
        'role' => 'staff',
        'is_active' => true,
    ]
);
```

### Option 2: Via Tinker (Quick Test)
```powershell
php artisan tinker
```

```php
User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password123'),
    'role' => 'staff',
    'is_active' => true,
]);
```

### Option 3: Via Artisan Command (Future)
Create a command to add users via CLI

---

## 🧪 Testing Scenarios

### Test 1: Valid Login
1. Go to `/admin/login`
2. Enter admin credentials
3. ✅ Should redirect to `/admin/dashboard`

### Test 2: Invalid Password
1. Go to `/admin/login`
2. Enter valid email, wrong password
3. ✅ Should show error message and stay on login page

### Test 3: Non-existent Email
1. Go to `/admin/login`
2. Enter non-existent email
3. ✅ Should show error message

### Test 4: Logout
1. Login successfully
2. Click "Keluar" button
3. ✅ Should redirect to `/admin/login`
4. ✅ Dashboard should not be accessible without login

### Test 5: Remember Me
1. Login with "Remember me" checked
2. Close browser
3. ✅ Should remember login (cookie lasts 1 year)

### Test 6: Direct Access Protection
1. Logout
2. Try to access `/admin/dashboard` directly
3. ✅ Should redirect to `/admin/login`

---

## 📂 Files Modified/Created

### New Files
- ✅ `database/seeders/UserSeeder.php` - Test user seeder

### Modified Files
- ✅ `app/Http/Controllers/LoginController.php` - Complete login/logout logic
- ✅ `app/Models/User.php` - Added role, is_active, relationships, helper methods
- ✅ `routes/web.php` - Added login/logout routes with middleware
- ✅ `resources/views/admin/login.blade.php` - Added error display and form preservation
- ✅ `public/css/admin/login.css` - Added error box styling
- ✅ `public/js/admin/login.js` - Simplified to only password visibility
- ✅ `resources/views/layouts/admin.blade.php` - Show user info, proper logout button
- ✅ `database/seeders/DatabaseSeeder.php` - Call UserSeeder

---

## 🔧 Configuration Reference

### Auth Guard (config/auth.php)
```php
'defaults' => [
    'guard' => env('AUTH_GUARD', 'web'),
    'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
],
```

### Session Driver (.env)
```
SESSION_DRIVER=file
```

### Password Reset (optional, not implemented)
Can be added to forgot password feature later

---

## 📞 How to Use in Controllers

### Check if user is logged in
```php
if (auth()->check()) {
    $user = auth()->user(); // Get logged-in user
}
```

### Protect controller method with middleware
```php
public function __construct()
{
    $this->middleware('auth');
}
```

### Check user role
```php
if (auth()->user()->isAdmin()) {
    // User is admin
}

if (auth()->user()->isSuperAdmin()) {
    // User is superadmin
}
```

### Get logged-in user in blade
```blade
{{ auth()->user()->name }}
{{ auth()->user()->role }}
```

---

## 🎯 Next Steps

1. ✅ Test login/logout with provided credentials
2. Create user management page (CRUD for users)
3. Add role-based authorization (not just authentication)
4. Create password reset functionality
5. Add login attempt rate limiting
6. Add audit logging for admin actions

---

## 🆘 Troubleshooting

### "Route [admin.login] not defined"
- Make sure routes are cached: `php artisan route:cache`

### "User not found in database"
- Run seeder: `php artisan db:seed --class=UserSeeder`
- Check your test credentials

### Password doesn't work
- Make sure password was hashed with `Hash::make()`
- Test users use the passwords above, not plaintext

### Can't logout
- Check POST method is used in form
- Check CSRF token is present

### Session not persisting
- Check `.env` has `SESSION_DRIVER=file`
- Clear cookies in browser

---

**Login functionality is complete and ready for testing!** 🎉
