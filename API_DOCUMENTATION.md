# API Documentation - Product & Variant Management

## Base URL

```
/api
```

## Authentication

Endpoints yang memerlukan admin access memerlukan Sanctum token authentication.

---

## Endpoints

### 1. Products

#### List Products (Public)

```
GET /api/products
```

**Query Parameters:**

- `search` (optional): Search by title or location
- `per_page` (optional, default: 15): Items per page

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Product Name",
            "description": "Description",
            "specification": "Specifications",
            "location": "Jakarta",
            "image": "https://...",
            "rating": 4.5,
            "sold": 10,
            "lowest_price": 50000,
            "total_stock": 100,
            "variants": [
                {
                    "id": 1,
                    "name": "Size M",
                    "price": 50000,
                    "stock": 30,
                    "image": "https://..."
                }
            ],
            "created_at": "2026-04-19T...",
            "updated_at": "2026-04-19T..."
        }
    ],
    "pagination": {
        "current_page": 1,
        "total": 50,
        "per_page": 15,
        "last_page": 4
    }
}
```

---

#### Get Single Product (Public)

```
GET /api/products/{id}
```

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Product Name",
        "description": "Description",
        ...
        "variants": [...]
    }
}
```

---

#### Create Product (Admin Only)

```
POST /api/admin/products
```

**Headers:**

- `Authorization: Bearer {token}`
- `Content-Type: multipart/form-data`

**Request Body:**

```
- title (required): string
- description (optional): string
- specification (optional): string
- location (required): string
- image (optional): file (image/jpeg, image/png, image/jpg, image/webp, max: 2MB)
- variants (required): array of objects
  - variants[0][name] (required): string
  - variants[0][price] (required): integer (min: 0)
  - variants[0][stock] (required): integer (min: 0)
  - variants[0][image] (optional): file (image/jpeg, image/png, image/jpg, image/webp, max: 2MB)
```

**Example using cURL:**

```bash
curl -X POST http://localhost/api/admin/products \
  -H "Authorization: Bearer your_token" \
  -F "title=Kaos Pria" \
  -F "description=Kaos premium berkualitas tinggi" \
  -F "specification=Material: 100% Cotton\n• Ukuran: M, L, XL" \
  -F "location=Jakarta Selatan" \
  -F "image=@/path/to/image.jpg" \
  -F "variants[0][name]=Size M" \
  -F "variants[0][price]=50000" \
  -F "variants[0][stock]=30" \
  -F "variants[0][image]=@/path/to/variant1.jpg" \
  -F "variants[1][name]=Size L" \
  -F "variants[1][price]=50000" \
  -F "variants[1][stock]=25"
```

**Response (201):**

```json
{
    "success": true,
    "message": "Produk berhasil ditambahkan",
    "data": {
        "id": 1,
        "title": "Kaos Pria",
        ...
    }
}
```

---

#### Get Product for Editing (Admin Only)

```
GET /api/admin/products/{id}/edit
```

**Headers:**

- `Authorization: Bearer {token}`

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Product Name",
        "description": "Description",
        "specification": "Specifications",
        "location": "Jakarta",
        "image": "https://...",
        "variants": [
            {
                "id": 1,
                "name": "Size M",
                "price": 50000,
                "stock": 30,
                "image": "https://..."
            }
        ]
    }
}
```

---

#### Update Product (Admin Only)

```
PUT /api/admin/products/{id}
```

**Headers:**

- `Authorization: Bearer {token}`
- `Content-Type: multipart/form-data`

**Request Body:**
Same as Create Product endpoint, but variants dengan `id` field untuk identify existing variants

**Example using JavaScript/Fetch:**

```javascript
const formData = new FormData();
formData.append("title", "Updated Product Name");
formData.append("description", "Updated description");
formData.append("variants[0][id]", 1); // Existing variant
formData.append("variants[0][name]", "Size M");
formData.append("variants[0][price]", 60000);
formData.append("variants[0][stock]", 25);
formData.append("variants[1][name]", "Size L"); // New variant (no id)
formData.append("variants[1][price]", 60000);
formData.append("variants[1][stock]", 20);

const response = await fetch("/api/admin/products/1", {
    method: "PUT",
    headers: {
        Authorization: "Bearer " + token,
    },
    body: formData,
});

