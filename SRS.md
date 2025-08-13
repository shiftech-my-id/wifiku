# Software Requirements Specification (SRS) – Wifiku Billing v1

1. Pendahuluan
   1.1 Tujuan
   Dokumen ini menjelaskan kebutuhan fungsional dan non-fungsional untuk pengembangan Wifiku Billing v1, sebuah aplikasi manajemen tagihan WiFi yang dirancang untuk ISP lokal dan penyedia internet rumahan. Versi ini fokus pada Minimum Viable Product (MVP) dengan fitur utama billing, pembayaran, laporan, dan cetak invoice.

1.2 Ruang Lingkup
Wifiku Billing adalah aplikasi berbasis web dengan desain mobile-first yang mendukung konversi ke aplikasi Android (web-to-app).
Fitur utama meliputi:

-   Manajemen pengguna dan grup akses (custom access role)
-   Manajemen layanan dan pelanggan
-   Pembuatan dan pengelolaan tagihan
-   Pencatatan pembayaran manual
-   Pengelolaan biaya operasional
-   Dashboard dan laporan ringkas
-   Cetak invoice via thermal printer Bluetooth

    1.3 Definisi & Singkatan

-   ISP: Internet Service Provider
-   MVP: Minimum Viable Product
-   Invoice: Bukti tagihan yang diberikan ke pelanggan
-   Thermal Printer: Printer kecil khusus cetak struk/invoice

2. Deskripsi Umum
   2.1 Perspektif Produk
   Aplikasi akan digunakan oleh:

-   Admin (pemilik usaha atau staf keuangan)
-   Teknisi (opsional, untuk update status layanan pelanggan)
-   Kasir (opsional, untuk mencatat pembayaran)
-   Aplikasi terintegrasi dengan database lokal/online dan mendukung cetak invoice melalui Bluetooth.

    2.2 Fungsi Utama Sistem

-   User & Role Management – membuat, mengedit, dan menghapus pengguna serta menetapkan hak akses.
-   Service & Customer Management – mengelola paket layanan dan data pelanggan.
-   Billing Management – membuat, mengedit, menghapus, dan melihat tagihan.
-   Payment Recording – mencatat pembayaran manual, dengan status lunas atau belum lunas.
-   Operational Cost Management – mencatat biaya operasional dan kategorinya.
-   Dashboard & Reporting – menampilkan ringkasan data dan membuat laporan sederhana.
-   Invoice Printing – mencetak invoice dengan thermal printer Bluetooth.

    2.3 Karakteristik Pengguna

-   Admin → Mengelola semua modul
-   Staf Keuangan → Mengelola billing, pembayaran, laporan
-   Teknisi → Mengelola data pelanggan & status layanan

    2.4 Kendala

-   Dukungan thermal printer terbatas pada model yang umum digunakan di pasar
-   Perangkat pengguna minimal Android 7.0 atau browser modern
-   Target rilis MVP pada Agustus 2025

3. Kebutuhan Fungsional (Functional Requirements)
   ID Nama Fitur Deskripsi
   FR-01 Login & Autentikasi Pengguna harus login untuk mengakses sistem berdasarkan role
   FR-02 Manajemen User & Group CRUD user dan group, pengaturan hak akses
   FR-03 Manajemen Layanan CRUD paket layanan
   FR-04 Manajemen Pelanggan CRUD data pelanggan
   FR-05 Manajemen Tagihan Membuat tagihan, mengatur jatuh tempo, status
   FR-06 Pencatatan Pembayaran Mencatat pembayaran manual dan status lunas
   FR-07 Manajemen Biaya Operasional CRUD data biaya operasional dan kategori
   FR-08 Dashboard & Laporan Menampilkan ringkasan data dan laporan PDF/print
   FR-09 Cetak Invoice Print invoice via thermal printer Bluetooth

4. Kebutuhan Non-Fungsional (Non-Functional Requirements)
   ID Kategori Deskripsi
   NFR-01 Performa Waktu respon ≤ 2 detik untuk operasi utama
   NFR-02 Keamanan Semua data transaksi harus terlindungi dengan autentikasi & HTTPS
   NFR-03 Portabilitas Berjalan di browser desktop & mobile modern
   NFR-04 Skalabilitas Desain modular untuk kemudahan pengembangan fitur baru
   NFR-05 Ketersediaan Sistem tersedia 99% selama jam operasional ISP

5. Kriteria Penerimaan (Acceptance Criteria)

-   Semua fitur fungsional berjalan sesuai deskripsi tanpa bug kritis
-   Invoice dapat dicetak melalui thermal printer Bluetooth
-   Dashboard menampilkan data akurat dari transaksi yang tercatat
-   UI responsif di perangkat mobile dan desktop
