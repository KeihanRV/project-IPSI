# 🚀 Quick Reference Guide - API Product Management

## 1️⃣ Setup Token (Admin Login)

```javascript
// Setelah admin login, dapatkan token
const token = document.querySelector('meta[name="csrf-token"]').content;
// Atau ambil dari response login API jika ada

// Set ke client
const api = new ProductAPIClient("/api");
api.setToken(token);
```

---

## 2️⃣ Common Operations

### Get All Products (Public)

```javascript
const response = await api.getProducts("search term", 15, 1);
console.log(response.data); // Array of products
console.log(response.pagination); // Pagination info
```

### Get Single Product (Public)

```javascript
const response = await api.getProduct(1); // Product ID = 1
console.log(response.data); // Product with variants
```

### Create Product (Admin)

```javascript
const formData = new FormData();
formData.append("title", "Kaos Pria");
formData.append("description", "Premium quality");
formData.append("specification", "Material: Cotton");
formData.append("location", "Jakarta");
formData.append("image", imageFile); // File input

// Add variants
formData.append("variants[0][name]", "Size M");
formData.append("variants[0][price]", 50000);
formData.append("variants[0][stock]", 30);
formData.append("variants[0][image]", variantImageFile);

formData.append("variants[1][name]", "Size L");
formData.append("variants[1][price]", 55000);
formData.append("variants[1][stock]", 25);

const response = await api.createProduct(formData);
console.log(response.data.id); // New product ID
```

### Update Product (Admin)

```javascript
const formData = new FormData();
formData.append("title", "Updated Title");

// Update existing variant (with id)
formData.append("variants[0][id]", 1); // Variant ID
formData.append("variants[0][name]", "Size M");
formData.append("variants[0][price]", 60000);
formData.append("variants[0][stock]", 20);

// Add new variant (without id)
formData.append("variants[1][name]", "Size XL");
formData.append("variants[1][price]", 65000);
formData.append("variants[1][stock]", 15);

const response = await api.updateProductPut(1, formData); // Product ID = 1
```

### Delete Product (Admin)

```javascript
const response = await api.deleteProduct(1); // Product ID = 1
console.log(response.message);
```

---

## 3️⃣ Variant Operations (Admin)

### Get All Variants

```javascript
const response = await api.getVariants(1); // Product ID = 1
console.log(response.data); // Array of variants
```

### Create Variant

```javascript
const formData = new FormData();
formData.append("name", "Size XXL");
formData.append("price", 70000);
formData.append("stock", 10);
formData.append("image", imageFile);

const response = await api.createVariant(1, formData); // Product ID = 1
```

### Update Variant

```javascript
const formData = new FormData();
formData.append("name", "Size XXL Updated");
formData.append("price", 75000);
formData.append("stock", 8);

const response = await api.updateVariant(1, 5, formData); // Product ID = 1, Variant ID = 5
```

### Delete Variant

```javascript
const response = await api.deleteVariant(1, 5); // Product ID = 1, Variant ID = 5
```

---

## 4️⃣ Error Handling

```javascript
try {
    const response = await api.createProduct(formData);
    console.log("Success:", response.data);
} catch (error) {
    if (error.status === 401) {
        console.log("Token invalid or expired");
    } else if (error.status === 403) {
        console.log("Not authorized as admin");
    } else if (error.status === 404) {
        console.log("Resource not found");
    } else if (error.status === 422) {
        console.log("Validation error:", error.data.errors);
    } else if (error.status === 500) {
        console.log("Server error:", error.data.error);
    }
}
```

---

## 5️⃣ API Endpoints Reference

### Public Endpoints

```
GET /api/products                 - List all products
GET /api/products/{id}            - Get single product
```

### Admin Endpoints (Require Auth + Admin Role)

**Products:**

```
GET    /api/admin/products                  - List products
POST   /api/admin/products                  - Create product
GET    /api/admin/products/{id}             - Get product
GET    /api/admin/products/{id}/edit        - Get for editing
PUT    /api/admin/products/{id}             - Update product
DELETE /api/admin/products/{id}             - Delete product
```

**Variants:**

```
GET    /api/admin/products/{pid}/variants                - List variants
POST   /api/admin/products/{pid}/variants                - Create variant
GET    /api/admin/products/{pid}/variants/{vid}          - Get variant
PUT    /api/admin/products/{pid}/variants/{vid}          - Update variant
DELETE /api/admin/products/{pid}/variants/{vid}          - Delete variant
```

---

## 6️⃣ Response Examples

### Product with Variants

```json
{
    "id": 1,
    "title": "Kaos Pria",
    "description": "Premium quality",
    "specification": "Material: Cotton",
    "location": "Jakarta",
    "image": "https://example.com/storage/product/image.jpg",
    "rating": 4.5,
    "sold": 25,
    "lowest_price": 50000,
    "total_stock": 100,
    "variants": [
        {
            "id": 1,
            "name": "Size M",
            "price": 50000,
            "stock": 30,
            "image": "https://example.com/storage/variant/variant1.jpg"
        },
        {
            "id": 2,
            "name": "Size L",
            "price": 55000,
            "stock": 35,
            "image": "https://example.com/storage/variant/variant2.jpg"
        }
    ]
}
```

---

## 7️⃣ Import & Usage

```html
<!-- Include client script -->
<script src="/js/product-api-client.js"></script>

<script>
    // Create instance
    const api = new ProductAPIClient("/api");

    // Set token if admin
    api.setToken("{{ auth()->user()->tokens->first()->plainTextToken }}");

    // Use it
    api.getProducts().then((response) => {
        console.log(response.data);
    });
</script>
```

---

## 8️⃣ Validation Rules

| Field         | Rules                                  |
| ------------- | -------------------------------------- |
| title         | required, max 255                      |
| description   | nullable, string                       |
| specification | nullable, string                       |
| location      | required, max 255                      |
| image         | nullable, file, max 2MB, image formats |
| variant.name  | required, max 255                      |
| variant.price | required, integer, min 0               |
| variant.stock | required, integer, min 0               |
| variant.image | nullable, file, max 2MB, image formats |

---

## 9️⃣ File Upload Example

```javascript
// Get file from input
const fileInput = document.getElementById("productImage");
const file = fileInput.files[0];

// Create FormData
const formData = new FormData();
formData.append("title", "Product Name");
formData.append("image", file);
formData.append("variants[0][name]", "Size M");
formData.append("variants[0][image]", variantFile);

// Send to API
const response = await api.createProduct(formData);
```

---

## 🔟 Admin Middleware Check

Ensure admin middleware is setup in `app/Http/Middleware/IsAdmin.php`:

```php
public function handle($request, Closure $next)
{
    if (auth()->check() && auth()->user()->is_admin) {
        return $next($request);
    }
    return response()->json(['message' => 'Unauthorized'], 403);
}
```

And registered in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    'admin' => \App\Http\Middleware\IsAdmin::class,
];
```

---

## 📚 Complete Documentation

See `API_DOCUMENTATION.md` for detailed endpoint documentation
See `IMPLEMENTATION_SUMMARY.md` for implementation details