const data = await response.json();
```

**Response:**

```json
{
    "success": true,
    "message": "Produk berhasil diperbarui",
    "data": {...}
}
```

---

#### Delete Product (Admin Only)

```
DELETE /api/admin/products/{id}
```

**Headers:**

- `Authorization: Bearer {token}`

**Response:**

```json
{
    "success": true,
    "message": "Produk berhasil dihapus"
}
```

---

### 2. Variants

#### Get All Variants for a Product (Admin Only)

```
GET /api/admin/products/{productId}/variants
```

**Headers:**

- `Authorization: Bearer {token}`

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "product_id": 1,
            "name": "Size M",
            "price": 50000,
            "stock": 30,
            "image": "https://...",
            "created_at": "2026-04-19T...",
            "updated_at": "2026-04-19T..."
        }
    ]
}
```

---

#### Get Single Variant (Admin Only)

```
GET /api/admin/products/{productId}/variants/{variantId}
```

**Headers:**

- `Authorization: Bearer {token}`

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "product_id": 1,
        "name": "Size M",
        "price": 50000,
        "stock": 30,
        "image": "https://...",
        "created_at": "2026-04-19T...",
        "updated_at": "2026-04-19T..."
    }
}
```

---

#### Create Variant (Admin Only)

```
POST /api/admin/products/{productId}/variants
```

**Headers:**

- `Authorization: Bearer {token}`
- `Content-Type: multipart/form-data`

**Request Body:**

```
- name (required): string
- price (required): integer (min: 0)
- stock (required): integer (min: 0)
- image (optional): file
```

**Response (201):**

```json
{
    "success": true,
    "message": "Varian berhasil ditambahkan",
    "data": {...}
}
```

---

#### Update Variant (Admin Only)

```
PUT /api/admin/products/{productId}/variants/{variantId}
```

**Headers:**

- `Authorization: Bearer {token}`
- `Content-Type: multipart/form-data`

**Request Body:** Same as Create Variant

**Response:**

```json
{
    "success": true,
    "message": "Varian berhasil diperbarui",
    "data": {...}
}
```

---

#### Delete Variant (Admin Only)

```
DELETE /api/admin/products/{productId}/variants/{variantId}
```

**Headers:**

- `Authorization: Bearer {token}`

**Response:**

```json
{
    "success": true,
    "message": "Varian berhasil dihapus"
}
```

---

## Error Responses

### Validation Error (422)

```json
{
    "success": false,
    "message": "Validation error",
    "errors": {
        "title": ["The title field is required."],
        "variants": ["The variants field is required."]
    }
}
```

### Server Error (500)

```json
{
    "success": false,
    "message": "Terjadi kesalahan saat menyimpan produk",
    "error": "Error details..."
}
```

### Unauthorized (401)

```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)

```json
{
    "message": "Unauthorized."
}
```

---

## File Structure

```
app/Http/Controllers/
├── API/
│   ├── ProductController.php    (API endpoints untuk CRUD Product)
│   └── VariantController.php    (API endpoints untuk CRUD Variant)
└── ProductController.php        (Web routes untuk CRUD Product)

routes/
├── api.php                      (Main API routes file)
└── api/
    └── products.php             (Product & Variant API routes)
```

---

## Notes

1. **Images Management:**
    - Product images disimpan di `storage/app/public/product/`
    - Variant images disimpan di `storage/app/public/variant/`
    - Ensure storage symlink exists: `php artisan storage:link`

2. **Database Transactions:**
    - Semua operasi product dengan variants menggunakan database transactions
    - Jika ada error, semua changes akan di-rollback dan uploaded images akan dihapus

3. **Validation:**
    - Maximum file size: 2MB per image
    - Allowed formats: JPEG, PNG, JPG, WebP
    - Minimum variants required: 1

4. **Response Format:**
    - Semua responses menggunakan JSON format
    - Status field `success` untuk indicate success/failure
    - Proper HTTP status codes digunakan

---

## Web Routes Tetap Digunakan Untuk

- Admin dashboard: `/admin/dashboard` (GET)
- Create product form: `/admin/products/create` (GET)
- Store product form: `/admin/products` (POST) - masih menggunakan traditional form
- Edit product form: `/admin/products/{id}/edit` (GET)
- Update product form: `/admin/products/{id}` (PUT) - masih menggunakan traditional form
- Delete product: `/admin/products/{id}` (DELETE)

Aplikasi sekarang mendukung **BOTH**:

1. Traditional form submission via web routes
2. API endpoints untuk programmatic access
