# CodeIgniter 4 — InfinityFree Hosting & Maintenance Guide

Panduan lengkap arsitektur, cara deploy cepat, kompatibilitas PHP 8.4+, dan panduan update di masa depan untuk aplikasi **Daily Tools** di hosting **InfinityFree**.

---

## 1. Arsitektur Folder di InfinityFree

Di InfinityFree, root web publik yang dapat diakses oleh browser adalah folder **`htdocs/`**. Untuk keamanan dan kompatibilitas, aplikasi menggunakan struktur pemisahan:

```text
/home/vol10_4/.../htdocs/
│
├── .htaccess                     # Apache Rewrite (Semua directive Options DIKOMENTARI)
├── index.php                     # Front controller (Mengarahkan ke app_core/)
├── favicon.ico
├── robots.txt
├── template_stisla/              # Aset statis (CSS, JS, gambar, template)
│
└── app_core/                     # Backend CodeIgniter (Terkunci dari web langsung)
    ├── app/
    │   ├── Config/
    │   │   ├── App.php           # Memiliki $allowedHostnames & $proxyIPs (array)
    │   │   ├── Exceptions.php    # Memiliki $logDeprecations & $deprecationLogLevel
    │   │   ├── Kint.php          # Kint v5 AbstractRenderer::SORT_FULL
    │   │   ├── Routes.php        # Route eksplisit (Auth, Home, Series, dll.)
    │   │   ├── Routing.php       # Config Routing ($autoRoute = true)
    │   │   └── Session.php       # Config Session dedicated CI 4.5
    │   ├── Controllers/
    │   ├── Models/
    │   └── Views/
    ├── vendor/                   # Framework CodeIgniter 4.5+ & Composer packages
    ├── writable/                 # Cache, logs, session
    └── .env                      # Konfigurasi environment (CI_ENVIRONMENT = production)
```

---

## 2. Aturan Kritis InfinityFree (Wajib Diingat!)

### A. Kebijakan `.htaccess`
* **JANGAN PERNAH** menggunakan directive `Options` (misal `Options -Indexes`, `Options +FollowSymlinks`, `Options +SymLinksIfOwnerMatch`).
* **JANGAN PERNAH** menggunakan `ServerSignature Off`.
* *Akibat jika dilanggar:* Server langsung memunculkan halaman error: `"Something Went Wrong! The issue is likely due to a code error within the .htaccess file"`.
* Gunakan template `.htaccess` yang sudah disesuaikan di `public/.htaccess`.

### B. Batasan `open_basedir`
* InfinityFree membatasi PHP hanya boleh membaca file di dalam `/.../htdocs/`.
* Script tidak boleh membaca direktori induk (`../`).
* Oleh karena itu, seluruh backend berada di dalam `htdocs/app_core/` (bukan di luar `htdocs/`).

---

## 3. Kompatibilitas PHP 8.4 & CodeIgniter 4.5+ Gotchas

Jika di masa depan InfinityFree memperbarui versi PHP atau Anda memperbarui framework, berikut daftar perbaikan yang sudah diterapkan dan harus dijaga:

| Isu / Gejala | Penyebab di PHP 8.4+ / CI 4.5 | Solusi / Standar Kode |
| :--- | :--- | :--- |
| `TypeError: BaseBuilder::where(): Argument #3 ($escape) must be ?bool, int given` | Menulis `where('kolom', '!=', 1)` | Operator harus disatukan di argumen 1: `where('kolom !=', 1)` |
| `Fatal error: TimeTrait::createFromTimestamp(...) must be compatible with DateTimeImmutable` | PHP 8.4 menambahkan fungsi native `createFromTimestamp(int\|float $timestamp)` | Parameter di `system/I18n/TimeTrait.php` baris 268 harus bertipe `int\|float $timestamp` |
| `Fatal error: Undefined property: Config\App::$allowedHostnames` | CI 4.4+ mewajibkan property `$allowedHostnames` | Tambahkan `public array $allowedHostnames = [];` di `app/Config/App.php` |
| `ErrorException: foreach() argument must be of type array, string given` pada IP address | `$proxyIPs` bertipe string kosong `''` | Ubah menjadi `public array $proxyIPs = [];` di `app/Config/App.php` |
| `Deprecated: Creation of dynamic property Config\Exceptions::$logDeprecations` | Dynamic properties dideprecate di PHP 8.2+ | Definisikan properti eksplisit di `app/Config/Exceptions.php` |
| `Fatal error: Call to undefined method ...::cleanPath()` di `error_exception.php` | Template error CI 4.1 memanggil static method lama | Gunakan fungsi helper global `clean_path($file)` di `error_exception.php` |
| `404 - Cannot access CLI Route: Auth` | AutoRouter memblokir rute web yang tidak terdaftar eksplisit | Daftarkan rute eksplisit di `app/Config/Routes.php` (misal `$routes->get('Auth', 'Auth::index');`) |

---

## 4. Prosedur Update Cepat di Masa Depan (Tanpa Upload Ribuan File)

Upload ribuan file terpisah melalui FTP sering gagal atau sangat lambat. Gunakan salah satu metode berikut:

### Metode 1: Update Harian (Hanya File yang Diedit)
Jika hanya mengedit Controller, View, Model, atau Rute:
* Cukup upload **1 file yang diedit** ke lokasi tujuannya di `htdocs/app_core/app/...`.
* **Jangan sentuh folder `vendor/`** jika tidak ada instalasi library baru.

### Metode 2: Update Dependensi / Folder Vendor (Metode "1-ZIP")
Jika menjalankan `composer update` atau mengupdate framework:
1. Di komputer lokal, zip folder `vendor` menjadi `vendor.zip`.
2. Upload file `vendor.zip` ke `htdocs/app_core/` melalui File Manager InfinityFree (hanya 1 file, selesai dalam hitungan detik).
3. Buat file `unzip_helper.php` di dalam `htdocs/app_core/`:
   ```php
   <?php
   $zip = new ZipArchive;
   if ($zip->open(__DIR__ . '/vendor.zip') === TRUE) {
       $zip->extractTo(__DIR__ . '/');
       $zip->close();
       unlink(__DIR__ . '/vendor.zip');
       unlink(__FILE__);
       echo "Berhasil diekstrak dan dibersihkan!";
   } else {
       echo "Gagal membuka file zip.";
   }
   ```
4. Akses melalui browser: `https://daily-tools.infinityfreeapp.com/app_core/unzip_helper.php`.
5. Selesai! Seluruh `vendor` terekstrak di server dalam 1 detik tanpa file corrupt.