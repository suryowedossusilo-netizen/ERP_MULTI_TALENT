# Rencana Migrasi Indeks Unik Multi-Tenant

Status: **rencana saja — belum disetujui untuk dijalankan**.

Dokumen ini hanya mencatat rencana. Tidak ada migrasi dibuat/dijalankan dan tidak ada database aktif yang diubah.

## Tujuan

Data dengan kode atau nomor bisnis yang sama harus boleh dipakai oleh tenant yang berbeda, tetapi tetap unik di dalam tenant pemiliknya. Semua query, validasi aplikasi, foreign key, dan indeks database harus konsisten dengan batas tenant.

## Indeks unik global yang ditemukan pada migration awal

| Tabel | Kolom yang saat ini unik global | Rencana target |
|---|---|---|
| users | email | unique (tenant_id, email) |
| employees | employee_code | unique (tenant_id, employee_code) |
| customers | code | unique (tenant_id, code) |
| suppliers | code | unique (tenant_id, code) |
| products | sku | unique (tenant_id, sku) |
| products | barcode (nullable) | tenant-scoped unique (tenant_id, barcode), setelah memastikan perilaku NULL pada engine database |
| warehouses | code | unique (tenant_id, code) |
| orders | order_number | unique (tenant_id, order_number) |
| invoices | invoice_number | unique (tenant_id, invoice_number) |
| payments | payment_number | unique (tenant_id, payment_number) |
| purchase_orders | po_number | unique (tenant_id, po_number) |
| incomes | income_number | unique (tenant_id, income_number) |
| expenses | expense_number | unique (tenant_id, expense_number) |

Catatan tambahan: indeks unik stocks saat ini adalah (product_id, warehouse_id). Audit lanjutan perlu memastikan cakupan tenant dan foreign-key relation yang konsisten; jangan mengubah indeks ini hanya demi menambah tenant_id jika tidak ada kebutuhan integritas yang jelas.


## Hasil audit kode read-only (2026-10-19)

Ini adalah temuan dari kode dan file migration di repository `main`, bukan hasil query terhadap data database aktif.

### Temuan prioritas tinggi

1. **Validasi email User masih global.** `UserController::store/update` menggunakan `Rule::unique('users', 'email')` tanpa `tenant_id`. Ini bertentangan dengan login yang mencari user menggunakan kombinasi tenant dan email. Perlu validasi tenant-scoped dan tes dua tenant dengan email sama setelah skema kompatibel.
2. **Purchase order number masih divalidasi global.** `PurchaseOrderController::store/update` memvalidasi `po_number` secara global, sedangkan numbering dirancang per tenant. Perlu validasi tenant-scoped.
3. **Income/expense number belum memiliki validasi format dan keunikan tenant-scoped.** Controller hanya mewajibkan nomor dan nominal; indeks database saat ini global. Perlu audit generator dan aturan input sebelum perbaikan.
4. **Relasi model tidak semuanya dijamin cocok tenant oleh foreign key.** Migration menggunakan foreign key berdasarkan ID saja. Pemeriksaan aplikasi sudah ada pada sebagian jalur (contoh order dan invoice/payment service), tetapi integritas data lama harus dicek secara terpisah.
5. **Pivots role-permission dan user-role tidak memiliki unique composite constraint.** Duplikasi pasangan pivot bisa terjadi di tingkat database. Selain itu, audit lanjutan perlu memastikan role global/sistem tidak dapat diberikan ke user tenant secara tidak semestinya. Model `User::roles()` saat ini hanya memilih role dengan `tenant_id` sama dengan user; role dengan tenant_id NULL tidak ikut hasil relasi tersebut.
6. **Validasi relasi produk belum lengkap.** `ProductController` tidak menerima/validasi kategori dan satuan pada jalur create/update saat ini. Walau hal ini membatasi input lewat controller tersebut, data lama atau jalur lain masih perlu diperiksa untuk memastikan `category_id` dan `unit_id` milik tenant yang sama.
7. **Relasi tenant yang harus diperiksa pada data lama:** employees.user_id/department_id/position_id; products.category_id/unit_id; stocks.product_id/warehouse_id; stock_movements.product_id/warehouse_id/user_id; orders.customer_id/user_id; order_items.product_id dibandingkan dengan tenant order; invoices.order_id/customer_id; payments.invoice_id; purchase_orders.supplier_id; purchase_order_items.product_id dibandingkan dengan tenant purchase order; role_permission dan user_role.
8. **Indeks stocks** unik pada `(product_id, warehouse_id)`; jangan ubah sebelum pemeriksaan relasi dan engine selesai. Karena product/warehouse ID global, indeks ini mungkin cukup untuk mencegah pasangan duplikat secara global, tetapi tidak membuktikan tenant_id di baris stocks cocok dengan kedua relasi.

