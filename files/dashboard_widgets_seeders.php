<?php
// ============================================================
// DASHBOARD WIDGETS + SEEDERS CONTOH DATA
// ============================================================

// ==================== Dashboard Widget (app/Filament/Widgets/StatsOverview.php) ====================
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Buku', Buku::count())
                ->description('Koleksi buku perpustakaan')
                ->icon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Total Anggota', Anggota::count())
                ->description('Anggota terdaftar')
                ->icon('heroicon-m-users')
                ->color('success'),

            Stat::make('Peminjaman Aktif',
                Peminjaman::where('status', 'dipinjam')->count()
            )
                ->description('Sedang dipinjam')
                ->icon('heroicon-m-clipboard-document-list')
                ->color('warning'),

            Stat::make('Terlambat',
                Peminjaman::where('status', 'terlambat')->count()
            )
                ->description('Belum dikembalikan')
                ->icon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}

// Register di AdminPanelProvider:
// ->widgets([StatsOverview::class])


// ==================== KategoriSeeder.php ====================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id_kategori' => 'KAT001', 'nama_kategori' => 'Fiksi'],
            ['id_kategori' => 'KAT002', 'nama_kategori' => 'Non-Fiksi'],
            ['id_kategori' => 'KAT003', 'nama_kategori' => 'Sains & Teknologi'],
            ['id_kategori' => 'KAT004', 'nama_kategori' => 'Sejarah'],
            ['id_kategori' => 'KAT005', 'nama_kategori' => 'Pendidikan'],
        ];

        foreach ($data as $item) {
            Kategori::firstOrCreate(['id_kategori' => $item['id_kategori']], $item);
        }
    }
}


// ==================== PenulisSeeder.php ====================
class PenulisSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['id_penulis' => 'PEN001', 'nama_penulis' => 'Andrea Hirata'],
            ['id_penulis' => 'PEN002', 'nama_penulis' => 'Pramoedya Ananta Toer'],
            ['id_penulis' => 'PEN003', 'nama_penulis' => 'Raditya Dika'],
            ['id_penulis' => 'PEN004', 'nama_penulis' => 'Tere Liye'],
            ['id_penulis' => 'PEN005', 'nama_penulis' => 'Habiburrahman El Shirazy'],
        ];

        foreach ($data as $item) {
            \App\Models\Penulis::firstOrCreate(['id_penulis' => $item['id_penulis']], $item);
        }
    }
}


// ==================== BukuSeeder.php ====================
class BukuSeeder extends Seeder
{
    public function run(): void
    {
        $buku = [
            [
                'id_buku' => 'BKU001',
                'judul_buku' => 'Laskar Pelangi',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'stok' => 5,
                'id_kategori' => 'KAT001',
                'penulis' => ['PEN001'],
            ],
            [
                'id_buku' => 'BKU002',
                'judul_buku' => 'Bumi Manusia',
                'penerbit' => 'Hasta Mitra',
                'tahun_terbit' => 1980,
                'stok' => 3,
                'id_kategori' => 'KAT004',
                'penulis' => ['PEN002'],
            ],
            [
                'id_buku' => 'BKU003',
                'judul_buku' => 'Ayat-Ayat Cinta',
                'penerbit' => 'Republika',
                'tahun_terbit' => 2004,
                'stok' => 7,
                'id_kategori' => 'KAT001',
                'penulis' => ['PEN005'],
            ],
        ];

        foreach ($buku as $item) {
            $penulis = $item['penulis'];
            unset($item['penulis']);

            $b = \App\Models\Buku::firstOrCreate(['id_buku' => $item['id_buku']], $item);
            $b->penulis()->sync($penulis);
        }
    }
}


// ==================== AnggotaSeeder.php ====================
class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'id_anggota' => 'ANG001',
                'nama_anggota' => 'Budi Santoso',
                'alamat' => 'Jl. Merdeka No. 1, Jakarta',
                'email' => 'budi@email.com',
                'no_tlp' => '081234567890',
                'tgl_daftar' => '2024-01-10',
            ],
            [
                'id_anggota' => 'ANG002',
                'nama_anggota' => 'Siti Rahayu',
                'alamat' => 'Jl. Sudirman No. 5, Bandung',
                'email' => 'siti@email.com',
                'no_tlp' => '089876543210',
                'tgl_daftar' => '2024-02-15',
            ],
        ];

        foreach ($data as $item) {
            \App\Models\Anggota::firstOrCreate(['id_anggota' => $item['id_anggota']], $item);
        }
    }
}
