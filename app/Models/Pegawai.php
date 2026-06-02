<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'pegawais';

    // JAWABAN MASALAHNYA: Semua kolom ini wajib didaftarkan agar data bisa tersimpan!
    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'gender',
        'divisi_id',
        'jabatan_id',
        'tmp_lahir',
        'tgl_lahir',
        'hp',
        'alamat',
        'foto',
    ];

    // Relasi ke model User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Divisi
    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    // Relasi ke model Jabatan
    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class);
    }
}