### Query duplikasi read-only

Query berikut adalah template untuk dijalankan oleh pemilik database atau operator pada **salinan/staging atau koneksi read-only** setelah engine database dikonfirmasi. Tidak ada query ini yang dijalankan terhadap database aktif selama audit ini.

Contoh pemeriksaan duplikasi dalam tenant (ulangi untuk setiap pasangan tabel/kolom di atas):

```sql
SELECT tenant_id, email, COUNT(*) AS row_count
FROM users
GROUP BY tenant_id, email
HAVING COUNT(*) > 1;

SELECT tenant_id, employee_code, COUNT(*) AS row_count
FROM employees
GROUP BY tenant_id, employee_code
HAVING COUNT(*) > 1;

SELECT tenant_id, code, COUNT(*) AS row_count
FROM customers
GROUP BY tenant_id, code
HAVING COUNT(*) > 1;

SELECT tenant_id, code, COUNT(*) AS row_count
FROM suppliers
GROUP BY tenant_id, code
HAVING COUNT(*) > 1;

SELECT tenant_id, sku, COUNT(*) AS row_count
FROM products
GROUP BY tenant_id, sku
HAVING COUNT(*) > 1;

SELECT tenant_id, barcode, COUNT(*) AS row_count
FROM products
WHERE barcode IS NOT NULL
GROUP BY tenant_id, barcode
HAVING COUNT(*) > 1;

SELECT tenant_id, code, COUNT(*) AS row_count
FROM warehouses
GROUP BY tenant_id, code
HAVING COUNT(*) > 1;

SELECT tenant_id, order_number, COUNT(*) AS row_count
FROM orders
GROUP BY tenant_id, order_number
HAVING COUNT(*) > 1;

SELECT tenant_id, invoice_number, COUNT(*) AS row_count
FROM invoices
GROUP BY tenant_id, invoice_number
HAVING COUNT(*) > 1;

SELECT tenant_id, payment_number, COUNT(*) AS row_count
FROM payments
GROUP BY tenant_id, payment_number
HAVING COUNT(*) > 1;

SELECT tenant_id, po_number, COUNT(*) AS row_count
FROM purchase_orders
GROUP BY tenant_id, po_number
HAVING COUNT(*) > 1;

SELECT tenant_id, income_number, COUNT(*) AS row_count
FROM incomes
GROUP BY tenant_id, income_number
HAVING COUNT(*) > 1;

SELECT tenant_id, expense_number, COUNT(*) AS row_count
FROM expenses
GROUP BY tenant_id, expense_number
HAVING COUNT(*) > 1;
```

Pemeriksaan relasi lintas tenant (sesuaikan nama/quoting sesuai engine):

```sql
SELECT e.id, e.tenant_id, e.user_id, u.tenant_id AS related_tenant_id
FROM employees e JOIN users u ON u.id = e.user_id
WHERE e.user_id IS NOT NULL AND e.tenant_id <> u.tenant_id;

SELECT e.id, e.tenant_id, e.department_id, d.tenant_id AS related_tenant_id
FROM employees e JOIN departments d ON d.id = e.department_id
WHERE e.department_id IS NOT NULL AND e.tenant_id <> d.tenant_id;

SELECT e.id, e.tenant_id, e.position_id, p.tenant_id AS related_tenant_id
FROM employees e JOIN positions p ON p.id = e.position_id
WHERE e.position_id IS NOT NULL AND e.tenant_id <> p.tenant_id;

SELECT s.id, s.tenant_id, s.product_id, s.warehouse_id,
       p.tenant_id AS product_tenant_id, w.tenant_id AS warehouse_tenant_id
FROM stocks s
JOIN products p ON p.id = s.product_id
JOIN warehouses w ON w.id = s.warehouse_id
WHERE s.tenant_id <> p.tenant_id OR s.tenant_id <> w.tenant_id;

SELECT i.id, i.tenant_id, i.invoice_id, inv.tenant_id AS invoice_tenant_id
FROM payments i JOIN invoices inv ON inv.id = i.invoice_id
WHERE i.invoice_id IS NOT NULL AND i.tenant_id <> inv.tenant_id;
```

