<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\ShortcodeManager;
use App\Services\Shortcodes\ProsConsShortcode;

class AppServiceProvider extends ServiceProvider {
    public function register() {}

    public function boot()
    {
        Schema::defaultStringLength(191);
        ShortcodeManager::add('pros_cons', [ProsConsShortcode::class, 'render']);
    }
}
