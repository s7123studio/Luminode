<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:20:22
 */

namespace Luminode\Core;

// 定义一个名为 Cache 的类，用于缓存数据
class Cache {
    // 定义一个私有静态变量 $driver，用于存储当前使用的缓存驱动
    private static $driver;
    private static $cachePath;

    // 初始化缓存驱动
    public static function init() {
        // 检查是否加载了 'apcu' 扩展，如果加载则使用 'apcu' 驱动，否则使用 'file' 驱动
        self::$driver = extension_loaded('apcu') ? 'apcu' : 'file';
        
        if (self::$driver === 'file') {
            self::$cachePath = APP_ROOT . '/storage/cache/';
            if (!is_dir(self::$cachePath)) {
                mkdir(self::$cachePath, 0755, true);
            }
        }
    }

    // 获取缓存数据
    public static function get($key) {
        if (self::$driver === 'apcu') {
            return apcu_fetch($key);
        } 
        
        if (!self::$cachePath) { self::init(); } // 确保已初始化

        $file = self::getCacheFile($key);
        if (file_exists($file)) {
            $content = unserialize(file_get_contents($file));
            if ($content['expires_at'] === null || $content['expires_at'] >= time()) {
                return $content['value'];
            }
            // 删除过期的缓存文件
            unlink($file);
        }
        return null;
    }

    // 设置缓存数据
    public static function set($key, $value, $ttl = 3600) {
        if (self::$driver === 'apcu') {
            apcu_store($key, $value, $ttl);
            return;
        }

        if (!self::$cachePath) { self::init(); } // 确保已初始化

        $expires_at = $ttl ? time() + $ttl : null;
        $data = [
            'value' => $value,
            'expires_at' => $expires_at,
        ];
        file_put_contents(self::getCacheFile($key), serialize($data), LOCK_EX);
    }

    // 获取缓存文件的路径
    private static function getCacheFile($key) {
        return self::$cachePath . md5($key);
    }
}