Hasil query harus disimpan sebagai laporan jumlah dan ID baris yang konflik, tanpa mengubah atau menghapus data. Query tambahan diperlukan untuk setiap relasi di daftar temuan di atas. Karena nama tabel/kolom dan dukungan SQL dapat berbeda menurut engine, validasi query di staging terlebih dahulu.

## Tahapan yang diusulkan

1. **Inventarisasi dan backup**
   - Konfirmasi engine dan versi database, ukuran tabel, status migration, serta strategi backup/restore.
   - Simpan backup yang dapat dipulihkan dan uji pemulihan di lingkungan staging, bukan database aktif.

2. **Pemeriksaan duplikasi read-only**
   - Untuk setiap kandidat pasangan (tenant_id, kolom), cari nilai duplikat di dalam tenant.
   - Untuk barcode nullable, periksa nilai non-NULL secara terpisah sesuai perilaku database yang digunakan.
   - Periksa email/kode/nomor kosong, perbedaan kapitalisasi, spasi, dan aturan collation.
   - Jangan menghapus atau menggabungkan data otomatis. Setiap konflik harus ditinjau dan diputuskan pemilik data.

3. **Audit foreign key lintas tenant**
   - Pastikan employee.department_id, employee.position_id, dan employee.user_id tidak menunjuk record tenant lain.
   - Pastikan product.category_id/unit_id, stock.product_id/warehouse_id, stock_movement.product_id/warehouse_id/user_id, order.customer_id/user_id, invoice.order_id/customer_id, payment.invoice_id, dan purchase_order.supplier_id sesuai tenant.
   - Foreign key yang hanya mengacu pada id global tidak dengan sendirinya menjamin tenant_id yang cocok; rencanakan perlindungan aplikasi dan/atau constraint komposit sesuai dukungan engine.

4. **Perubahan aplikasi terlebih dahulu**
   - Selaraskan validasi create/update dengan scope tenant untuk setiap kolom unik.
   - Pastikan numbering service selalu menghasilkan nomor dalam scope tenant.
   - Tambahkan tes untuk kode sama lintas tenant (harus diizinkan), duplikat dalam tenant (harus ditolak), barcode NULL, dan update record yang tidak mengubah kunci.
   - Deploy kode yang kompatibel sebelum indeks database diganti.

5. **Migrasi skema terencana**
   - Buat migration baru yang secara eksplisit menghapus indeks unik global dengan nama indeks yang diverifikasi dan membuat indeks komposit baru.
   - Jangan mengedit migration awal yang mungkin sudah pernah dijalankan di lingkungan mana pun.
   - Pastikan operasi indeks aman untuk engine/versi database yang digunakan dan perkirakan lock/downtime.
   - Siapkan rollback yang hanya mengembalikan skema. Rollback dapat gagal bila sudah ada nilai yang sama antar-tenant; karena itu rollback juga perlu diuji di staging.

6. **Validasi setelah migrasi**
   - Verifikasi indeks aktual, jalankan tes integritas dan seluruh suite Laravel.
   - Uji login email yang sama pada dua tenant berbeda dengan identitas tenant yang tepat.
   - Pantau error duplicate-key, autentikasi, numbering, transaksi, dan stok.

## Syarat sebelum meminta persetujuan eksekusi

- Engine/versi database dan lokasi database aktif telah dipastikan.
- Hasil pemeriksaan duplikasi read-only telah tersedia.
- Daftar indeks aktual dan nama indeks telah diverifikasi pada database staging.
- Dampak, waktu lock/downtime, backup, rollback, dan langkah pemulihan telah disetujui.
- Pemilik proyek memberi persetujuan eksplisit terpisah untuk membuat migration dan untuk menjalankannya pada lingkungan yang ditentukan.

## Batas saat ini

Tidak ada file migration yang ditambahkan atau diubah dalam pekerjaan ini. Tidak ada migration command dijalankan. Tidak ada database aktif dibaca atau ditulis melalui koneksi aplikasi.
