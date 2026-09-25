<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CulinaryPlaceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\QuickSaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SmartCityController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\Admin\ModerationController as AdminModerationController;
use App\Http\Controllers\Admin\ReportAdminController as AdminReportController;
use App\Http\Controllers\Admin\SmartCityAdminController as AdminSmartCityController;
use App\Http\Controllers\Admin\StudioFeedController as AdminStudioFeedController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Web Routes — Habar Etam V2
|--------------------------------------------------------------------------
*/

// SEO & Search Engine Indexing
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Public Pages
Route::get('/', [HomeController::class, 'index'])->name('home');

// 1. Bursa Kerja
Route::get('/bursa-kerja', [JobVacancyController::class, 'index'])->name('jobs.index');
Route::get('/bursa-kerja/buat', [JobVacancyController::class, 'create'])->middleware(['auth', 'active'])->name('jobs.create');
Route::post('/bursa-kerja', [JobVacancyController::class, 'store'])->middleware(['auth', 'active'])->name('jobs.store');
Route::get('/bursa-kerja/{slug}', [JobVacancyController::class, 'show'])->name('jobs.show');
Route::get('/bursa-kerja/{job}/edit', [JobVacancyController::class, 'edit'])->middleware(['auth', 'active'])->name('jobs.edit');
Route::put('/bursa-kerja/{job}', [JobVacancyController::class, 'update'])->middleware(['auth', 'active'])->name('jobs.update');
Route::delete('/bursa-kerja/{job}', [JobVacancyController::class, 'destroy'])->middleware(['auth', 'active'])->name('jobs.destroy');

// 2. Produk & Jasa
Route::get('/produk-jasa', [BusinessController::class, 'index'])->name('businesses.index');
Route::get('/produk-jasa/buat', [BusinessController::class, 'create'])->middleware(['auth', 'active'])->name('businesses.create');
Route::post('/produk-jasa', [BusinessController::class, 'store'])->middleware(['auth', 'active'])->name('businesses.store');
Route::get('/produk-jasa/{slug}', [BusinessController::class, 'show'])->name('businesses.show');
Route::get('/produk-jasa/{business}/edit', [BusinessController::class, 'edit'])->middleware(['auth', 'active'])->name('businesses.edit');
Route::put('/produk-jasa/{business}', [BusinessController::class, 'update'])->middleware(['auth', 'active'])->name('businesses.update');
Route::delete('/produk-jasa/{business}', [BusinessController::class, 'destroy'])->middleware(['auth', 'active'])->name('businesses.destroy');

// 3. Kuliner Lokal
Route::get('/kuliner', [CulinaryPlaceController::class, 'index'])->name('culinary.index');
Route::get('/kuliner/buat', [CulinaryPlaceController::class, 'create'])->middleware(['auth', 'active'])->name('culinary.create');
Route::post('/kuliner', [CulinaryPlaceController::class, 'store'])->middleware(['auth', 'active'])->name('culinary.store');
Route::get('/kuliner/{slug}', [CulinaryPlaceController::class, 'show'])->name('culinary.show');
Route::get('/kuliner/{culinary}/edit', [CulinaryPlaceController::class, 'edit'])->middleware(['auth', 'active'])->name('culinary.edit');
Route::put('/kuliner/{culinary}', [CulinaryPlaceController::class, 'update'])->middleware(['auth', 'active'])->name('culinary.update');
Route::delete('/kuliner/{culinary}', [CulinaryPlaceController::class, 'destroy'])->middleware(['auth', 'active'])->name('culinary.destroy');

// 4. Event & Kegiatan
Route::get('/event', [EventController::class, 'index'])->name('events.index');
Route::get('/event/buat', [EventController::class, 'create'])->middleware(['auth', 'active'])->name('events.create');
Route::post('/event', [EventController::class, 'store'])->middleware(['auth', 'active'])->name('events.store');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/event/{event}/edit', [EventController::class, 'edit'])->middleware(['auth', 'active'])->name('events.edit');
Route::put('/event/{event}', [EventController::class, 'update'])->middleware(['auth', 'active'])->name('events.update');
Route::delete('/event/{event}', [EventController::class, 'destroy'])->middleware(['auth', 'active'])->name('events.destroy');

// 5. Klub & Komunitas
Route::get('/komunitas', [CommunityController::class, 'index'])->name('communities.index');
Route::get('/komunitas/buat', [CommunityController::class, 'create'])->middleware(['auth', 'active'])->name('communities.create');
Route::post('/komunitas', [CommunityController::class, 'store'])->middleware(['auth', 'active'])->name('communities.store');
Route::get('/komunitas/{slug}', [CommunityController::class, 'show'])->name('communities.show');
Route::get('/komunitas/{community}/edit', [CommunityController::class, 'edit'])->middleware(['auth', 'active'])->name('communities.edit');
Route::put('/komunitas/{community}', [CommunityController::class, 'update'])->middleware(['auth', 'active'])->name('communities.update');
Route::delete('/komunitas/{community}', [CommunityController::class, 'destroy'])->middleware(['auth', 'active'])->name('communities.destroy');

