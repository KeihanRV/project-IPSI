# TODO: Fix Variant Selection Off-by-One Bug in product-detail.blade.php

## Plan Steps:

- [x] Step 1: Add dedicated `#stock-display` span in quantity selector section
- [x] Step 2: Update variant buttons `@foreach` loop - change onclick to pass id + stock
- [x] Step 3: Rewrite `selectVariant(id, stock)` JS function with correct stock update and class-based active styling
- [x] Step 4: Add CSS rules for `.variant-btn.active` visual feedback
- [ ] Step 4: Add CSS rules for `.variant-btn.active` visual feedback
- [x] Step 5: Test variant selection - verify stock updates correctly, active button highlights, form submits right id
- [x] Step 6: Clear view cache - `php artisan view:clear`
- [x] Step 7: Verify add-to-cart saves correct variant_id in DB

**Status**: ✅ COMPLETE - Variant selection off-by-one fixed. Stock now shows per clicked variant. Active styling works with class/CSS. Form ready for cart.
