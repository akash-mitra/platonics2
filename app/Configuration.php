<?php

namespace App;

use DB;
use Illuminate\Support\Facades\Cache;

class Configuration
{
    /**
     * Sets the configurations values against the given config key.
     */
    public static function setConfig($key, $value)
    {
        $keys = explode('.', $key);
        $existingValue = Cache::get($keys[0]);

        if ($existingValue) {
            self::delConfig($keys[0]);
        } else {
            $existingValue = [];
        }

        $val = self::getSubConfig($key, $value, $existingValue);

        DB::table('configurations')
                    ->insert([
                        'key' => $keys[0],
                        'value' => serialize(json_encode($val)),
                        'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                    ]);
        Cache::forever($keys[0], $val);

        return ['status' => 'success'];
    }

    public static function getSubConfig($key, $value, $existingValue)
    {
        $keys = explode('.', $key);
        $key = $keys[0];
        array_shift($keys);
        $subKey = implode('.', $keys);

        if ($subKey === '') {  // this means key is z, so no more subkey
            // if (array_key_exists($key, $existingValue))
            $existingValue[$key] = $value;
        // else
            //     array_push($existingValue, [$key => $value]);
        } else { // this means key is y, z is subkey
            if (array_key_exists($key, $existingValue)) {
                $subValue = $existingValue[$key];
            } else {
                $subValue = [$key => $value];
            }

            $existingValue[$key] = self::getSubConfig($subKey, $value, $subValue);
        }

        return $existingValue;
    }

    /**
     * Gets the configurations values against the given config key.
     */
    public static function getConfig($key)
    {
        // if (self::keyExists($key)) {
        return Cache::rememberForever($key, function () use ($key) {
            $configuration = DB::table('configurations')->where('key', $key)->first();

            if (empty($configuration)) {
                abort(404);
            }

            $unserializedValue = json_decode(unserialize($configuration->value), true);

            return $unserializedValue;
        });
        // }

        return 'Not Found';
    }

    /**
     * Gets all the configurations values.
     */
    public static function getConfigs()
    {
        $configurations = DB::table('configurations')->get();
        foreach ($configurations as $configuration) {
            $configuration->value = json_decode(unserialize($configuration->value), true);
        }

        return $configurations;
    }

    /**
     * Deletes the configurations values against the given config key.
     */
    public static function delConfig($key)
    {
        if (Cache::get($key)) {
            Cache::forget($key);

            return DB::table('configurations')->where('key', $key)->delete();
        }

        return 0;
    }

    /**
     * Checks if the given config key exists.
     */
    // public static function keyExists($key)
    // {
    //     return DB::table('configurations')->where('key', $key)->exists();
    // }
}
