<?php

declare(strict_types=1);

namespace App\Config;

use App\Config\Loaders\LoaderInterface;
use function array_key_exists;
use function array_merge;
use function explode;
use function is_array;

class Config
{
    private $config = [];
    private $cache = [];

    public function load(array $loaders): Config
    {
        foreach ($loaders as $loader) {
            if (!$loader instanceof LoaderInterface) {
                continue;
            }

            $this->config = $this->mergeConfig($loader);
        }

        return $this;
    }

    public function get(string $key, string $default = null)
    {
        if ($this->existsInCache($key)) {
            return $this->fromCache($key);
        }

        $extracted = $this->extractFromConfig($key) ?? $default;

        return $this->addToCache($key, $extracted);
    }

    private function extractFromConfig(string $key)
    {
        $filtered = $this->config;

        foreach (explode('.', $key) as $segment) {
            if (is_array($filtered) && $this->exists($filtered, $segment)) {
                $filtered = $filtered[$segment];
                continue;
            }

            return null;
        }

        return $filtered;
    }

    private function addToCache(string $key, $value)
    {
        $this->cache[$key] = $value;

        return $value;
    }

    private function existsInCache(string $key): bool
    {
        return isset($this->cache[$key]);
    }

    private function fromCache(string $key)
    {
        return $this->cache[$key];
    }

    private function exists(array $config, string $key): bool
    {
        return array_key_exists($key, $config);
    }

    private function mergeConfig(LoaderInterface $loader): array
    {
        return array_merge($this->config, $loader->parse());
    }
}
