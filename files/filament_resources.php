<?php
// ============================================================
// FILAMENT RESOURCES - Taruh di app/Filament/Resources/
// Generate dengan: php artisan make:filament-resource NamaModel
// ============================================================

// ==================== KategoriResource.php ====================
namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id_kategori')
                ->label('ID Kategori')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('nama_kategori')
                ->label('Nama Kategori')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_kategori')->label('ID'),
                Tables\Columns\TextColumn::make('nama_kategori')->label('Nama Kategori')->searchable(),
                Tables\Columns\TextColumn::make('buku_count')->label('Jumlah Buku')
                    ->counts('buku'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategori::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }
}


// ==================== PenulisResource.php ====================
class PenulisResource extends Resource
{
    protected static ?string $model = \App\Models\Penulis::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id_penulis')
                ->label('ID Penulis')
                ->required()
                ->maxLength(10)
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('nama_penulis')
                ->label('Nama Penulis')
                ->required()
                ->maxLength(100),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_penulis')->label('ID'),
                Tables\Columns\TextColumn::make('nama_penulis')->label('Nama')->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenulis::route('/'),
            'create' => Pages\CreatePenulis::route('/create'),
            'edit' => Pages\EditPenulis::route('/{record}/edit'),
        ];
    }
}


// ==================== BukuResource.php ====================
class BukuResource extends Resource
{
    protected static ?string $model = \App\Models\Buku::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Buku')->schema([
                Forms\Components\TextInput::make('id_buku')
                    ->label('ID Buku')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('judul_buku')
                    ->label('Judul Buku')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('penerbit')
                    ->label('Penerbit')
                    ->maxLength(50),
                Forms\Components\TextInput::make('tahun_terbit')
                    ->label('Tahun Terbit')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(date('Y')),
                Forms\Components\TextInput::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Forms\Components\Select::make('id_kategori')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori')
                    ->searchable()
                    ->preload(),
            ])->columns(2),

            Forms\Components\Section::make('Penulis')->schema([
                Forms\Components\Select::make('penulis')
                    ->label('Penulis')
                    ->relationship('penulis', 'nama_penulis')
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ]),

            Forms\Components\Section::make('Cover Buku')->schema([
                Forms\Components\FileUpload::make('cover')
                    ->label('Cover Buku')
                    ->image()
                    ->directory('covers')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('3:4')
                    ->maxSize(2048),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')->label('Cover')->square(),
                Tables\Columns\TextColumn::make('id_buku')->label('ID'),
                Tables\Columns\TextColumn::make('judul_buku')->label('Judul')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('kategori.nama_kategori')->label('Kategori'),
                Tables\Columns\TextColumn::make('penulis.nama_penulis')
                    ->label('Penulis')
                    ->badge(),
                Tables\Columns\TextColumn::make('stok')->label('Stok')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('id_kategori')
                    ->label('Kategori')
                    ->relationship('kategori', 'nama_kategori'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBuku::route('/'),
            'create' => Pages\CreateBuku::route('/create'),
            'edit' => Pages\EditBuku::route('/{record}/edit'),
        ];
    }
}


// ==================== AnggotaResource.php ====================
class AnggotaResource extends Resource
{
    protected static ?string $model = \App\Models\Anggota::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id_anggota')
                ->label('ID Anggota')
                ->required()
                ->maxLength(10)
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('nama_anggota')
                ->label('Nama Anggota')
                ->required()
                ->maxLength(100),
            Forms\Components\Textarea::make('alamat')
                ->label('Alamat')
                ->rows(3),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),
            Forms\Components\TextInput::make('no_tlp')
                ->label('No. Telepon')
                ->tel()
                ->maxLength(15),
            Forms\Components\DatePicker::make('tgl_daftar')
                ->label('Tanggal Daftar')
                ->default(now())
                ->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_anggota')->label('ID'),
                Tables\Columns\TextColumn::make('nama_anggota')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('no_tlp')->label('No. HP'),
                Tables\Columns\TextColumn::make('tgl_daftar')->label('Tgl Daftar')->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnggota::route('/'),
            'create' => Pages\CreateAnggota::route('/create'),
            'edit' => Pages\EditAnggota::route('/{record}/edit'),
        ];
    }
}


// ==================== PeminjamanResource.php ====================
use Filament\Forms\Components\Repeater;

class PeminjamanResource extends Resource
{
    protected static ?string $model = \App\Models\Peminjaman::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Peminjaman')->schema([
                Forms\Components\TextInput::make('id_peminjaman')
                    ->label('ID Peminjaman')
                    ->required()
                    ->maxLength(15)
                    ->unique(ignoreRecord: true)
                    ->default('PJM-' . date('YmdHis')),
                Forms\Components\Select::make('id_anggota')
                    ->label('Anggota')
                    ->relationship('anggota', 'nama_anggota')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('id_petugas')
                    ->label('Petugas')
                    ->relationship('petugas', 'nama_petugas')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DatePicker::make('tgl_pinjam')
                    ->label('Tanggal Pinjam')
                    ->default(now())
                    ->required(),
                Forms\Components\DatePicker::make('tgl_kembali')
                    ->label('Tanggal Kembali')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat' => 'Terlambat',
                    ])
                    ->default('dipinjam')
                    ->required(),
            ])->columns(2),

            Forms\Components\Section::make('Detail Buku')->schema([
                Repeater::make('detailPeminjaman')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('id_buku')
                            ->label('Buku')
                            ->options(\App\Models\Buku::where('stok', '>', 0)
                                ->pluck('judul_buku', 'id_buku'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('jumlah')
                            ->label('Jumlah')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->required(),
                    ])
                    ->columns(2)
                    ->addActionLabel('Tambah Buku')
                    ->minItems(1),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_peminjaman')->label('ID')->searchable(),
                Tables\Columns\TextColumn::make('anggota.nama_anggota')->label('Anggota')->searchable(),
                Tables\Columns\TextColumn::make('petugas.nama_petugas')->label('Petugas'),
                Tables\Columns\TextColumn::make('tgl_pinjam')->label('Tgl Pinjam')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('tgl_kembali')->label('Tgl Kembali')->date('d/m/Y'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'dipinjam',
                        'success' => 'dikembalikan',
                        'danger' => 'terlambat',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'dipinjam' => 'Dipinjam',
                        'dikembalikan' => 'Dikembalikan',
                        'terlambat' => 'Terlambat',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeminjaman::route('/'),
            'create' => Pages\CreatePeminjaman::route('/create'),
            'view' => Pages\ViewPeminjaman::route('/{record}'),
            'edit' => Pages\EditPeminjaman::route('/{record}/edit'),
        ];
    }
}
