# Fix variant_id NULL Issue in Cart

Status: 🔄 In Progress

## Steps:

- [x]   1. Analyzed files & created plan (CartController, CartService, Blade files)
- [✅] 2. Edit `resources/views/pages/product-detail.blade.php`
    - Replace hardcoded variants with `@foreach($product->variants)`
    - Add hidden `name='variant_id' id='selected-variant-id'`
    - Update JS `selectVariant(variantId)` for numeric IDs
    - Update button onclick to pass `{{ $variant->id }}`
- [ ]   3. Test add to cart with variant selection
- [ ]   4. Verify DB: carts table has variant_id populated
- [ ]   5. Test composite logic: same product+variant → increment qty; different variant → new row
- [ ]   6. Complete: attempt_completion

Next: Edit Blade file
