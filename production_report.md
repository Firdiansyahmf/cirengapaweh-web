# Production Readiness Audit & Codebase Analysis
**Project:** Cireng A'Paweh Web App  
**Date:** June 7, 2026  
**Auditor:** Antigravity (Advanced AI Coding Assistant)

This report details security vulnerabilities, logic flaws, mass assignment bugs, and architectural inconsistencies identified across the codebase that should be resolved before deploying to a production environment.

---

## 1. Critical Security Vulnerabilities

### 1.1. CSRF Exclusions Overwrite in `bootstrap/app.php`
* **Risk Level:** **High**  
* **Impact:** External payment webhooks from Midtrans will fail in production with `419 Page Expired` errors, preventing the system from automatically updating order payment statuses.
* **Finding:** In `bootstrap/app.php` (lines 13-17), CSRF exceptions are registered using two separate calls to `$middleware->preventRequestForgery()`:
  ```php
  ->withMiddleware(function (Middleware $middleware): void {
      $middleware->preventRequestForgery(except: ['/payment/webhook']);
      $middleware->preventRequestForgery(except: ['webhooks/biteship']);
  })
  ```
  In Laravel 11's middleware configuration, calling `preventRequestForgery()` multiple times overwrites the internal `$csrfExcept` array instead of merging it. As a result, only `webhooks/biteship` is actually excluded from CSRF protection; the Midtrans webhook `/payment/webhook` remains protected.
* **Recommended Fix:** Combine both exclusions into a single array parameter:
  ```php
  $middleware->preventRequestForgery(except: [
      '/payment/webhook',
      'webhooks/biteship'
  ]);
  ```

---

### 1.2. Bypassed Authorization & Validation on Chat Routes
* **Risk Level:** **Medium-High**  
* **Impact:** Insecure Direct Object Reference (IDOR) & lack of message sanitization. Logged-in non-admin or lower-privileged staff users can view, close, or send chat messages in any live chat session.
* **Finding:** The codebase contains a fully implemented `ChatController.php` which restricts message querying, session closing, and sending to `superadmin` users, as well as validating message content. However, the active chat routes in `routes/web.php` (lines 202-228) bypass this controller entirely and use anonymous closures, such as:
  ```php
  Route::post('/chat-sync/{id}/send', function (Request $request, $id) {
      ChatMessage::query()->create([
          'session_id' => $id,
          'user_id' => Auth::id() ?? 1,
          'message' => $request->message,
          'sender_type' => 'admin',
      ]);
  });
  ```
  This implementation lacks both access checks (allowing any staff user to bypass role checks) and input validation (making it possible to post empty or malformed data).
* **Recommended Fix:** Map the chat routes directly to the controller actions in `ChatController.php` instead of using closures.

---

