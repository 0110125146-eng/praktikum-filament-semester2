<?php
// ============================================================
// SEMUA MIGRATION - Buat file terpisah di database/migrations/
// ============================================================

// ---- 1. create_anggota_table.php ----
Schema::create('anggota', function (Blueprint $table) {
    $table->string('id_anggota', 10)->primary();
    $table->string('nama_anggota', 100);
    $table->text('alamat')->nullable();
    $table->string('email', 100)->unique();
    $table->string('no_tlp', 15)->nullable();
    $table->date('tgl_daftar');
    $table->timestamps();
});

// ---- 2. create_petugas_table.php ----
Schema::create('petugas', function (Blueprint $table) {
    $table->string('id_petugas', 10)->primary();
    $table->string('nama_petugas', 100);
    $table->string('email', 45)->unique();
    $table->string('password', 100);
    $table->string('no_tlp', 15)->nullable();
    $table->timestamps();
});

// ---- 3. create_kategori_table.php ----
Schema::create('kategori', function (Blueprint $table) {
    $table->string('id_kategori', 50)->primary();
    $table->string('nama_kategori', 100);
    $table->timestamps();
});

// ---- 4. create_penulis_table.php ----
Schema::create('penulis', function (Blueprint $table) {
    $table->string('id_penulis', 10)->primary();
    $table->string('nama_penulis', 100);
    $table->timestamps();
});

// ---- 5. create_buku_table.php ----
Schema::create('buku', function (Blueprint $table) {
    $table->string('id_buku', 10)->primary();
    $table->string('judul_buku', 100);
    $table->string('penerbit', 50)->nullable();
    $table->year('tahun_terbit')->nullable();
    $table->integer('stok')->default(0);
    $table->string('id_kategori', 50)->nullable();
    $table->string('cover', 255)->nullable(); // untuk upload gambar
    $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->nullOnDelete();
    $table->timestamps();
});

// ---- 6. create_buku_has_penulis_table.php ----
Schema::create('buku_has_penulis', function (Blueprint $table) {
    $table->string('buku_id_buku', 10);
    $table->string('penulis_id_penulis', 10);
    $table->primary(['buku_id_buku', 'penulis_id_penulis']);
    $table->foreign('buku_id_buku')->references('id_buku')->on('buku')->cascadeOnDelete();
    $table->foreign('penulis_id_penulis')->references('id_penulis')->on('penulis')->cascadeOnDelete();
});

// ---- 7. create_peminjaman_table.php ----
Schema::create('peminjaman', function (Blueprint $table) {
    $table->string('id_peminjaman', 15)->primary();
    $table->string('id_anggota', 10);
    $table->string('id_petugas', 10);
    $table->date('tgl_pinjam');
    $table->date('tgl_kembali');
    $table->string('status', 20)->default('dipinjam'); // dipinjam, dikembalikan
    $table->foreign('id_anggota')->references('id_anggota')->on('anggota')->cascadeOnDelete();
    $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->cascadeOnDelete();
    $table->timestamps();
});

// ---- 8. create_detail_peminjaman_table.php ----
Schema::create('detail_peminjaman', function (Blueprint $table) {
    $table->id();
    $table->string('id_peminjaman', 15);
    $table->string('id_buku', 10);
    $table->integer('jumlah')->default(1);
    $table->foreign('id_peminjaman')->references('id_peminjaman')->on('peminjaman')->cascadeOnDelete();
    $table->foreign('id_buku')->references('id_buku')->on('buku')->cascadeOnDelete();
    $table->timestamps();
});
