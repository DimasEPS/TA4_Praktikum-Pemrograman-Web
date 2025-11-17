# Tugas Akhir Praktikum Pemrograman Web - Judul 4

## Sistem Manajemen Kontak Sederhana

Project ini merupakan implementasi dari Tugas Akhir Judul 4 Praktikum Pemrograman Web yang membangun sistem manajemen kontak dengan PHP dan Session Management.

### Fitur Utama

1. **Form Tambah Kontak dengan Validasi**

   - Validasi nama (hanya huruf dan spasi)
   - Validasi nomor telepon (angka, +, -, dan spasi)
   - Validasi email (format email valid)
   - Field alamat opsional

2. **Tampilan Daftar Kontak**

   - Menampilkan semua kontak dalam bentuk tabel
   - Informasi lengkap: ID, Nama, Telepon, Email, Alamat

3. **Fitur Edit dan Hapus**

   - Edit kontak yang sudah ada
   - Hapus kontak dengan konfirmasi
   - Flash message untuk feedback operasi

4. **Session Management**
   - Sistem login dengan autentikasi
   - Session untuk menyimpan data kontak
   - Proteksi halaman dengan pengecekan login

### Cara Menjalankan

1. Clone repository ini
2. Pastikan PHP sudah terinstall (minimal PHP 7.4)
3. Jalankan PHP built-in server:
   ```bash
   php -S localhost:8000
   ```
4. Buka browser dan akses: `http://localhost:8000/login.php`
5. Login dengan kredensial default:
   - Username: `admin`
   - Password: `123456`

### Struktur File

- `login.php` - Halaman login
- `contacts.php` - Halaman utama (daftar kontak dan form tambah)
- `edit-contacts.php` - Halaman edit kontak
- `delete-contact.php` - Handler untuk hapus kontak
- `logout.php` - Handler untuk logout

### Catatan

Data kontak disimpan dalam PHP Session, sehingga data akan hilang ketika session berakhir atau browser ditutup.
