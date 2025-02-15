<?php

// 定义一个名为 Cache 的类，用于缓存数据
class Cache {
    // 定义一个私有静态变量 $driver，用于存储当前使用的缓存驱动
    private static $driver;

    // 初始化缓存驱动
    public static function init() {
        // 检查是否加载了 'apcu' 扩展，如果加载则使用 'apcu' 驱动，否则使用 'file' 驱动
        self::$driver = extension_loaded('apcu') ? 'apcu' : 'file';
    }

    // 获取缓存数据
    public static function get($key) {
        // 如果当前驱动是 'apcu'，则使用 apcu_fetch 函数获取缓存数据
        if (self::$driver === 'apcu') {
            return apcu_fetch($key);
        } else {
            // 否则，使用文件系统获取缓存数据
            $file = self::getCacheFile($key);
            // 检查缓存文件是否存在，如果存在则读取文件内容并反序列化，否则返回 null
            return file_exists($file) ? unserialize(file_get_contents($file)) : null;
        }
    }

    // 设置缓存数据
    public static function set($key, $value, $ttl = 3600) {
        // 如果当前驱动是 'apcu'，则使用 apcu_store 函数存储缓存数据，并设置过期时间
        if (self::$driver === 'apcu') {
            apcu_store($key, $value, $ttl);
        } else {
            // 否则，使用文件系统存储缓存数据
            file_put_contents(self::getCacheFile($key), serialize($value));
        }
    }

    // 获取缓存文件的路径
    private static function getCacheFile($key) {
        // 定义缓存文件存储目录
        $dir = 'storage/cache/';
        // 返回缓存文件的完整路径，文件名使用 key 的 MD5 哈希值
        return $dir.md5($key);
    }
}