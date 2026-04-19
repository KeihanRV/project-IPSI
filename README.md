# SHOO - Platform E-Commerce Luxury Sneakers & Accessories

## 1. Pendahuluan & Visi Produk

**Nama Proyek:** SHOO - Luxury E-Commerce Platform

**Deskripsi:**  
Platform belanja daring eksklusif yang fokus pada pengalaman pengguna premium untuk **Luxury Sneakers & Accessories**. SHOO dirancang dengan arsitektur modern, UI/UX luxury, dan fitur canggih seperti manajemen varian real-time, keranjang pintar untuk guest/auth, serta sistem inventory akurat.

**Visi:** Menyediakan pengalaman belanja digital kelas dunia dengan desain minimalis elegan, performa cepat, dan keamanan tingkat enterprise.

## 2. Arsitektur Teknologi (Tech Stack)

| Komponen            | Teknologi                         | Versi                      | Deskripsi                               |
| ------------------- | --------------------------------- | -------------------------- | --------------------------------------- |
| **Backend**         | Laravel                           | 11.x (^13.0)               | Framework PHP full-featured             |
| **Bahasa**          | PHP                               | ^8.3                       | Runtime dengan type safety              |
| **Frontend**        | Blade + Tailwind CSS + Vanilla JS | Tailwind ^3.1, Alpine ^3.4 | UI responsive luxury, state mgmt ringan |
| **Bundler**         | Vite                              | ^8.0                       | Build tool cepat HMR                    |
| **Database**        | MySQL                             | Relational                 | Schema migrations Eloquent ORM          |
| **Auth**            | Laravel Sanctum + Breeze          | ^4.0, ^2.4                 | Session-based + API tokens              |
| **Version Control** | Git                               | Branch-based               | Kolaborasi feature/admin branches       |
| **Testing**         | Pest + PHPUnit                    | ^4.5                       | Unit/Feature tests lengkap              |

**Konfigurasi Tambahan:**

```bash
# Tailwind custom (tailwind.config.js)
fontFamily: { sans: ['Figtree', ...defaultTheme.fontFamily.sans] }
plugins: [@tailwindcss/forms]
```

## 3. Fitur Utama Sistem

### Product Management

- Katalog dinamis dengan search full-text (title/location)
- CRUD admin lengkap dengan upload gambar produk/varian (transaksi DB atomic)
- Spesifikasi produk terstruktur (parse line-by-line)

### Luxury Product Detail

- Layout lebar `max-w-7xl mx-auto` untuk visual eksklusif
- **Tipografi khusus:** Harga monospace `font-mono font-bold text-6xl` (Courier New)
- **3 Kartu info dashed-border:** Detail/Spesifikasi/Review (`bg-[#FDFBF2] border-dashed border-blue-300`)
- Palet luxury: Beige `#FAF9F6`, Gold `#D4B47B` gradient buttons

### Advanced Inventory System

- Stok per varian (`variants.stock`) real-time di client-side
- Validasi stok sebelum add-to-cart
- Total stock accessor di Product model

### Smart Cart System

- **Dual mode:** Guest (`session_id`) + Auth (`user_id`)
- **Composite keys:** Unique `(user/session + product_id + variant_id)` cegah overwrite
- Merge otomatis session → user saat login (Listener `MigrateCartOnLogin`)
- Subtotal/harga dinamis via accessors (`$cart->subtotal = $price * $qty`)

### Variant Handling

- Sinkronisasi sempurna: UI grid selector → hidden `variant_id` → Controller cast `(int)`
- Gambar per varian, harga independen
- Query conditional `when($variantId, ...where('variant_id', $variantId))`

## 4. Desain Database & Skema

### Hubungan Tabel Utama

```
users (1) ──┐
             ├─ carts (N) ── products (1)
             │              │
             └────────────── variants (N)
```

**Detail Skema:**

| Tabel      | Kolom Utama                                                                                                       | Constraints/Notes    |
| ---------- | ----------------------------------------------------------------------------------------------------------------- | -------------------- |
| `users`    | `id`, `name`, `email`, `profile_*` (phone, address, etc.)                                                         | Enhanced profile     |
| `products` | `id`, `title`, `description`, `specification`, `location`, `image`, `rating`, `sold`                              | Base product         |
| `variants` | `id`, `product_id` (FK cascade), `name`, `price`, `stock`, `image`                                                | Per-size/color       |
| `carts`    | `id`, `user_id` (nullable FK), `session_id` (nullable), `product_id` (FK), `variant_id` (nullable FK), `quantity` | **Composite unique** |

**Composite Key di Carts (Kunci Sukses):**

```php
// Migration
$table->unique(['user_id', 'product_id', 'variant_id'], 'unique_cart_item');
$table->unique(['session_id', 'product_id', 'variant_id'], 'unique_guest_cart_item');
```

## 5. Implementasi Teknis & Solusi Masalah

### State Management Frontend (Vanilla JS)

```javascript
// Variant sync + stok real-time
function selectVariant(id, stock) {
    document.getElementById("selected-variant-id").value = id;
    document.getElementById("stock-display").innerHTML = `Stok: ${stock}`;
    // Active class: gold border + scale
}
```

### Keamanan Data (Mass Assignment Protection)

```php
// Models: $fillable eksplisit
protected $fillable = ['user_id', 'session_id', 'product_id', 'variant_id', 'quantity'];

// Controller: Validasi ketat
$request->validate(['product_id' => 'exists:products,id', ...]);
```

### Resolusi Konflik & Merge

- **Git workflow:** `feature/cart-composite` → `main` (no conflicts via services)
- **Cart merge:** `CartService::migrateSessionCartToUser()` dipicu `Login` event

**Solusi Masalah Umum:**
| Masalah | Solusi |
|---------|--------|
| `variant_id` NULL | Cast `(int)$request->variant_id ?: null` + `when()` query |
| Overwrite cart | Composite unique keys |
| Stok race-condition | Per-varian stock + increment atomic |

## 6. Panduan Instalasi (Getting Started)

### Prasyarat

- PHP 8.3+, Composer, Node.js 20+, MySQL 8.0+
- Git

```bash
# 1. Clone repository
git clone <your-repo-url> SHOO
cd SHOO

# 2. Install dependencies
composer install --optimize-autoloader --no-dev
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database
php artisan migrate --seed  # Includes ProductSeeder

# 5. Storage link
php artisan storage:link

# 6. Build assets & serve
npm run build
php artisan serve

# Akses: http://127.0.0.1:8000
# Admin: /admin/dashboard (buat user admin via tinker/seeder)
```

**Development Multi-terminal:**

```bash
# composer.json scripts
npx concurrently "php artisan serve" "npm run dev" "php artisan queue:listen"
```

**Testing:**

```bash
php artisan test  # Pest/PHPUnit
php artisan db:seed --class=ProductSeeder
```

## Kontribusi & Lisensi

- Gunakan feature branches: `git checkout -b feature/nama-fitur`
- MIT License

**Dibuat dengan untuk presentasi profesional. Last update:** `date('2026-4-18 8:41 PM')`
