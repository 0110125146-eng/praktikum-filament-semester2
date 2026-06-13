# Sistem Informasi Perpustakaan
## Laravel 12 + Filament 5 + Spatie Permission

---

## Setup Awal

```bash
composer create-project laravel/laravel perpustakaan
cd perpustakaan
composer require filament/filament:"^3.0"
composer require spatie/laravel-permission
composer require intervention/image
```

---

## Konfigurasi .env

```env
APP_NAME="Perpustakaan"
DB_DATABASE=perpustakaan_uas
DB_USERNAME=root
DB_PASSWORD=
```

---

## Urutan Instalasi

```bash
# 1. Install Filament Panel
php artisan filament:install --panels

# 2. Publish Spatie config
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 3. Jalankan semua migration
php artisan migrate

# 4. Jalankan seeder
php artisan db:seed

# 5. Buat storage link
php artisan storage:link

# 6. Jalankan server
php artisan serve
```

---

## Akun Default (setelah seeder)

| Role    | Email                  | Password |
|---------|------------------------|----------|
| Admin   | admin@perpus.com       | password |
| Petugas | petugas@perpus.com     | password |

---

## Fitur

- ✅ 7 tabel database (melebihi minimal 5)
- ✅ CRUD via Filament Resource
- ✅ One-to-Many: Kategori → Buku, Peminjaman → Detail
- ✅ Many-to-Many: Buku ↔ Penulis
- ✅ Upload cover buku (gambar)
- ✅ Authentication login/logout
- ✅ RBAC: Role Admin & Petugas (Spatie Permission)
