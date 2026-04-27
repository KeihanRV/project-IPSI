# ✅ Setup Checklist - API Product Management

## Pre-Flight Checks

### Database & Storage

- [ ] Database migrated: `php artisan migrate`
- [ ] Storage link created: `php artisan storage:link`
- [ ] `storage/app/public/product/` writable
- [ ] `storage/app/public/variant/` writable

### Laravel Setup

- [ ] `.env` file configured
- [ ] APP_KEY set: `php artisan key:generate`
- [ ] Sanctum installed and configured
- [ ] Authentication working (login page works)

---

## API Routes Verification

### Check Routes are Registered

```bash
php artisan route:list --path=api
```

Expected output should show:

- [ ] `GET /api/products` (public)
- [ ] `GET /api/products/{id}` (public)
- [ ] `GET /api/admin/products` (admin)
- [ ] `POST /api/admin/products` (admin)
- [ ] `GET /api/admin/products/{id}` (admin)
- [ ] `GET /api/admin/products/{id}/edit` (admin)
- [ ] `PUT /api/admin/products/{id}` (admin)
- [ ] `DELETE /api/admin/products/{id}` (admin)
- [ ] `GET /api/admin/products/{id}/variants` (admin)
- [ ] `POST /api/admin/products/{id}/variants` (admin)
- [ ] `GET /api/admin/products/{id}/variants/{id}` (admin)
- [ ] `PUT /api/admin/products/{id}/variants/{id}` (admin)
- [ ] `DELETE /api/admin/products/{id}/variants/{id}` (admin)

### Check Controllers

```bash
php -l app/Http/Controllers/API/ProductController.php
php -l app/Http/Controllers/API/VariantController.php
```

- [ ] No syntax errors in ProductController
- [ ] No syntax errors in VariantController

---

## File Structure Verification

```
app/Http/Controllers/
├── API/
│   ├── ProductController.php        ✅ EXISTS
│   └── VariantController.php        ✅ EXISTS
├── ProductController.php            ✅ EXISTS (unchanged)
└── ...

routes/
├── api.php                          ✅ MODIFIED
├── api/
│   └── products.php                 ✅ EXISTS
├── web.php                          ✅ EXISTS (unchanged)
└── ...

public/js/
└── product-api-client.js            ✅ EXISTS

Documentation/
├── API_DOCUMENTATION.md             ✅ EXISTS
├── IMPLEMENTATION_SUMMARY.md        ✅ EXISTS
├── QUICK_REFERENCE.md               ✅ EXISTS
└── SETUP_CHECKLIST.md               ✅ THIS FILE
```

- [ ] All folders created successfully
- [ ] All files present

---

## Authentication Setup

### Admin User Setup

```bash
php artisan tinker
```

```php
>>> $user = User::first();
>>> $user->is_admin = true;
>>> $user->save();
>>> $token = $user->createToken('admin-token')->plainTextToken;
>>> echo $token;
```

- [ ] Admin user created
- [ ] is_admin field set to true
- [ ] Can generate token

### Middleware Configuration

Check `app/Http/Middleware/` for admin middleware:

```php
// app/Http/Middleware/IsAdmin.php (if doesn't exist, create it)
public function handle($request, Closure $next)
{
    if (auth()->check() && auth()->user()->is_admin) {
        return $next($request);
    }
    return response()->json(['message' => 'Unauthorized'], 403);
}
```

- [ ] Admin middleware exists
- [ ] Admin middleware registered in `app/Http/Kernel.php`

---

## Testing - Public Endpoints

### Test 1: Get All Products (No Auth)

```bash
curl -X GET "http://localhost/api/products" \
  -H "Accept: application/json"
```

Expected:

- [ ] HTTP 200
- [ ] JSON response with products array
- [ ] Pagination info included

### Test 2: Get Single Product (No Auth)

```bash
curl -X GET "http://localhost/api/products/1" \
  -H "Accept: application/json"
```

Expected:

- [ ] HTTP 200
- [ ] Product with ID 1
- [ ] Variants array included

---

## Testing - Admin Endpoints

### Setup: Get Admin Token

1. [ ] Login to dashboard as admin
2. [ ] Or use tinker to generate token (see above)
3. [ ] Copy token value

### Test 3: List Admin Products

```bash
curl -X GET "http://localhost/api/admin/products" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Expected:

- [ ] HTTP 200
- [ ] Products list from admin view
- [ ] All variants included

### Test 4: Get Product for Edit

```bash
curl -X GET "http://localhost/api/admin/products/1/edit" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Expected:

- [ ] HTTP 200
- [ ] Product data with image URLs
- [ ] All variants with image URLs

### Test 5: Create Product (with file upload)

```bash
curl -X POST "http://localhost/api/admin/products" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "title=Test Product" \
  -F "description=Test Description" \
  -F "location=Jakarta" \
  -F "image=@/path/to/image.jpg" \
  -F "variants[0][name]=Size M" \
  -F "variants[0][price]=50000" \
  -F "variants[0][stock]=30" \
  -F "variants[0][image]=@/path/to/variant.jpg"
```

Expected:

- [ ] HTTP 201
- [ ] Product created with ID
- [ ] Image files uploaded
- [ ] Variants created

### Test 6: Update Product

```bash
curl -X PUT "http://localhost/api/admin/products/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "title=Updated Title" \
  -F "variants[0][id]=1" \
  -F "variants[0][name]=Size M" \
  -F "variants[0][price]=60000" \
  -F "variants[0][stock]=25"
```

Expected:

- [ ] HTTP 200
- [ ] Product updated
- [ ] Variants updated

