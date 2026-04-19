# 🧪 TESTING GUIDE - PRODUCT VARIANT MANAGEMENT

## Prerequisites

- Laravel app running locally (http://localhost or configured URL)
- Database already migrated and seeded
- Admin panel accessible

---

## Test Case 1: Create Product with Variants

### Step 1: Navigate to Create Product

1. Go to Admin Dashboard (`/admin/dashboard`)
2. Click "+ Produk" button (top right)
3. Verify: Create product form loads with empty fields

### Step 2: Add Product Data

1. Enter product details:
    - **Title**: "T-Shirt Premium"
    - **Description**: "Premium quality cotton t-shirt"
    - **Location**: "Jakarta"
    - **Upload Image**: Select a product image
2. Verify: Form fields filled correctly

### Step 3: Add Multiple Variants

1. Notice: One empty variant row exists
2. Click "Tambah Varian" button 3 times
3. Verify: Now 4 variant rows display

### Step 4: Fill Variant Data

Fill each variant:

```
Variant 1:
- Name: "S (Small)"
- Price: "50000"
- Stock: "10"
- Image: Select variant image

Variant 2:
- Name: "M (Medium)"
- Price: "60000"
- Stock: "15"
- Image: Select variant image

Variant 3:
- Name: "L (Large)"
- Price: "70000"
- Stock: "20"
- Image: Select variant image

Variant 4:
- Name: "XL (Extra Large)"
- Price: "80000"
- Stock: "25"
- Image: Select variant image
```

### Step 5: Test Dynamic Variant Management

1. Delete variant at position 2 (M)
    - Verify: Only 3 variants remain
    - Verify: Remaining variants still have correct data
2. Add new variant again
    - Verify: New row appears at the end
3. Add another variant
    - Verify: Total 5 variants

### Step 6: Submit Form

1. Click "Simpan" button
2. **Expected Result**:
    - ✅ Form submits without error
    - ✅ Redirects to dashboard
    - ✅ Success message shows: "Produk berhasil ditambahkan!"

### Step 7: Verify Database Save

Check database directly:

```sql
-- Check products table
SELECT * FROM products WHERE title = 'T-Shirt Premium';

-- Check variants table for this product
SELECT * FROM variants WHERE product_id = X;
```

**Expected Results:**

- ✅ Product row exists with correct title, description, location
- ✅ 5 variants exist with correct names, prices, stocks
- ✅ Product image saved in `storage/app/public/product/`
- ✅ All variant images saved in `storage/app/public/variant/`

---

## Test Case 2: Edit Product and Verify All Variants Display

### Step 1: Edit Product From Dashboard

1. On dashboard, click the **Edit button** (pencil icon) on the T-Shirt product
2. **Verify**: Edit product page loads
3. **CRITICAL - Verify**: ALL 5 variants from database display on the page
    - Should show all 5 variant rows with correct data
    - Should show variant images

### Step 2: Modify Existing Variant

1. Edit Variant 1:
    - Change price from "50000" to "55000"
    - Change stock from "10" to "12"
    - Don't upload new image (should keep existing)

2. Verify: Variant ID preserved in hidden field

### Step 3: Add New Variant

1. Click "Tambah Varian"
2. Add new variant:
    - Name: "XXL (Double Extra Large)"
    - Price: "90000"
    - Stock: "8"
    - Upload image

3. Verify: New variant row appears at bottom

### Step 4: Delete Variant

1. Click delete icon on Variant 3 (L)
2. Verify: Row removed, remaining variants still display correctly

### Step 5: Modify Product Image

1. Upload new product image
2. Verify: Image preview updates

### Step 6: Submit Form

1. Click "Update" button
2. **Expected Result**:
    - ✅ Form submits without error
    - ✅ Redirects to dashboard
    - ✅ Success message shows: "Produk berhasil diperbarui!"

### Step 7: Verify Database Changes

Check database:

```sql
-- Verify product update
SELECT * FROM products WHERE id = X;

-- Verify variant changes
SELECT * FROM variants WHERE product_id = X ORDER BY id;
```

**Expected Results:**

- ✅ Product image updated if new one uploaded
- ✅ Variant 1 price = "55000", stock = "12"
- ✅ Variant 3 (L) deleted
- ✅ New Variant (XXL) created
- ✅ Total variants = 5 (was 5, deleted 1, added 1)
- ✅ Variant IDs properly tracked

---

## Test Case 3: Dashboard Display

### Step 1: View Dashboard

1. Navigate to Admin Dashboard
2. **Verify**: T-Shirt product card displays
3. **Verify**: Card shows lowest price from all variants
    - Expected: "Rp50.000" (from smallest size S, now updated to 55000 should show)

### Step 2: Click Product

1. Click on T-Shirt product card
2. Verify: Product detail page loads with all variants

---

## Test Case 4: Edge Cases

### Test 4.1: Create Product with Single Variant

1. Create new product with only 1 variant
2. Try to delete that variant
3. Verify: System prevents deletion (minimum 1 variant required)
   OR shows new empty row to maintain minimum

### Test 4.2: Add 10+ Variants

1. Edit the T-Shirt product
2. Add variants until you have 10+ total
3. Verify: Form still works correctly
4. Submit and verify all saved

### Test 4.3: Form Validation

1. Try to submit create form with empty variant name
2. Verify: Form validation shows error (required field)

### Test 4.4: File Upload Validation

1. Try to upload non-image file as variant image
2. Verify: File upload rejected

---

## Console Debugging (Browser DevTools)

### Open Browser Developer Tools (F12)

Go to Console tab to check for any errors.

### Expected: No errors related to

- ✅ Variant data loading
- ✅ Image handling
- ✅ Form submission

### If debugging needed, look for messages like:

```javascript
// From edit-product.blade.php
console.log("Product variants from database:", {...})
console.log("Variant source for display:", [...])
```

---

## API Testing (Optional)

If you want to verify API endpoints as well:

### Create Product via API

```bash
curl -X POST http://localhost/api/admin/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "API Test Product",
    "variants": [
      {"name": "S", "price": 50000, "stock": 10},
      {"name": "M", "price": 60000, "stock": 15}
    ]
  }'
```

### Get Product with Variants

```bash
curl -X GET "http://localhost/api/admin/products/1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Update Product

```bash
curl -X PUT http://localhost/api/admin/products/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title",
    "variants": [
      {"id": 1, "name": "S", "price": 55000, "stock": 12},
      {"name": "L", "price": 70000, "stock": 20}
    ]
  }'
