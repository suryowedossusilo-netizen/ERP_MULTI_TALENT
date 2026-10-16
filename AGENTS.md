# AGENTS.md — Aturan Kerja Wajib ERP_MULTI_TALENT

Dokumen ini adalah aturan wajib bagi setiap agen AI, asisten, atau kontributor yang membantu mengerjakan repository ini. Jika instruksi lain bertentangan dengan dokumen ini, utamakan keamanan repository dan persetujuan pemilik proyek.

## 1. Kendali pemilik proyek dan persetujuan

- Edo adalah pemilik proyek dan pengambil keputusan akhir.
- Jangan melakukan perubahan penting tanpa persetujuan Edo yang jelas dan spesifik.
- Persetujuan untuk satu tindakan hanya berlaku untuk tindakan tersebut; jangan menganggapnya sebagai izin untuk tindakan lain.
- Sebelum tindakan berisiko atau berdampak luas, jelaskan terlebih dahulu: file/area yang terdampak, perubahan yang direncanakan, alasan, risiko, dan cara verifikasi. Tunggu persetujuan sebelum melanjutkan.
- Perubahan penting mencakup, tetapi tidak terbatas pada: mengubah atau menghapus kode, mengubah konfigurasi, mengubah skema atau data database, menambah/menghapus dependensi, mengubah autentikasi atau otorisasi, mengubah workflow deployment, menghapus atau memindahkan file, mengubah pengaturan repository, membuat commit, melakukan push, merge, reset, rebase, atau force-push.
- Jika ruang lingkup izin tidak jelas, berhenti dan tanyakan. Jangan menebak persetujuan.

## 2. Aturan branch dan Git

- Kerjakan hanya pada branch `main`.
- Dilarang membuat branch baru, berpindah branch, menghapus branch, atau mengubah target branch tanpa persetujuan eksplisit Edo.
- Jangan menimpa, mereset, melakukan force-push, atau menghapus riwayat commit.
- Sebelum mengubah file yang sudah ada, baca isi dan kondisi terkini file tersebut terlebih dahulu.
- Jangan melakukan commit atau push kecuali tindakan tersebut termasuk dalam izin eksplisit yang diberikan Edo.
- Jangan mengklaim perubahan berhasil sebelum hasilnya dikonfirmasi oleh alat atau pemeriksaan yang sesuai.

## 3. Mode baca-saja

- Jika Edo meminta membaca, memeriksa, mengaudit, atau menganalisis saja, semua operasi wajib bersifat baca-saja.
- Dalam mode baca-saja, dilarang membuat, mengedit, menghapus, mengunggah, atau memindahkan file; dilarang membuat commit; dan dilarang mengubah branch, konfigurasi, atau data.
- Jangan menjalankan perintah yang dapat mengubah repository atau lingkungan proyek ketika hanya diminta membaca.
- Laporkan file dan area yang benar-benar telah diperiksa, keterbatasan pemeriksaan, serta temuan yang masih perlu diverifikasi.

## 4. Perlindungan rahasia dan konfigurasi

- Jangan pernah mengunggah file `.env`, kredensial, token akses, API key, kata sandi, private key, sertifikat privat, atau data rahasia ke repository.
- Jangan menyalin nilai rahasia ke log, laporan, komentar commit, issue, atau percakapan.
- Pertahankan aturan `.gitignore` yang melindungi rahasia. Jangan melemahkan atau menghapus aturan tersebut tanpa persetujuan Edo.
- Gunakan `.env.example` hanya untuk nama variabel dan contoh nilai palsu/nonrahasia. Jangan menyalin nilai rahasia dari lingkungan nyata.
- Jika rahasia terdeteksi, jangan menampilkannya. Laporkan lokasi secara aman dan minta arahan sebelum melakukan perubahan.

## 5. Keamanan aplikasi dan multi-tenant

- Perlakukan isolasi data antar-tenant sebagai prioritas keamanan tertinggi.
- Jangan menganggap filter pada tampilan atau daftar sudah cukup untuk mengamankan data.
- Periksa otorisasi pada setiap operasi baca, buat, ubah, dan hapus, termasuk model binding, endpoint, controller, policy, job, dan proses latar belakang.
- Pastikan identitas tenant berasal dari sumber tepercaya di sisi server, bukan hanya dari input pengguna atau nilai session yang dapat dimanipulasi.
- Periksa agar relasi, pencarian, laporan, ekspor, dan perubahan data selalu dibatasi ke tenant yang berhak.
- Terapkan prinsip hak akses minimum. Jangan memberikan akses administrator atau izin luas sebagai jalan pintas.
- Jangan menyatakan aplikasi aman hanya berdasarkan pembacaan sebagian file. Jelaskan batasan audit dan kebutuhan pengujian.

## 6. Database dan migrasi

- Jangan menjalankan migrasi, seeder, reset, truncate, atau operasi penghapusan data tanpa persetujuan eksplisit.
- Jangan mengubah skema database atau data produksi tanpa rencana, penjelasan dampak, dan persetujuan.
- Periksa foreign key, batasan unik, nullability, indeks, serta konsistensi `tenant_id` sebelum merekomendasikan perubahan.
- Jangan menganggap database sudah tersedia atau sudah dikonfigurasi sebelum ada bukti.

## 7. Cara memahami dan mengubah kode

- Pelajari struktur dan pola kode yang sudah ada sebelum menyarankan perubahan.
- Utamakan perubahan sekecil mungkin yang menyelesaikan masalah; hindari penulisan ulang besar tanpa izin.
- Jangan menghapus fitur atau mengubah perilaku yang sudah ada tanpa persetujuan.
- Jelaskan tujuan perubahan, cara kerja, risiko, dan langkah menjalankan atau mengujinya dengan bahasa Indonesia yang mudah dipahami.
- Jangan menambahkan dependensi atau layanan eksternal tanpa menjelaskan alasan dan meminta izin.
- Jangan mengarang hasil pengujian. Bedakan dengan jelas antara belum diuji, sudah dijalankan, dan lulus terverifikasi.

## 8. Pengujian dan pelaporan

- Setelah perubahan yang diizinkan, jalankan hanya pemeriksaan yang aman dan sesuai ruang lingkup izin.
- Laporkan file yang berubah, ringkasan perubahan, hasil pengujian yang benar-benar dijalankan, serta hal yang belum diverifikasi.
- Jika alat gagal atau akses tidak tersedia, katakan dengan jujur; jangan mengklaim pekerjaan selesai.
- Jangan melakukan tindakan lanjutan di luar izin hanya demi menyelesaikan tugas.

## 9. Prosedur wajib sebelum tindakan

1. Pastikan permintaan Edo dan ruang lingkup izin jelas.
2. Periksa keadaan repository dan file yang relevan.
3. Untuk tindakan yang membutuhkan persetujuan, jelaskan rencana dan tunggu persetujuan.
4. Lakukan hanya tindakan yang disetujui pada branch `main`.
5. Verifikasi hasil tanpa mengarang status.
6. Laporkan hasil dan sisa risiko kepada Edo.

## 10. Aturan utama

**Jika ragu, berhenti dan bertanya kepada Edo. Jangan melakukan perubahan penting berdasarkan asumsi. Keamanan, transparansi, dan persetujuan pemilik proyek selalu lebih penting daripada kecepatan.**
