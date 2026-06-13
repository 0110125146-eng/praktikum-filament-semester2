<?php
// ============================================================
// RBAC SETUP - Spatie Permission
// ============================================================

// ==================== 1. User Model (app/Models/User.php) ====================
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    // Kontrol akses panel Filament berdasarkan role
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'petugas']);
    }
}


// ==================== 2. AdminPanelProvider (app/Providers/Filament/AdminPanelProvider.php) ====================
// Tambahkan middleware dan authGuard
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;

// Dalam method panel():
$panel
    ->id('admin')
    ->path('admin')
    ->login()
    ->colors(['primary' => Color::Amber])
    ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
    ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
    ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
    ->middleware([
        EncryptCookies::class,
        StartSession::class,
        DisableBladeIconComponents::class,
        DispatchServingFilamentEvent::class,
    ])
    ->authMiddleware([Authenticate::class]);


// ==================== 3. RoleSeeder (database/seeders/RolePermissionSeeder.php) ====================
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache roles & permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat permissions
        $permissions = [
            // Buku
            'view buku', 'create buku', 'edit buku', 'delete buku',
            // Anggota
            'view anggota', 'create anggota', 'edit anggota', 'delete anggota',
            // Peminjaman
            'view peminjaman', 'create peminjaman', 'edit peminjaman', 'delete peminjaman',
            // Kategori & Penulis
            'view kategori', 'create kategori', 'edit kategori', 'delete kategori',
            'view penulis', 'create penulis', 'edit penulis', 'delete penulis',
            // User Management (admin only)
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Role ADMIN - akses penuh
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Role PETUGAS - akses terbatas (tidak bisa manage users, delete master data)
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $petugas->givePermissionTo([
            'view buku', 'create buku', 'edit buku',
            'view anggota', 'create anggota', 'edit anggota',
            'view peminjaman', 'create peminjaman', 'edit peminjaman',
            'view kategori', 'view penulis',
        ]);

        // Buat user Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@perpus.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
            ]
        );
        $adminUser->assignRole('admin');

        // Buat user Petugas
        $petugasUser = User::firstOrCreate(
            ['email' => 'petugas@perpus.com'],
            [
                'name' => 'Petugas Perpustakaan',
                'password' => bcrypt('password'),
            ]
        );
        $petugasUser->assignRole('petugas');

        $this->command->info('Roles, permissions, dan user default berhasil dibuat!');
        $this->command->info('Admin: admin@perpus.com / password');
        $this->command->info('Petugas: petugas@perpus.com / password');
    }
}


// ==================== 4. DatabaseSeeder.php ====================
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            KategoriSeeder::class,
            PenulisSeeder::class,
            BukuSeeder::class,
            AnggotaSeeder::class,
        ]);
    }
}


// ==================== 5. Middleware RBAC di Resource ====================
// Tambahkan di setiap Resource untuk pembatasan akses berdasarkan role:

// Contoh di BukuResource.php:
public static function canCreate(): bool
{
    return auth()->user()?->can('create buku');
}

public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
{
    return auth()->user()?->can('edit buku');
}

public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
{
    return auth()->user()?->can('delete buku');
}

public static function canViewAny(): bool
{
    return auth()->user()?->can('view buku');
}


// ==================== 6. UserResource.php (Admin only) ====================
// Untuk manage user & assign role - hanya bisa diakses admin
class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Pengaturan';

    public static function canViewAny(): bool
    {
        return auth()->user()?->can('manage users');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context) => $context === 'create'),
            Forms\Components\Select::make('roles')
                ->multiple()
                ->relationship('roles', 'name')
                ->preload(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('roles.name')->badge()->label('Role'),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
