<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Display the System Settings & Server Diagnostics Dashboard
     */
    public function index(Request $request)
    {
        $settings = SystemSetting::all()->pluck('value', 'key')->toArray();

        // System Diagnostics & Environment Info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'app_env' => config('app.env', 'production'),
            'app_debug' => config('app.debug') ? 'Aktif (Debug Mode)' : 'Nonaktif (Production Safe)',
            'app_timezone' => config('app.timezone', 'Asia/Makassar') . ' (WITA)',
            'database_driver' => config('database.default', 'sqlite'),
            'database_name' => config('database.connections.' . config('database.default') . '.database', 'database.sqlite'),
            'cache_driver' => config('cache.default', 'file'),
            'session_driver' => config('session.driver', 'file'),
            'server_os' => PHP_OS_FAMILY,
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
        ];

        // Stats summary
        $stats = [
            'total_settings' => count($settings),
            'last_updated' => AuditLog::where('action', 'settings_updated')->latest('created_at')->first()?->created_at?->diffForHumans() ?? 'Belum pernah diubah',
            'audit_logs_count' => AuditLog::count(),
        ];

        return view('admin.system.settings', compact('settings', 'systemInfo', 'stats'));
    }

    /**
     * Update platform settings with validation and detailed audit logging
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // General Platform Info
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            
            // Official Contact & Hotline
            'contact_email' => ['required', 'email'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['required', 'string', 'max:50'],
            'office_address' => ['required', 'string', 'max:500'],
            'office_hours' => ['nullable', 'string', 'max:100'],

            // Studio & Redaksi SCM
            'studio_partner_name' => ['nullable', 'string', 'max:150'],
            'studio_reading_wpm' => ['nullable', 'integer', 'min:80', 'max:250'],
            'studio_ticker_announcement' => ['nullable', 'string', 'max:300'],

            // Policies & Limits
            'auto_resolve_days' => ['nullable', 'integer', 'min:1', 'max:90'],
            'max_upload_size_mb' => ['nullable', 'integer', 'min:1', 'max:50'],
            'allow_public_ugc' => ['nullable', 'string', 'in:1,0'],
            'require_nik_verification' => ['nullable', 'string', 'in:1,0'],
        ]);

        $changedKeys = [];

        foreach ($validated as $key => $value) {
            $currentVal = SystemSetting::get($key);
            if ($currentVal != $value) {
                $changedKeys[] = $key;
            }
            SystemSetting::set($key, (string) ($value ?? ''));
        }

        // Record Audit Log
        AuditLog::record('settings_updated', 'SystemSetting', null, [
            'updated_keys' => $changedKeys,
            'total_changed' => count($changedKeys),
        ]);

        return back()->with('success', 'Konfigurasi parameter operasional platform berhasil diperbarui.');
    }

    /**
     * Clear application cache, views, and routes
     */
    public function clearCache(Request $request)
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            Artisan::call('config:clear');

            AuditLog::record('cache_cleared', 'System', null, [
                'action_by' => auth()->user()?->name ?? 'Admin',
            ]);

            return back()->with('success', 'Seluruh berkas cache, views, dan konfigurasi sistem berhasil dibersihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membersihkan cache: ' . $e->getMessage());
        }
    }
}