// 6. Jual Cepat
Route::get('/jual-cepat', [QuickSaleController::class, 'index'])->name('quick-sales.index');
Route::get('/jual-cepat/buat', [QuickSaleController::class, 'create'])->middleware(['auth', 'active'])->name('quick-sales.create');
Route::post('/jual-cepat', [QuickSaleController::class, 'store'])->middleware(['auth', 'active'])->name('quick-sales.store');
Route::get('/jual-cepat/{slug}', [QuickSaleController::class, 'show'])->name('quick-sales.show');
Route::get('/jual-cepat/{quickSale}/edit', [QuickSaleController::class, 'edit'])->middleware(['auth', 'active'])->name('quick-sales.edit');
Route::put('/jual-cepat/{quickSale}', [QuickSaleController::class, 'update'])->middleware(['auth', 'active'])->name('quick-sales.update');
Route::post('/jual-cepat/{quickSale}/mark-sold', [QuickSaleController::class, 'markSold'])->middleware(['auth', 'active'])->name('quick-sales.mark-sold');
Route::delete('/jual-cepat/{quickSale}', [QuickSaleController::class, 'destroy'])->middleware(['auth', 'active'])->name('quick-sales.destroy');

// 7. Lapor Etam (Civic Grievances)
Route::get('/lapor-etam', [ReportController::class, 'index'])->name('reports.index');
Route::get('/lapor-etam/buat', [ReportController::class, 'create'])->middleware(['auth', 'active'])->name('reports.create');
Route::post('/lapor-etam', [ReportController::class, 'store'])->middleware(['auth', 'active', 'verified.member'])->name('reports.store');
Route::get('/lapor-etam/pengaduan-saya', [ReportController::class, 'myReports'])->middleware(['auth', 'active'])->name('reports.my-reports');
Route::get('/lapor-etam/tiket/{ticketNumber}', [ReportController::class, 'show'])->name('reports.show');

