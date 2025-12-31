<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    // Statically cache modules to avoid multiple DB queries per request cycle
    protected static $modules;

    /**
     * Load all modules and cache them statically.
     */
    protected static function loadModules()
    {
        if (is_null(self::$modules)) {
            self::$modules = Cache::remember('all_modules', now()->addHour(), function () {
                return self::all()->keyBy('key');
            });
        }
    }

    /**
     * Check if a module is active by its key.
     *
     * @param string $key
     * @return bool
     */
    public static function isActive(string $key): bool
    {
        self::loadModules();
        return self::$modules->get($key)?->is_active ?? false;
    }

    /**
     * Get all settings for a module or a specific setting by key.
     *
     * @param string $moduleKey
     * @param string|null $settingKey
     * @param mixed|null $default
     * @return mixed
     */
    public static function getSettings(string $moduleKey, string $settingKey = null, $default = null)
    {
        self::loadModules();
        $module = self::$modules->get($moduleKey);

        if (!$module || !$module->is_active) {
            return $default;
        }

        if (is_null($settingKey)) {
            return $module->settings ?? $default;
        }

        return data_get($module->settings, $settingKey, $default);
    }
}
