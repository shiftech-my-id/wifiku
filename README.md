# Wifiku Billing

**Wifiku Billing** adalah aplikasi modern untuk manajemen tagihan layanan WiFi, dirancang untuk membantu penyedia layanan internet skala kecil hingga menengah dalam mengelola pelanggan, layanan, pembayaran, dan laporan secara efisien. Aplikasi ini mobile-first, mendukung cetak invoice via thermal printer Bluetooth, dan dapat dikonversi menjadi APK untuk memberikan pengalaman layaknya aplikasi native bagi end user.

## ✨ Fitur Utama

-   **Manajemen User & Group**
    -   Mendukung _custom access role_ (hak akses sesuai kebutuhan)
    -   Modul user group untuk pembagian peran dan otorisasi granular
-   **Manajemen Layanan**
    -   Pengaturan jenis layanan, paket, dan harga
    -   Penentuan masa aktif dan kebijakan pembayaran
-   **Manajemen Customer**
    -   Data pelanggan lengkap (kontak, alamat, layanan aktif)
    -   Riwayat pembayaran dan status tagihan
-   **Tagihan & Pembayaran**
    -   Pembuatan tagihan otomatis atau manual
    -   Mendukung cetak invoice via thermal printer Bluetooth
    -   Pencatatan pembayaran tunai atau metode lainnya
-   **Biaya Operasional**
    -   Pencatatan biaya operasional dan kategorinya
    -   Analisis pengeluaran untuk evaluasi bisnis
-   **Dashboard & Laporan**
    -   Ringkasan pendapatan, tunggakan, dan biaya
    -   Laporan terperinci per periode
    -   Ekspor data (CSV/PDF)
-   **Mobile-First Design**
    -   Optimal untuk digunakan di perangkat mobile
    -   Dapat dikonversi ke APK (WebView) untuk pengalaman seperti aplikasi native

## 📦 Teknologi yang Digunakan

-   **Frontend**: Quasar Framework (Vue 3, TypeScript), Inertia
-   **Backend**: Laravel 11 (PHP 8.3), Inertia
-   **Database**: MySQL / MariaDB
-   **Printing**: Thermal printer Bluetooth (ESC/POS)

## ⚙️ Instalasi & Setup

1.  **Clone repository**

    ```
    git clone [https://github.com/username/wifiku-billing.git](https://github.com/username/wifiku-billing.git)
    cd wifiku-billing
    ```

2.  **Install dependencies**
    -   Backend:
    ```
    composer install
    cp .env.example .env
    php artisan key:generate
    ```
    -   Frontend:
    ```
    cd frontend
    npm install
    ```
3.  **Konfigurasi environment**
    -   Sesuaikan koneksi database, API URL, dan setting lainnya di .env
4.  **Migrasi & seeding database**

    ```
    php artisan migrate --seed
    ```

5.  **Jalankan aplikasi**
    -   Backend:
    ```
    php artisan serve
    ```
    -   Frontend:
    ```
    npm run dev
    ```

## 📄 Lisensi

Hak cipta ©2025 shiftech.my.id. Proyek ini dilisensikan di bawah [MIT License](https://chatgpt.com/c/LICENSE).

## 📌 Catatan Pengembangan

-   Versi saat ini fokus pada **MVP** untuk kebutuhan internal / pilot project.