### Test 7: Delete Product

```bash
curl -X DELETE "http://localhost/api/admin/products/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

Expected:

- [ ] HTTP 200
- [ ] Product deleted message
- [ ] Images cleaned up

---

## Testing - Variant Endpoints

### Test 8: Get Variants

```bash
curl -X GET "http://localhost/api/admin/products/1/variants" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

- [ ] HTTP 200
- [ ] Variants list returned

### Test 9: Create Variant

```bash
curl -X POST "http://localhost/api/admin/products/1/variants" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=Size XXL" \
  -F "price=70000" \
  -F "stock=10"
```

- [ ] HTTP 201
- [ ] Variant created

### Test 10: Update Variant

```bash
curl -X PUT "http://localhost/api/admin/products/1/variants/1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -F "name=Size XXL Updated" \
  -F "price=75000" \
  -F "stock=8"
```

- [ ] HTTP 200
- [ ] Variant updated

### Test 11: Delete Variant

```bash
curl -X DELETE "http://localhost/api/admin/products/1/variants/1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

- [ ] HTTP 200
- [ ] Variant deleted

---

## Testing - Error Handling

### Test 12: Unauthorized (No Token)

```bash
curl -X GET "http://localhost/api/admin/products" \
  -H "Accept: application/json"
```

Expected:

- [ ] HTTP 401
- [ ] "Unauthenticated" message

### Test 13: Invalid Token

```bash
curl -X GET "http://localhost/api/admin/products" \
  -H "Authorization: Bearer invalid_token"
```

Expected:

- [ ] HTTP 401
- [ ] Unauthorized message

### Test 14: Non-Admin User

If logged in as non-admin:

```bash
curl -X POST "http://localhost/api/admin/products" \
  -H "Authorization: Bearer non_admin_token" \
  -F "title=Test"
```

Expected:

- [ ] HTTP 403
- [ ] "Unauthorized" message

### Test 15: Validation Error

```bash
curl -X POST "http://localhost/api/admin/products" \
  -H "Authorization: Bearer token" \
  -F "title=" \
  -F "variants="
```

Expected:

- [ ] HTTP 422
- [ ] Validation errors in response

### Test 16: Not Found

```bash
curl -X GET "http://localhost/api/products/99999"
```

Expected:

- [ ] HTTP 404
- [ ] Not found message

---

## Testing - Web Routes (Still Working)

### Test 17: Dashboard

- [ ] Browse to `/admin/dashboard`
- [ ] Products list displays
- [ ] "+ Produk" button works

### Test 18: Create Product Form

- [ ] Click "+ Produk"
- [ ] Form displays
- [ ] Can add variants
- [ ] Submit button works

### Test 19: Edit Product Form

- [ ] Click product to edit
- [ ] Form pre-filled with data
- [ ] Variants display
- [ ] Update button works

### Test 20: Delete via Web

- [ ] Delete button works
- [ ] Product deleted
- [ ] Images cleaned up

---

## Integration Testing

### Test 21: API + Web Dashboard Sync

1. [ ] Create product via API
2. [ ] Verify it shows in dashboard
3. [ ] Update product via dashboard
4. [ ] Verify changes via API
5. [ ] Delete via API
6. [ ] Verify deletion in dashboard

### Test 22: Multiple Variants

1. [ ] Create product with 3+ variants
2. [ ] Each variant has unique price/stock
3. [ ] Images upload correctly
4. [ ] API returns all variants
5. [ ] Update some variants, keep others
6. [ ] Verify data integrity

### Test 23: Image Management

1. [ ] Upload product image
2. [ ] Upload variant images
3. [ ] Check files exist in storage
4. [ ] Verify URLs in API response
5. [ ] Update images
6. [ ] Verify old images deleted
7. [ ] Delete product
8. [ ] Verify all images deleted

---

## Performance Checks

- [ ] API response time < 200ms (without file uploads)
- [ ] File uploads complete successfully
- [ ] Database queries optimized (check with debugbar)
- [ ] No N+1 queries in list endpoints
- [ ] Pagination works smoothly

---

## Documentation Review

- [ ] Read `API_DOCUMENTATION.md`
- [ ] Read `IMPLEMENTATION_SUMMARY.md`
- [ ] Read `QUICK_REFERENCE.md`
- [ ] Understand endpoint structure
- [ ] Understand response formats
- [ ] Understand error handling

---

## Production Readiness

- [ ] CORS configured (if frontend separate)
- [ ] Rate limiting configured
- [ ] Logging enabled
- [ ] Error tracking setup
- [ ] Backups configured
- [ ] SSL/HTTPS enabled
- [ ] Environment variables secured

---

## Summary

- Total Checks: 87
- Completed: \_\_\_/87

**Status:**

- [ ] ✅ All checks passed - Ready for use
- [ ] ⚠️ Some checks failed - See errors above
- [ ] 🔴 Critical issues - Fix before production

---

## Next Steps

1. [ ] Complete all checks above
2. [ ] Test with real frontend application
3. [ ] Monitor logs in production
4. [ ] Gather user feedback
5. [ ] Optimize based on performance metrics
6. [ ] Document any custom integrations

---

## Support & Troubleshooting

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server logs
3. Run `php artisan migrate:fresh --seed` to reset database
4. Clear cache: `php artisan cache:clear`
5. Clear config: `php artisan config:clear`
6. Restart queue: `php artisan queue:restart`

---

**Last Updated:** {{ date('Y-m-d H:i:s') }}
**API Version:** 1.0
**Laravel Version:** 11.x
