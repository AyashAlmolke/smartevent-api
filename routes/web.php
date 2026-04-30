<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BehaviorController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\QuickInvitationController;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// 🏠 Home & Redirects
// =========================
Route::get('/', function () {
    return view('welcome');
});

// ✅ إعادة توجيه /dashboard إلى /admin/dashboard
Route::get('/dashboard', function () {
    return redirect('/admin/dashboard');
});

// =========================
// 📊 API Stats
// =========================
Route::get('/api/stats', function () {
    return response()->json([
        'users' => \App\Models\User::count(),
        'events' => \App\Models\Event::count(),
        'invitations' => \App\Models\Invitation::count(),
        'attendances' => \App\Models\Attendance::count(),
    ]);
});

// =========================
// 🧪 QR Test
// =========================
Route::get('/qr-test', function () {
    return QrCode::size(300)->generate('Smart Event System');
});

// =========================
// 🔐 Admin Auth Routes
// =========================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
    
    Route::middleware('admin')->group(function () {
        // 📊 Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // 🎉 Events Management
        Route::resource('/events', EventController::class);
        
        // 👤 Users Management
        Route::resource('/users', UserController::class);
        Route::post('/users/{user}/blacklist', [UserController::class, 'toggleBlacklist'])->name('users.blacklist');
        
        // ============================================================
        // 🎫 IMPORTANT: Quick Invitation MUST come BEFORE resource route
        // ============================================================
        
        // 🎫 Quick Invitation (إنشاء سريع) ← يجب أن تأتي أولاً
        Route::get('/invitations/quick', [QuickInvitationController::class, 'create'])->name('invitations.quick');
        Route::post('/invitations/quick', [QuickInvitationController::class, 'store'])->name('invitations.quick.store');
        
        // 🎫 Invitations Management (مع دعم الصيغ المتعددة)
        Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index');
        Route::get('/invitations/create', [InvitationController::class, 'create'])->name('invitations.create');
        Route::post('/invitations', [InvitationController::class, 'store'])->name('invitations.store');
        Route::get('/invitations/{invitation}', [InvitationController::class, 'show'])->name('invitations.show');
        Route::get('/invitations/{invitation}/edit', [InvitationController::class, 'edit'])->name('invitations.edit');
        Route::put('/invitations/{invitation}', [InvitationController::class, 'update'])->name('invitations.update');
        Route::delete('/invitations/{invitation}', [InvitationController::class, 'destroy'])->name('invitations.destroy');
        Route::get('/invitations/{invitation}/download-qr/{format?}', [InvitationController::class, 'downloadQr'])->name('invitations.download-qr');
        
        // 📡 Logs
        Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
        Route::get('/logs/{log}', [LogController::class, 'show'])->name('logs.show');
        
        // 🤖 Behavior & Fraud Detection
        Route::get('/behavior', [BehaviorController::class, 'index'])->name('behavior.index');
        Route::get('/fraud', [BehaviorController::class, 'fraudDetection'])->name('fraud.index');
    });
});

// =========================
// 🔐 Authentication Routes (Laravel UI)
// =========================
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
