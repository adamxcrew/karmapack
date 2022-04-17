<?php
// utility
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

// model
use App\Models\User;

// controller
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LoginController;

// Address
use App\Http\Controllers\Admin\Address\ProvinceController;
use App\Http\Controllers\Admin\Address\RegencieController;
use App\Http\Controllers\Admin\Address\DistrictController;
use App\Http\Controllers\Admin\Address\VillageController;

// artikel
use App\Http\Controllers\Admin\Artikel\ArtikelController;
use App\Http\Controllers\Admin\Artikel\KategoriController;
use App\Http\Controllers\Admin\Artikel\TagController;

// pengurus
use App\Http\Controllers\Admin\Pengurus\PeriodeController;
use App\Http\Controllers\Admin\Pengurus\JabatanController;
use App\Http\Controllers\Admin\Pengurus\JabatanMemberController;

// Galeri
use App\Http\Controllers\Admin\GaleriController;

// ====================================================================================================================
// ====================================================================================================================

// home default
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }
    return view('auth.login');
});

// auth
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'check_login'])->name('login.check_login');
Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

// home
Route::get('/home', function () {
    $user = Auth::user();
    $role = isset($user->role) ? $user->role : null;
    switch ($role) {
        case User::ROLE_ADMIN:
            return Redirect::route('admin.dashboard');
            break;

        case User::ROLE_MEMBER:
            return Redirect::route('member.dashboard');
            break;

        default:
            return view('auth.login');
            break;
    }
})->name('dashboard');

