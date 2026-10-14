# Pemesanan Tiket Event

Aplikasi web berbasis Laravel untuk menampilkan katalog event, menerima pesanan tiket, dan membantu admin mengelola event serta status pesanan.

## Fitur

- Pelanggan dapat melihat event dan memfilter daftar berdasarkan kategori: Musik, Festival, atau Seminar.
- Pelanggan memilih jumlah tiket untuk satu atau beberapa event, lalu mengisi nama dan email pemesan.
- Aplikasi menghitung subtotal setiap event dan total pesanan dari harga tiket dan jumlah tiket.
- Admin yang sudah login dapat melihat daftar pesanan, mengubah status pesanan, serta mengelola data event.
- Proses penyimpanan pesanan menggunakan transaksi database agar data pesanan dan detailnya tersimpan bersama-sama.

> **Catatan pembayaran:** aplikasi ini belum terhubung ke payment gateway. Status “Selesai / Lunas” diperbarui secara manual oleh admin.

## Teknologi

- PHP 8.2 atau lebih baru
- Laravel 12
- MySQL
- Node.js dan npm untuk membangun aset frontend

## Persiapan dan instalasi

1. Pastikan PHP, Composer, Node.js/npm, dan MySQL sudah terpasang.
2. Pasang dependency PHP:

   ```bash
   composer install
   ```

3. Buat file konfigurasi lokal `.env`:

   **PowerShell (Windows):**

   ```powershell
   Copy-Item .env.example .env
   ```

   **macOS/Linux:**

   ```bash
   cp .env.example .env
   ```

4. Buat database MySQL, lalu sesuaikan konfigurasi berikut di `.env` dengan pengaturan lokal:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pesantiket_serkom
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. Buat application key:

   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi dan data awal:

   ```bash
   php artisan migrate --seed
   ```

   Seeder menambahkan beberapa contoh event serta satu akun admin lokal.

7. Pasang dependency frontend dan build aset:

   ```bash
   npm install
   npm run build
   ```

8. Jalankan server pengembangan:

   ```bash
   php artisan serve
   ```

   Buka alamat yang ditampilkan Artisan (biasanya `http://127.0.0.1:8000`).

## Cara kerja pemesanan

1. Halaman utama (`/`) mengambil semua event dari database dan menampilkan katalog kepada pelanggan.
2. Filter kategori bekerja di browser untuk menyembunyikan atau menampilkan kartu event; filter tidak mengubah data di database.
3. Pelanggan mengisi nama dan email, kemudian memilih jumlah tiket. Jumlah nol tidak dimasukkan ke pesanan.
4. Saat formulir dikirim ke `POST /checkout`, aplikasi memvalidasi data pemesan dan jumlah tiket. Pesanan ditolak jika tidak ada tiket yang dipilih.
5. Aplikasi mengambil harga event dari database, menghitung `subtotal = harga tiket × jumlah`, lalu menghitung total seluruh detail pesanan.
6. Data header pesanan dan detail tiket disimpan dalam transaksi database. Status awal pesanan adalah `Pending`.
7. Jika penyimpanan berhasil, pelanggan kembali ke halaman utama dengan pesan sukses dan total harga. Jika gagal, transaksi dibatalkan dan pesan error ditampilkan.

Harga total dihitung di server menggunakan harga event di database, bukan mempercayai harga dari browser.

## Cara kerja admin dan status pesanan

- `/dashboard` menampilkan rekap pesanan dan hanya dapat diakses oleh pengguna yang login serta sudah memverifikasi email.
- Admin mengubah status pesanan melalui dropdown. Perubahan dikirim ke `PATCH /admin/orders/{id}/status`.
- Nilai status yang diterima harus mengikuti enum pada database:

  | Nilai database | Arti di aplikasi |
  | --- | --- |
  | `Pending` | Menunggu konfirmasi |
  | `Confirmed` | Selesai / lunas |
  | `Cancelled` | Dibatalkan |

- Label pada dropdown boleh menggunakan bahasa yang lebih ramah pengguna, tetapi nilai yang dikirim harus tetap salah satu dari tiga nilai di atas.
- Rute pengelolaan event (`/admin/events`) memerlukan login. Saat ini, middleware yang digunakan adalah autentikasi; aplikasi belum membedakan hak akses admin dan pengguna biasa dengan role khusus.

## Akun lokal dari seeder

Seeder saat ini membuat akun berikut untuk pengembangan lokal:

- Email: `admin@gmail.com`
- Password: `admin123`

Ganti kredensial ini sebelum aplikasi digunakan di lingkungan bersama atau production. Jangan gunakan akun contoh ini sebagai kredensial deployment.

## Struktur terkait

- `routes/web.php` — rute katalog, checkout, dashboard, dan pengelolaan event.
- `app/Http/Controllers/OrdersController.php` — menampilkan katalog, memproses checkout, dan memperbarui status.
- `app/Http/Controllers/EventsController.php` — operasi pengelolaan event.
- `app/Models/` — model event, pesanan, dan detail pesanan.
- `database/migrations/` — struktur tabel event, pesanan, dan detail pesanan.
- `database/seeders/` — data event dan akun awal untuk pengembangan.
- `resources/views/customer/index.blade.php` — halaman katalog dan formulir checkout.
- `resources/views/dashboard.blade.php` — rekap pesanan admin.

## Menjalankan test

```bash
php artisan test
```

## Lisensi

Proyek ini menggunakan Laravel. Lihat dokumentasi Laravel untuk informasi framework dan dependensinya.