// 8. Smart City Hub
Route::prefix('smart-city')->name('smart-city.')->group(function () {
    Route::get('/kontak-darurat', [SmartCityController::class, 'emergency'])->name('emergency');
    Route::get('/harga-pangan', [SmartCityController::class, 'marketPrices'])->name('market-prices');
    Route::get('/lingkungan', [SmartCityController::class, 'environment'])->name('environment');
    Route::get('/budaya', [SmartCityController::class, 'culture'])->name('culture');
    Route::get('/budaya/{slug}', [SmartCityController::class, 'cultureShow'])->name('culture.show');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::post('/keluar', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Member Profile & Submissions Portal
Route::middleware(['auth', 'active'])->prefix('member')->name('member.')->group(function () {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin CMS Control Center
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Activity
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/aktivitas', [AdminDashboardController::class, 'activity'])->name('activity');

    // Warga Management
    Route::get('/warga', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/warga/{member}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::post('/warga/{member}/status', [AdminMemberController::class, 'toggleStatus'])->name('members.status');
    Route::post('/warga/{member}/toggle-status', [AdminMemberController::class, 'toggleStatus'])->name('members.toggle-status');

    // Verifikasi Warga
    Route::get('/verifikasi', [AdminVerificationController::class, 'index'])->name('verifications.index');
    Route::get('/verifikasi/{verification}', [AdminVerificationController::class, 'show'])->name('verifications.show');
    Route::post('/verifikasi/{verification}/approve', [AdminVerificationController::class, 'approve'])->name('verifications.approve');
    Route::post('/verifikasi/{verification}/reject', [AdminVerificationController::class, 'reject'])->name('verifications.reject');

    // Moderasi Submission (Central Inbox)
    Route::get('/moderasi', [AdminModerationController::class, 'index'])->name('moderation.index');
    Route::get('/moderasi/{type}/{id}', [AdminModerationController::class, 'show'])->name('moderation.show');
    Route::post('/moderasi/{type}/{id}/approve', [AdminModerationController::class, 'approve'])->name('moderation.approve');
    Route::post('/moderasi/{type}/{id}/reject', [AdminModerationController::class, 'reject'])->name('moderation.reject');
    Route::delete('/moderasi/{type}/{id}', [AdminModerationController::class, 'destroy'])->name('moderation.destroy');

    // Lapor Etam Management
    Route::get('/lapor-etam', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/lapor-etam/{report}', [AdminReportController::class, 'show'])->name('reports.show');
    Route::post('/lapor-etam/{report}/status', [AdminReportController::class, 'updateStatus'])->name('reports.update-status');
    Route::get('/peta-pengaduan', [AdminReportController::class, 'map'])->name('reports.map');

    // Smart City Management
    Route::prefix('smart-city')->name('smart-city.')->group(function () {
        // Emergency
        Route::get('/darurat', [AdminSmartCityController::class, 'emergencyIndex'])->name('emergency.index');
        Route::get('/darurat/tambah', [AdminSmartCityController::class, 'emergencyCreate'])->name('emergency.create');
        Route::post('/darurat', [AdminSmartCityController::class, 'emergencyStore'])->name('emergency.store');
        Route::get('/darurat/{contact}/edit', [AdminSmartCityController::class, 'emergencyEdit'])->name('emergency.edit');
        Route::put('/darurat/{contact}', [AdminSmartCityController::class, 'emergencyUpdate'])->name('emergency.update');
        Route::delete('/darurat/{contact}', [AdminSmartCityController::class, 'emergencyDestroy'])->name('emergency.destroy');

        // Market Prices
        Route::get('/harga-pangan', [AdminSmartCityController::class, 'marketPricesIndex'])->name('prices.index');
        Route::get('/harga-pangan/tambah', [AdminSmartCityController::class, 'marketPricesCreate'])->name('prices.create');
        Route::post('/harga-pangan', [AdminSmartCityController::class, 'marketPricesStore'])->name('prices.store');
        Route::get('/harga-pangan/{price}/edit', [AdminSmartCityController::class, 'marketPricesEdit'])->name('prices.edit');
        Route::put('/harga-pangan/{price}', [AdminSmartCityController::class, 'marketPricesUpdate'])->name('prices.update');
        Route::delete('/harga-pangan/{price}', [AdminSmartCityController::class, 'marketPricesDestroy'])->name('prices.destroy');

        // Environment
        Route::get('/lingkungan', [AdminSmartCityController::class, 'environmentIndex'])->name('environment.index');
        Route::get('/lingkungan/tambah', [AdminSmartCityController::class, 'environmentCreate'])->name('environment.create');
        Route::post('/lingkungan', [AdminSmartCityController::class, 'environmentStore'])->name('environment.store');
        Route::get('/lingkungan/{point}/edit', [AdminSmartCityController::class, 'environmentEdit'])->name('environment.edit');
        Route::put('/lingkungan/{point}', [AdminSmartCityController::class, 'environmentUpdate'])->name('environment.update');
        Route::delete('/lingkungan/{point}', [AdminSmartCityController::class, 'environmentDestroy'])->name('environment.destroy');

        // Culture & Heritage
        Route::get('/budaya', [AdminSmartCityController::class, 'cultureIndex'])->name('culture.index');
        Route::get('/budaya/tambah', [AdminSmartCityController::class, 'cultureCreate'])->name('culture.create');
        Route::post('/budaya', [AdminSmartCityController::class, 'cultureStore'])->name('culture.store');
        Route::get('/budaya/{destination}/edit', [AdminSmartCityController::class, 'cultureEdit'])->name('culture.edit');
        Route::put('/budaya/{destination}', [AdminSmartCityController::class, 'cultureUpdate'])->name('culture.update');
        Route::delete('/budaya/{destination}', [AdminSmartCityController::class, 'cultureDestroy'])->name('culture.destroy');
    });

    // Redaksi Studio PT SCM
    Route::get('/redaksi/feed', [AdminStudioFeedController::class, 'index'])->name('studio.feed');
    Route::get('/redaksi/teleprompter', [AdminStudioFeedController::class, 'teleprompter'])->name('studio.teleprompter');
    Route::post('/redaksi/feed/{report}/toggle-live', [AdminStudioFeedController::class, 'toggleLiveAgenda'])->name('studio.toggle-live');
    Route::post('/redaksi/feed/{report}/update-script', [AdminStudioFeedController::class, 'updateScript'])->name('studio.update-script');

    // Export Data
    Route::get('/export', [AdminExportController::class, 'index'])->name('export.index');
    Route::get('/export/preview/{type}', [AdminExportController::class, 'preview'])->name('export.preview');
    Route::get('/export/{type}', [AdminExportController::class, 'download'])->name('export.download');

    // System: Audit Log & Settings
    Route::get('/sistem/audit-log', [AdminAuditLogController::class, 'index'])->name('audit.index');
    Route::get('/sistem/audit-logs', [AdminAuditLogController::class, 'index'])->name('system.audit-logs');
    Route::get('/sistem/pengaturan', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::get('/sistem/settings', [AdminSettingController::class, 'index'])->name('system.settings');
    Route::post('/sistem/pengaturan', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/sistem/settings', [AdminSettingController::class, 'update'])->name('system.settings.update');
    Route::post('/sistem/cache/clear', [AdminSettingController::class, 'clearCache'])->name('settings.clear-cache');

});
