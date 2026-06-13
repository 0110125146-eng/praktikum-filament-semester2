<?php
// ============================================================
// SEMUA MODEL - Taruh di app/Models/
// ============================================================

// ==================== Anggota.php ====================
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_anggota', 'nama_anggota', 'alamat',
        'email', 'no_tlp', 'tgl_daftar',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_anggota', 'id_anggota');
    }
}


// ==================== Petugas.php ====================
class Petugas extends Model
{
    protected $table = 'petugas';
    protected $primaryKey = 'id_petugas';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $hidden = ['password'];

    protected $fillable = [
        'id_petugas', 'nama_petugas', 'email', 'password', 'no_tlp',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_petugas', 'id_petugas');
    }
}


// ==================== Kategori.php ====================
class Kategori extends Model
{
    protected $table = 'kategori';
    protected $primaryKey = 'id_kategori';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_kategori', 'nama_kategori'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_kategori', 'id_kategori');
    }
}


// ==================== Penulis.php ====================
class Penulis extends Model
{
    protected $table = 'penulis';
    protected $primaryKey = 'id_penulis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id_penulis', 'nama_penulis'];

    public function buku()
    {
        return $this->belongsToMany(
            Buku::class,
            'buku_has_penulis',
            'penulis_id_penulis',
            'buku_id_buku'
        );
    }
}


// ==================== Buku.php ====================
class Buku extends Model
{
    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_buku', 'judul_buku', 'penerbit',
        'tahun_terbit', 'stok', 'id_kategori', 'cover',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function penulis()
    {
        return $this->belongsToMany(
            Penulis::class,
            'buku_has_penulis',
            'buku_id_buku',
            'penulis_id_penulis'
        );
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_buku', 'id_buku');
    }
}


// ==================== Peminjaman.php ====================
class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_peminjaman', 'id_anggota', 'id_petugas',
        'tgl_pinjam', 'tgl_kembali', 'status',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'id_anggota', 'id_anggota');
    }

    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id_petugas');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }
}


// ==================== DetailPeminjaman.php ====================
class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';

    protected $fillable = ['id_peminjaman', 'id_buku', 'jumlah'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}