// Admin route
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'verified', 'admin']], function () {
    Route::get('/', function () {
        return view('admin.dashboard', ['page_attr' => ['title' => 'Dashboard']]);
    })->name('admin.dashboard');

    // user
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.user');
        Route::post('/', [UserController::class, 'store'])->name('admin.user.store');
        Route::delete('/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
        Route::post('/update', [UserController::class, 'update'])->name('admin.user.update');
    });

    // address
    Route::group(['prefix' => 'address'], function () {

        // Province
        Route::group(['prefix' => 'province'], function () {
            Route::get('/', [ProvinceController::class, 'index'])->name('admin.address.province');
            Route::get('/select2', [ProvinceController::class, 'select2'])->name('admin.address.province.select2');
            Route::post('/', [ProvinceController::class, 'store'])->name('admin.address.province.store');
            Route::delete('/{id}', [ProvinceController::class, 'delete'])->name('admin.address.province.delete');
            Route::post('/update', [ProvinceController::class, 'update'])->name('admin.address.province.update');
        });

        // Regencie
        Route::group(['prefix' => 'regencie'], function () {
            Route::get('/', [RegencieController::class, 'index'])->name('admin.address.regencie');
            Route::get('/select2', [RegencieController::class, 'select2'])->name('admin.address.regencie.select2');
            Route::post('/', [RegencieController::class, 'store'])->name('admin.address.regencie.store');
            Route::delete('/{id}', [RegencieController::class, 'delete'])->name('admin.address.regencie.delete');
            Route::post('/update', [RegencieController::class, 'update'])->name('admin.address.regencie.update');
        });

        // District
        Route::group(['prefix' => 'district'], function () {
            Route::get('/', [DistrictController::class, 'index'])->name('admin.address.district');
            Route::get('/select2', [DistrictController::class, 'select2'])->name('admin.address.district.select2');
            Route::post('/', [DistrictController::class, 'store'])->name('admin.address.district.store');
            Route::delete('/{id}', [DistrictController::class, 'delete'])->name('admin.address.district.delete');
            Route::post('/update', [DistrictController::class, 'update'])->name('admin.address.district.update');
        });

        // Village
        Route::group(['prefix' => 'village'], function () {
            Route::get('/', [VillageController::class, 'index'])->name('admin.address.village');
            Route::get('/select2', [VillageController::class, 'select2'])->name('admin.address.village.select2');
            Route::post('/', [VillageController::class, 'store'])->name('admin.address.village.store');
            Route::delete('/{id}', [VillageController::class, 'delete'])->name('admin.address.village.delete');
            Route::post('/update', [VillageController::class, 'update'])->name('admin.address.village.update');
        });
    });

    // Artikel
    Route::group(['prefix' => 'artikel'], function () {

        // Data
        Route::group(['prefix' => 'data'], function () {
            Route::get('/', [ArtikelController::class, 'index'])->name('admin.artikel.data'); // page
            Route::get('/add', [ArtikelController::class, 'add'])->name('admin.artikel.data.add'); // page
            Route::get('/edit/{artikel}', [ArtikelController::class, 'edit'])->name('admin.artikel.data.edit'); // page

            Route::delete('/{artikel}', [ArtikelController::class, 'delete'])->name('admin.artikel.data.delete');
            Route::post('/insert', [ArtikelController::class, 'insert'])->name('admin.artikel.data.insert');
            Route::post('/update', [ArtikelController::class, 'update'])->name('admin.artikel.data.update');
        });

        // Kategori
        Route::group(['prefix' => 'kategori'], function () {
            Route::get('/', [KategoriController::class, 'index'])->name('admin.artikel.kategori');
            Route::get('/select2', [KategoriController::class, 'select2'])->name('admin.artikel.kategori.select2');
            Route::post('/', [KategoriController::class, 'insert'])->name('admin.artikel.kategori.insert');
            Route::delete('/{model}', [KategoriController::class, 'delete'])->name('admin.artikel.kategori.delete');
            Route::post('/update', [KategoriController::class, 'update'])->name('admin.artikel.kategori.update');
        });

        // Tag
        Route::group(['prefix' => 'tag'], function () {
            Route::get('/', [TagController::class, 'index'])->name('admin.artikel.tag');
            Route::get('/select2', [TagController::class, 'select2'])->name('admin.artikel.tag.select2');
            Route::post('/', [TagController::class, 'insert'])->name('admin.artikel.tag.insert');
            Route::delete('/{model}', [TagController::class, 'delete'])->name('admin.artikel.tag.delete');
            Route::post('/update', [TagController::class, 'update'])->name('admin.artikel.tag.update');
        });
    });

    // Pengurus
    Route::group(['prefix' => 'pengurus'], function () {

        // Data
        Route::group(['prefix' => 'periode'], function () {
            Route::get('/', [PeriodeController::class, 'index'])->name('admin.pengurus.periode'); // page
            Route::get('/add', [PeriodeController::class, 'add'])->name('admin.pengurus.periode.add'); // page
            Route::get('/edit/{model}', [PeriodeController::class, 'edit'])->name('admin.pengurus.periode.edit'); // page
            Route::get('/active/{model}', [PeriodeController::class, 'setActive'])->name('admin.pengurus.periode.active');
            Route::post('/member', [PeriodeController::class, 'member'])->name('admin.pengurus.periode.member'); // member json
            Route::post('/detail/{model}', [PeriodeController::class, 'detail'])->name('admin.pengurus.periode.detail'); // detail json

            Route::delete('/{model}', [PeriodeController::class, 'delete'])->name('admin.pengurus.periode.delete');
            Route::post('/insert', [PeriodeController::class, 'insert'])->name('admin.pengurus.periode.insert');
            Route::post('/update', [PeriodeController::class, 'update'])->name('admin.pengurus.periode.update');
        });

        // Jabatan
        Route::group(['prefix' => 'jabatan'], function () {
            // suffix
            Route::get('/get_parent', [JabatanController::class, 'parent'])->name('admin.pengurus.jabatan.parent'); // list option element
            Route::get('/select2', [JabatanController::class, 'select2'])->name('admin.pengurus.jabatan.select2'); // select2
            Route::post('/update', [JabatanController::class, 'update'])->name('admin.pengurus.jabatan.update'); // update

            // base
            Route::get('/{periode_id}', [JabatanController::class, 'index'])->name('admin.pengurus.jabatan'); // page
            Route::post('/{periode_id}', [JabatanController::class, 'insert'])->name('admin.pengurus.jabatan.insert'); // insert
            Route::delete('/{model}', [JabatanController::class, 'delete'])->name('admin.pengurus.jabatan.delete'); // delete

            // Member
            Route::group(['prefix' => 'member'], function () {
                Route::get('/select2', [JabatanMemberController::class, 'select2'])->name('admin.pengurus.jabatan.member.select2'); // select2
                Route::get('/{id}', [JabatanMemberController::class, 'index'])->name('admin.pengurus.jabatan.member'); // page
                Route::post('/update', [JabatanMemberController::class, 'update'])->name('admin.pengurus.jabatan.member.update'); // update
            });
        });
    });

    // Galeri
    Route::group(['prefix' => 'galeri'], function () {
        Route::get('/', [GaleriController::class, 'index'])->name('admin.galeri');
        Route::get('/select2', [GaleriController::class, 'select2'])->name('admin.galeri.select2');
        Route::post('/', [GaleriController::class, 'insert'])->name('admin.galeri.insert');
        Route::delete('/{model}', [GaleriController::class, 'delete'])->name('admin.galeri.delete');
        Route::post('/update', [GaleriController::class, 'update'])->name('admin.galeri.update');
    });
});


// User Panel Admin
Route::group(['prefix' => 'member', 'middleware' => ['auth:sanctum', 'verified', 'member']], function () {
    Route::get('/dashboard', function () {
        return view('member.dashborard', ['page_attr' => ['title' => 'Dashboard']]);
    })->name('member.dashboard');
});

Route::get('/tesadmin', function () {
    return view('templates.admin.index');
});
