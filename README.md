# Sistem Pengelolaan Zakat Maal

Aplikasi web berbasis **PHP** dan **MySQL** untuk mengelola zakat maal: pencatatan muzakki, transaksi pembayaran zakat, kalkulator zakat, serta laporan. Didesain untuk dijalankan di **XAMPP** (Apache + MariaDB).

---

## Fitur

### Halaman Publik
- **Beranda** — ringkasan jumlah muzakki dan total zakat terkumpul.
- **Info & Panduan** — materi nisab, kadar, syarat wajib, jenis harta, dan 8 asnaf penerima zakat.
- **Kalkulator Zakat** — hitung zakat dari uang tunai, tabungan, dan emas secara otomatis.

### Panel Admin (perlu login)
- **Dashboard** — statistik muzakki, transaksi, dan zakat terkumpul + transaksi terbaru.
- **Muzakki** — kelola data pembayar zakat (tambah / ubah / hapus / cari).
- **Transaksi** — catat pembayaran zakat dengan jumlah zakat terhitung otomatis dari harta muzakki.
- **Laporan** — filter laporan per periode & metode, cetak, dan export CSV.
- **Users** — kelola akun admin.

---

## Teknologi

- PHP (prosedural, `mysqli`, prepared statement)
- MySQL / MariaDB
- CSS murni (tanpa framework)
- XAMPP

---

## Kebutuhan

- XAMPP (Apache + MySQL/MariaDB aktif)
- Browser modern

---

## Cara Instalasi

1. **Salin folder** ini ke direktori `htdocs` XAMPP:
   ```
   /Applications/XAMPP/xamppfiles/htdocs/zakat/
   ```

2. **Import database** menggunakan phpMyAdmin **atau** terminal:
   ```sql
   -- phpMyAdmin: buat database baru lalu import file database/zakat.sql
   -- atau lewat terminal:
   mysql -u root < database/zakat.sql
   ```
   Skema lengkap ada di `database/zakat.sql` (membuat DB `zakat` beserta tabel dan data awal).

3. **Pastikan** kredensial koneksi di `includes/db.php` sesuai:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "zakat";
   ```

4. **Jalankan** Apache & MySQL dari XAMPP Control Panel, lalu buka browser:
   - Web publik: `http://localhost/zakat/`
   - Login admin: `http://localhost/zakat/login.php`

---

## Akun Admin Default

| Username | Password  |
|----------|-----------|
| `admin`  | `admin123` |

> Ganti password melalui menu **Users** setelah masuk.

---

## Logika Perhitungan Zakat

- **Nisab** = 85 gram emas
- **Kadar** = 2,5%
- Harga emas per gram (default Rp 1.300.000) dapat diubah dari halaman kalkulator.
- Zakat dihitung jika **total harta ≥ nisab**:
  ```
  Zakat = Total Harta × 2,5%
  ```
- Pada pencatatan transaksi, jumlah zakat otomatis dihitung dari `total_harta` muzakki.

---

## Struktur Folder

```
zakat/
├── database/
│   └── zakat.sql            # skema & data awal database
├── includes/
│   ├── db.php               # koneksi database
│   ├── auth.php             # proteksi halaman admin
│   ├── functions.php        # helper zakat & format rupiah
│   ├── header.php           # layout publik
│   ├── header_admin.php     # layout admin
│   └── footer.php           # penutup layout
├── assets/
│   └── css/style.css        # styling
├── index.php                # beranda publik
├── info.php                 # panduan zakat
├── kalkulator.php           # kalkulator zakat
├── login.php                # login admin
├── logout.php               # keluar sesi
└── admin/                   # panel admin (dilindungi login)
    ├── index.php            # dashboard
    ├── muzakki.php          # + tambah/edit/hapus
    ├── transaksi.php        # + tambah/edit/hapus
    ├── laporan.php          # laporan & export CSV
    └── users.php            # + tambah/edit/hapus akun admin
```

---

## Struktur Database

- **users** — `id`, `nama`, `username`, `password`, `created_at`
- **muzakki** — `id`, `nama`, `jenis_kelamin`, `alamat`, `no_hp`, `jenis_harta`, `total_harta`, `created_at`
- **transaksi_zakat** — `id`, `muzakki_id` (FK), `jumlah_zakat`, `metode`, `tanggal_pembayaran`, `created_at`

---

## Keamanan

- Password tersimpan dengan `password_hash` (bcrypt).
- Koneksi MySQL menggunakan prepared statement / `mysqli_real_escape_string`.
- Seluruh halaman admin dilindungi cek sesi login (`includes/auth.php`).
- Output data menggunakan `htmlspecialchars` untuk mencegah XSS.

---

## Lisensi

Proyek ini bebas digunakan untuk keperluan belajar, tugas, maupun pengelolaan internal.