### 1.3. Logic Bypass on Admin Password Verification
* **Risk Level:** **Medium-High**  
* **Impact:** Bypassed privilege validation & incorrect password checking target.
* **Finding:** In `UserController.php` (lines 158-182), the `verifyPassword` method (used before editing another superadmin's profile) checks the input password against the **target user's password** (`$targetUser->password`), rather than the **current logged-in user's password** (`auth()->user()->password`):
  ```php
  if (!Hash::check($request->password, $targetUser->password)) {
  ```
  Additionally, the method lacks validation to ensure the calling user is a superadmin, and there are two redundant routes mapped to this method in `routes/web.php`.
* **Recommended Fix:** Secure the method by verifying that the caller is a superadmin, and change the hash check to verify the caller's password: `Hash::check($request->password, auth()->user()->password)`.

---

### 1.4. Client-Controlled Price Parameter in Checkout
* **Risk Level:** **High**  
* **Impact:** Price tampering vulnerability. Malicious clients can submit orders for any arbitrary price.
* **Finding:** In `CheckoutController.php` (lines 9-26), the `prepare()` method accepts the product price directly from the request parameters and stores it in the user's session:
  ```php
  $request->validate([
      'product_id' => 'required|integer',
      'product_name' => 'required|string',
      'price' => 'required|numeric',
      'quantity' => 'required|integer|min:1',
  ]);
  ```
* **Recommended Fix:** Do not accept price and name from the request payload. Only accept `product_id` and `quantity` from the client, then retrieve the official name and price from the database before setting the checkout session data.

---

## 2. Important Functional & Logic Bugs

### 2.1. Mass Assignment Bug on Order Creation
* **Risk Level:** **Medium**  
* **Impact:** Order-to-User relationship is permanently lost. Every order in the database is saved with a `null` user association.
* **Finding:** In `PaymentController.php`, `user_id` is passed during the mass assignment of `Order`:
  ```php
  $order = Order::create([
      "user_id" => Auth::id(),
      "invoice_number" => $invoiceNumber,
      ...
  ]);
  ```
  However, `user_id` is not declared in the `$fillable` array of the `Order` model (`app/Models/Order.php`). Consequently, Eloquent discards it during creation.
* **Recommended Fix:** Add `'user_id'` to the `$fillable` array in the `Order` model.

---

### 2.2. Commented-out Postal Code Backend Logic Breaks Biteship Integration
* **Risk Level:** **High (for shipping operations)**  
* **Impact:** Shipment creation will fail for all orders.
* **Finding:** The frontend checkout page collects a postal code, but in `PaymentController.php` (line 56), the backend validation and assignment for `postal_code` are commented out:
  ```php
  /* "postal_code" => "required|string|max:5", */
  ```
  Consequently, `postal_code` is saved as `null` in the database. When `ShippingService.php` attempts to create a shipment in Biteship, it casts `null` to `0`:
  ```php
  'destination_postal_code' => (int)($order->postal_code),
  ```
  This causes the Biteship API to reject the request due to an invalid postal code.
* **Recommended Fix:** Uncomment the validation and database mapping for `postal_code` in `PaymentController.php`.

---

### 2.3. Missing View & Broken Route for `/admin/pengiriman`
* **Risk Level:** **Medium**  
* **Impact:** Route causes a runtime crash.
* **Finding:** The route `/admin/pengiriman` is mapped in `web.php` and calls `PengirimanController@index`. However, the view file `admin.pengiriman` does not exist in the filesystem, causing a `ViewNotFound` exception. It is also missing from the sidebar.
* **Recommended Fix:** Since shipments can be processed and tracked directly via the **Pemesanan** dashboard (which uses `ShippingService` directly), you should delete the dead `PengirimanController.php` and its associated routes to clean up the routing table.

---

## 3. Resource Leakage

### 3.1. Obsolete Image Files Accumulate in Storage
* **Risk Level:** **Low**  
* **Impact:** Server disk space leak.
* **Finding:** When updating or deleting products (in `ProductController.php`) or partner locations (in `LocationController.php`), old image files are never deleted from storage, causing obsolete files to accumulate on the server's disk over time.
* **Recommended Fix:** Use `Storage::disk('public')->delete($oldImagePath)` when updating or deleting records that contain file attachments.

---

## 4. Inconsistent Practices

### 4.1. Non-Idiomatic Laravel HTTP Redirects
* **Finding:** In `PaymentController.php` (lines 401-412), raw PHP headers are used to perform redirects, and execution is halted abruptly using `exit;`:
  ```php
  if (!$invoiceNumber) {
      header("Location: " . url("/"));
      exit;
  }
  ```
  This violates the standard Laravel request/response lifecycle.
* **Recommended Fix:** Return standard Laravel redirect responses:
  ```php
  return redirect('/');
  ```

---

### 4.2. Incomplete Cancelled Orders View
* **Finding:** When an admin cancels an order in the **Pemesanan** tab, its status changes to `cancelled`. However, the view `pemesanan.blade.php` only has tabs for `unpaid`, `paid`, `shipping`, and `completed`. Cancelled orders disappear from the admin UI completely, leaving no trace or log in the CMS for audit purposes.
* **Recommended Fix:** Add a fifth tab ("Dibatalkan") to display cancelled orders, or provide an order audit log page.
