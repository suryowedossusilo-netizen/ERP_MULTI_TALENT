# Deployment ERP_MULTI_TALENT ke InfinityFree (paket gratis)

Target ini ditujukan untuk **demo/staging awal**, bukan langsung untuk data perusahaan yang sensitif. Paket gratis InfinityFree mengiklankan PHP 8.3, MySQL 8.0/MariaDB, SSL, penyimpanan 5 GB, dan tanpa kartu pembayaran. Paket gratis tidak menyediakan SSH/terminal; karena itu dependensi Composer disiapkan oleh GitHub Actions sebelum diunggah.

## Syarat dan batasan

- Gunakan paket gratis saja; jangan aktifkan upgrade atau layanan berbayar.
- Workflow hanya menyiapkan berkas dan dependency Laravel. Workflow **tidak** menjalankan `migrate`, `db:seed`, atau mengubah database.
- Repository ini publik. File `.env`, APP_KEY, kata sandi, dan kredensial database **tidak boleh** masuk ke GitHub.
- InfinityFree tidak menyediakan SSH/Composer/Artisan di server gratis. Dependensi dibangun di GitHub Actions dan diunggah menggunakan FTP/File Manager. Host juga menerapkan batas tertentu untuk file PHP, sehingga workflow menghindari Composer optimized classmap dan memeriksa file PHP yang mendekati 1 MB.
- Jangan gunakan untuk data karyawan/keuangan nyata sebelum audit keamanan, pencadangan, dan izin akses selesai. Hosting gratis/shared memiliki batas sumber daya dan bukan pengganti layanan produksi yang terkelola.

## Langkah deployment

1. Buat akun gratis di https://www.infinityfree.com/ menggunakan email. Pilih subdomain gratis dan aktifkan/konfirmasikan SSL sebelum menggunakan HTTPS.
2. Di panel hosting, buat database MySQL. Catat **hostname database, nama database, username, dan password** secara pribadi. Jangan kirim password FTP/database di chat.
3. Buka repository GitHub `ERP_MULTI_TALENT` → tab **Actions** → workflow **Build InfinityFree deployment package**. Workflow otomatis berjalan pada push ke `main`; tunggu status hijau.
4. Buka run yang berhasil, unduh artifact **erp-multi-talent-infinityfree**, lalu ekstrak ZIP hasil unduhan di komputer.
5. Dengan FileZilla/FTP, unggah folder **`erp_app`** ke direktori akun yang sejajar dengan `htdocs`. Unggah **isi** folder `htdocs` paket ke folder `htdocs` hosting. Jangan menaruh seluruh folder Laravel di dalam `htdocs`; kode aplikasi harus berada di luar document root.
6. Di `erp_app`, salin `.env.example` menjadi `.env`. Isi:
   - `APP_URL`: alamat HTTPS subdomain yang kamu pilih.
   - `APP_KEY`: isi key acak 32-byte dengan awalan `base64:`. Jika memakai Termux yang sudah memiliki PHP, jalankan perintah berikut dan salin hasilnya ke `.env`:
     `php -r 'echo "base64:".base64_encode(random_bytes(32)).PHP_EOL;'`
   - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`: nilai persis dari panel MySQL hosting.
   - `PUBLIC_STORAGE_ROOT=../htdocs/storage`
   Jangan unggah file `.env` ke repository.
7. Pastikan folder `erp_app/storage`, subfolder di dalamnya, dan `erp_app/bootstrap/cache` dapat ditulis oleh PHP. Jika perlu, atur permission melalui File Manager menjadi `775` untuk folder tersebut, bukan untuk seluruh akun.
8. Buka `https://subdomain-kamu/login`. Jika muncul 500, cek `erp_app/storage/logs/laravel.log`; jangan menampilkan log atau file `.env` ke publik.
9. Database baru masih kosong. Sampai ada persetujuan terpisah untuk inisialisasi skema database, login dan fitur yang membutuhkan tabel **belum siap digunakan**. Tidak ada migrasi atau seeder yang dijalankan oleh paket ini.

## Jika build gagal

Buka run workflow yang merah di tab Actions dan lihat log langkah yang gagal. Jangan menambahkan file `.env` atau kredensial sebagai workaround. Paket dapat dibangun ulang dengan **Run workflow** setelah masalah diperbaiki.

## Opsi pembanding

- **InfinityFree** — cocok untuk demo PHP/MySQL yang bisa diunggah via FTP; tidak ada terminal di paket gratis.
- **Byet.host** — juga mengiklankan PHP 8.3, MySQL 8, 5 GB, SSL subdomain, dan tanpa kartu. Namun, sebelum memilihnya, verifikasi sendiri pembatasan Laravel dan akses servernya.
- **x10Hosting** — paket gratisnya mengiklankan PHP/MySQL dan tanpa kartu, tetapi halaman resminya saat diperiksa menyatakan pendaftaran sedang ditutup karena maintenance terjadwal.
- **alwaysdata** — paling jelas menyebut dukungan Laravel dan menyediakan SSH/Composer tanpa kartu, tetapi paket gratis hanya untuk penggunaan personal dan terbatas pada domain `alwaysdata.net`; ketentuannya melarang situs perusahaan/penggunaan komersial. Karena ERP ini berpotensi dipakai perusahaan, bukan pilihan utama untuk deployment nyata.

## Referensi resmi

- InfinityFree: https://www.infinityfree.com/
- InfinityFree support (SSH/terminal tidak tersedia di paket gratis): https://forum.infinityfree.com/t/can-i-ssh-into-the-host-and-run-commands-like-npm-install/59811
- InfinityFree support (upload `vendor` dan batas file PHP): https://forum.infinityfree.com/t/about-laravel-installation/104204
- Byet.host: https://byet.host/free-hosting
- x10Hosting: https://www.x10hosting.com/
- alwaysdata Laravel: https://www.alwaysdata.com/en/laravel-hosting/
- alwaysdata free-use restrictions: https://help.alwaysdata.com/en/docs/admin-billing/profile/suspension/
