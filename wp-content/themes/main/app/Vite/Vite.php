<?php
namespace App\Vite;

class Vite {

    public static function isDev(): bool {
        return defined('THEME_ENV') && THEME_ENV === 'dev';
    }

    public static function devServer(): string {
        return 'http://localhost:5173';
    }

    public static function base(): string {
        return self::isDev()
            ? self::devServer() . '/'
            : get_template_directory_uri() . '/dist/';
    }

    public static function manifestPath(): string {
        return get_template_directory() . '/dist/.vite/manifest.json';
    }

    public static function manifest(): array {

        if (self::isDev()) {
            return [];
        }

        $path = self::manifestPath();

        if (!file_exists($path)) {
            throw new Exception('Vite manifest not found. Run build first.');
        }

        $json = file_get_contents($path);
        $manifest = json_decode($json, true);

        if (!is_array($manifest)) {
            throw new Exception('Invalid Vite manifest JSON');
        }

        return $manifest;
    }

    public static function asset(string $entry): string {

        if (self::isDev()) {
            return self::devServer() . '/' . $entry;
        }

        $manifest = self::manifest();

        if (!isset($manifest[$entry])) {
            throw new Exception("Vite entry not found: {$entry}");
        }

        return get_template_directory_uri()
            . '/dist/'
            . $manifest[$entry]['file'];
    }

    public static function css(string $entry): array {

        if (self::isDev()) {
            return [];
        }

        $manifest = self::manifest();

        return $manifest[$entry]['css'] ?? [];
    }
}