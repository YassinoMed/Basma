<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class SystemController extends Controller
{
    public function index()
    {
        $dbOk = true;
        $dbError = null;

        try {
            DB::connection()->select('select 1');
        } catch (Throwable $e) {
            $dbOk = false;
            $dbError = $e->getMessage();
        }

        $storageWritable = is_writable(storage_path());
        $logsWritable = is_writable(storage_path('logs'));

        return view('admin.system', [
            'system' => [
                'app_name' => (string) config('app.name'),
                'app_env' => (string) config('app.env'),
                'app_debug' => (bool) config('app.debug'),
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'database_connection' => (string) config('database.default'),
                'cache_driver' => (string) config('cache.default'),
                'queue_connection' => (string) config('queue.default'),
                'session_driver' => (string) config('session.driver'),
                'db_ok' => $dbOk,
                'db_error' => $dbError,
                'storage_writable' => $storageWritable,
                'logs_writable' => $logsWritable,
            ],
        ]);
    }

    public function action(Request $request)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:optimize_clear,theme_cache_clear,cache_clear,config_clear,route_clear,view_clear'],
        ]);

        $action = $validated['action'];

        try {
            if ($action === 'theme_cache_clear') {
                Cache::forget('theme_settings_inline_css');
                Cache::forget('theme_icon_setting');

                return back()->with('success', 'Cache du thème vidé.');
            }

            $command = match ($action) {
                'optimize_clear' => 'optimize:clear',
                'cache_clear' => 'cache:clear',
                'config_clear' => 'config:clear',
                'route_clear' => 'route:clear',
                'view_clear' => 'view:clear',
            };

            Artisan::call($command);

            return back()->with('success', 'Action exécutée : '.$command);
        } catch (Throwable $e) {
            return back()->with('error', 'Erreur: '.$e->getMessage());
        }
    }
}
