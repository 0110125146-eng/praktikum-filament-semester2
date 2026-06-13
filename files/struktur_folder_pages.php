<!-- 
=================================================================
STRUKTUR FOLDER LENGKAP PROYEK
=================================================================
Buat file-file berikut sesuai path yang tertera:
=================================================================

perpustakaan/
├── app/
│   ├── Filament/
│   │   ├── Resources/
│   │   │   ├── AnggotaResource.php          ← dari filament_resources.php
│   │   │   ├── AnggotaResource/Pages/
│   │   │   │   ├── ListAnggota.php
│   │   │   │   ├── CreateAnggota.php
│   │   │   │   └── EditAnggota.php
│   │   │   ├── BukuResource.php             ← dari filament_resources.php
│   │   │   ├── BukuResource/Pages/
│   │   │   │   ├── ListBuku.php
│   │   │   │   ├── CreateBuku.php
│   │   │   │   └── EditBuku.php
│   │   │   ├── KategoriResource.php
│   │   │   ├── PenulisResource.php
│   │   │   ├── PeminjamanResource.php
│   │   │   ├── PeminjamanResource/Pages/
│   │   │   │   ├── ListPeminjaman.php
│   │   │   │   ├── CreatePeminjaman.php
│   │   │   │   ├── ViewPeminjaman.php
│   │   │   │   └── EditPeminjaman.php
│   │   │   └── UserResource.php             ← dari rbac_setup.php
│   │   └── Widgets/
│   │       └── StatsOverview.php            ← dari dashboard_widgets_seeders.php
│   ├── Models/
│   │   ├── User.php                         ← dari rbac_setup.php
│   │   ├── Anggota.php
│   │   ├── Buku.php
│   │   ├── Kategori.php
│   │   ├── Peminjaman.php
│   │   ├── DetailPeminjaman.php
│   │   └── Penulis.php
│   └── Providers/
│       └── Filament/
│           └── AdminPanelProvider.php
├── database/
│   ├── migrations/
│   │   ├── xxxx_create_anggota_table.php
│   │   ├── xxxx_create_petugas_table.php
│   │   ├── xxxx_create_kategori_table.php
│   │   ├── xxxx_create_penulis_table.php
│   │   ├── xxxx_create_buku_table.php
│   │   ├── xxxx_create_buku_has_penulis_table.php
│   │   ├── xxxx_create_peminjaman_table.php
│   │   └── xxxx_create_detail_peminjaman_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolePermissionSeeder.php
│       ├── KategoriSeeder.php
│       ├── PenulisSeeder.php
│       ├── BukuSeeder.php
│       └── AnggotaSeeder.php
└── config/
    └── permission.php (auto-generated saat vendor:publish)
-->


<!-- ================================================================ -->
<!-- CARA BUAT PAGES RESOURCE (sama untuk semua resource)             -->
<!-- ================================================================ -->
<?php
// Contoh: app/Filament/Resources/BukuResource/Pages/ListBuku.php
namespace App\Filament\Resources\BukuResource\Pages;

use App\Filament\Resources\BukuResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuku extends ListRecords
{
    protected static string $resource = BukuResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}

// app/Filament/Resources/BukuResource/Pages/CreateBuku.php
class CreateBuku extends \Filament\Resources\Pages\CreateRecord
{
    protected static string $resource = BukuResource::class;
}

// app/Filament/Resources/BukuResource/Pages/EditBuku.php
class EditBuku extends \Filament\Resources\Pages\EditRecord
{
    protected static string $resource = BukuResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}

// ---- Lakukan hal yang sama untuk: ----
// AnggotaResource/Pages/ → ListAnggota, CreateAnggota, EditAnggota
// KategoriResource/Pages/ → ListKategori, CreateKategori, EditKategori
// PenulisResource/Pages/ → ListPenulis, CreatePenulis, EditPenulis
// PeminjamanResource/Pages/ → ListPeminjaman, CreatePeminjaman, ViewPeminjaman, EditPeminjaman
// UserResource/Pages/ → ListUsers, CreateUser, EditUser
