# Deployment ERP_MULTI_TALENT ke InfinityFree (paket gratis)

Status: paket Laravel sudah dapat dibangun di GitHub Actions. **Build bukan berarti aplikasi sudah terpasang di hosting.** Untuk akun gratis, siapkan paket lalu unggah isinya ke `htdocs` melalui File Manager atau klien FTP secara manual.

InfinityFree mengonfirmasi bahwa paket gratis tidak menyediakan integrasi Git langsung. Dokumentasi mereka juga mencatat bahwa document root harus tetap `htdocs`, tidak ada SSH/terminal untuk menjalankan Composer/Artisan, dan file PHP di atas 1 MB dapat ditolak. Lihat referensi resmi di bagian akhir.

## Prinsip aman

- Tetap gunakan paket gratis; tidak perlu menambahkan kartu.
- Workflow menyiapkan file dan dependency Laravel saja. Workflow **tidak** menjalankan `migrate`, `db:seed`, atau mengubah database.
- Repository ini publik. Jangan pernah meng-commit atau mengunggah file `.env`, APP_KEY asli, password database, atau kredensial hosting ke GitHub.
- Paket menghindari optimized Composer classmap agar tidak menghasilkan file PHP besar yang ditolak oleh hosting.
- Ini untuk demo/staging awal. Jangan gunakan untuk data karyawan/keuangan nyata sebelum keamanan, backup, dan batasan hosting dinilai.

## A. Bangun dan unduh paket

1. Buka tab **Actions** di GitHub repository `ERP_MULTI_TALENT`.
2. Pilih workflow **Build InfinityFree deployment package**. Workflow otomatis berjalan saat ada push ke `main`; buka run terakhir dengan status hijau.
3. Di bagian **Artifacts**, unduh `erp-multi-talent-infinityfree` lalu ekstrak ZIP ke komputer.
4. ZIP tersebut berisi **isi document root** yang siap diunggah, bukan folder `htdocs` bertingkat.

## B. Unggah ke InfinityFree

1. Buka File Manager atau hubungkan klien FTP ke akun hosting.
2. Buka folder website bernama `htdocs` (atau folder `htdocs` domain yang tepat bila memakai addon domain).
3. Unggah **semua isi** ZIP hasil build tepat ke dalam folder itu. Jangan unggah file ZIP saja, dan jangan membuat lapisan folder `htdocs/htdocs`.
4. Struktur akhirnya seharusnya seperti:
   ```
   htdocs/
   ├── index.php
   ├── .htaccess
   ├── css/ dan aset publik lainnya
   ├── erp_app/
   │   ├── app/
   │   ├── bootstrap/
   │   ├── config/
   │   ├── vendor/
   │   ├── .htaccess
   │   └── .env.example
   └── storage/
       └── app/public/
   ```
5. Pastikan File Manager atau FTP ikut menampilkan file tersembunyi seperti `.htaccess`. File kode berada dalam `htdocs/erp_app` dan dilindungi aturan akses langsung.

## C. Siapkan konfigurasi rahasia dan database

1. Di dalam `htdocs/erp_app`, salin `.env.example` menjadi `.env`. Jangan unggah file `.env` ke GitHub.
2. Isi `APP_URL` dengan URL HTTPS hosting kamu.
3. Buat key aplikasi di komputer yang memiliki PHP, lalu simpan hasilnya hanya ke `.env`:
   ```powershell
   php -r "echo 'base64:' . base64_encode(random_bytes(32)) . PHP_EOL;"
   ```
   Salin hasil yang diawali `base64:` ke `APP_KEY=`.
4. Dari panel InfinityFree, buat database MySQL dan isi `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` pada file `.env`. Host database bukan selalu `localhost`; gunakan nama host persis dari panel database.
5. Biarkan `DB_PORT=3306`, `SESSION_DRIVER=file`, `CACHE_STORE=file`, `QUEUE_CONNECTION=sync`, dan `PUBLIC_STORAGE_ROOT=../storage/app/public`.
6. Pastikan folder `htdocs/erp_app/storage`, semua subfoldernya, `htdocs/erp_app/bootstrap/cache`, dan `htdocs/storage/app/public` dapat ditulis PHP. Mulai dengan permission standar hosting; ubah menjadi `775` hanya pada folder terkait jika muncul masalah permission.

## D. Tes situs

Buka `https://subdomain-kamu/login`. Domain harus sudah aktif dan SSL tersedia. Jika error 500 muncul, periksa `htdocs/erp_app/storage/logs/laravel.log` melalui File Manager. Jangan menampilkan log atau isi `.env` secara publik.

**Database belum berisi skema aplikasi.** Sampai ada persetujuan terpisah untuk metode inisialisasi skema, autentikasi dan fitur yang membutuhkan tabel belum siap digunakan. Tidak ada migrasi atau seeder yang dijalankan dalam proses paket ini.

## Batasan paket gratis

InfinityFree tidak menyediakan terminal untuk menjalankan Artisan, job queue latar belakang, scheduler, atau WebSocket server. Bila proyek melewati limit jumlah file/inode atau kebutuhan runtime, hosting gratis mungkin tidak cukup.

## Referensi resmi

- InfinityFree free hosting: https://www.infinityfree.com/
- Panduan resmi InfinityFree untuk Laravel: https://forum.infinityfree.com/t/how-to-install-a-laravel-site-on-infinityfree/118578
- Konfirmasi paket gratis tidak memiliki Git integration langsung: https://forum.infinityfree.com/t/github-integration/76439
- Batas FTP dan file yang boleh berada di dalam `htdocs`: https://forum.infinityfree.com/t/why-are-my-files-deleted-after-uploading-them/49310
