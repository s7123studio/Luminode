<?php

namespace Luminode\Core;

class Config
{
    protected array $items = [];

    public function __construct(string $configPath)
    {
        $this->loadConfigurationFiles($configPath);
    }

    protected function loadConfigurationFiles(string $path): void
    {
        foreach (glob($path . '/*.php') as $file) {
            $key = basename($file, '.php');
            $this->items[$key] = require $file;
        }
    }

    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $array = $this->items;

        foreach ($keys as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }

    public function all(): array
    {
        return $this->items;
    }
}
