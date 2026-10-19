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

Catatan tambahan: indeks unik stocks saat ini adalah (product_id, warehouse_id). Audit lanjutan perlu memastikan cakupan tenant dan foreign-key relation yang konsisten; jangan mengubah indeks ini hanya demi menambah tenant_id jika tidak ada kebutuhan integritas yang jelas.

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
