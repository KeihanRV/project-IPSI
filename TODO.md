# TODO: Implementasi Fitur Keranjang (Session-based)

✅ **SELESAI! Fitur keranjang lengkap:**

- Backend service/controller/routes ✅
- Navbar link + badge count ✅
- Add to cart from list/detail (dengan qty) ✅
- Cart page: view items, update qty, remove, clear, total ✅

## Cara Test:

1. `php artisan serve`
2. Buka localhost:8000
3. Klik "Tambah ke Keranjang" di home atau detail (/product/1)
4. Lihat badge di navbar
5. Klik cart icon → /cart
6. Test update qty, hapus, kosongkan

Notes:

- Guest + auth ok (session)
- Navbar badge instantiate service langsung (simple)
- Checkout stub (alert)
- Linter error di routes ignore (tidak pengaruh run)

Fitur siap pakai! 🎉