```

---

## ✅ Sign-Off Checklist

After all tests pass, mark these items:

- [ ] Create product form works (1+ variants saved)
- [ ] Edit product form loads all variants from database
- [ ] Can add variants dynamically during edit
- [ ] Can delete variants during edit
- [ ] Form submission properly renumbers variant indexes
- [ ] Variant images upload correctly
- [ ] Database saves are accurate
- [ ] Dashboard displays products correctly
- [ ] Lowest price calculated correctly from variants
- [ ] No console errors
- [ ] API endpoints functional (if tested)

---

## Troubleshooting

### Issue: Variants not displaying on edit form

**Solution:**

1. Check browser console for errors (F12 → Console)
2. Check database: `SELECT * FROM variants WHERE product_id = X;`
3. Verify ProductController edit() is loading variants

### Issue: Form submission fails

**Solution:**

1. Check form data in DevTools Network tab
2. Verify variant indexes are sequential in request
3. Check Laravel logs: `storage/logs/laravel.log`

### Issue: Images not uploading

**Solution:**

1. Check storage permissions: `chmod -R 755 storage/app/public/`
2. Verify symbolic link: `php artisan storage:link`
3. Check Laravel logs for file system errors

### Issue: "Minimal 1 varian required" validation error

**Solution:**

1. Ensure at least 1 variant exists in form
2. Check that renumberVariants() is called before submit

---

## Success Criteria

✅ **Test passes when:**

1. All variants display correctly on edit page
2. Form submissions save all variant data to database
3. Adding/removing variants doesn't break form functionality
4. Dashboard shows products with correct pricing
5. No console errors in browser
6. Database records are accurate and complete

---

Generated: 2026-04-19
