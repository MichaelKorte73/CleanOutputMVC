<?php
declare(strict_types=1);

namespace CHK\Helper;

use CHK\Core\App;
use InvalidArgumentException;

final class MediaHelper
{
    private App $app;
    private array $paths;
    private string $projectRoot;
    private string $publicRoot;
    private string $assetBasePath;

    public function __construct(App $app)
    {
        $this->app   = $app;
        $imageCfg    = $app->config('images', []);

        $this->paths        = $imageCfg['paths'] ?? [];
        $this->projectRoot  = rtrim($app->config('project_root', dirname(__DIR__, 3)), '/');
        $this->publicRoot   = rtrim($app->config('public_root', $this->projectRoot . '/public'), '/');
        $this->assetBasePath = rtrim((string)($imageCfg['base_path'] ?? ''), '/');
    }

    /**
     * unprocessed | public
     */
    public function getMedia(string $key): array
    {
        if (!isset($this->paths[$key])) {
            throw new InvalidArgumentException("Unknown media key: {$key}");
        }

        return match ($key) {
            'unprocessed' => $this->scanUnprocessed(),
            'public'      => $this->scanAssets(),
            default       => [],
        };
    }

    private function scanUnprocessed(): array
    {
        $dir = rtrim($this->projectRoot . $this->paths['unprocessed'], '/');

        if (!is_dir($dir)) {
            return [];
        }

        $items = [];

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (!preg_match('/\.(jpe?g|png)$/i', $file)) {
                continue;
            }

            $items[] = [
                'filename'   => $file,
                'identifier' => pathinfo($file, PATHINFO_FILENAME),
            ];
        }

        return $items;
    }

    private function scanAssets(): array
    {
        $pathFromCfg = (string)($this->paths['public'] ?? '');

        if (str_starts_with($pathFromCfg, '/public/')) {
            $pathFromCfg = substr($pathFromCfg, 7);
        }

        $dir = rtrim($this->publicRoot . '/' . ltrim($pathFromCfg, '/'), '/');

        if (!is_dir($dir)) {
            return [];
        }

        return $this->scanGroupedImages($dir);
    }

    private function scanGroupedImages(string $dir): array
    {
        $groups = [];

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (!preg_match(
                '/^(?<id>[^-]+)-(?<ratio>\d+x\d+)-(?<size>\d+)\.(webp|avif)$/',
                $file,
                $m
            )) {
                continue;
            }

            $id = $m['id'];

            if (!isset($groups[$id])) {
                $groups[$id] = [
                    'identifier' => $id,
                    'files'      => [],
                    'thumb'      => null,
                ];
            }

            $groups[$id]['files'][] = $file;

            if ($groups[$id]['thumb'] === null && (int)$m['size'] <= 480) {
                $groups[$id]['thumb'] =
                    $this->assetBasePath . '/' . $file;
            }
        }

        return array_values($groups);
    }

    public function getAssetIdentifiers(): array
    {
        $assets = $this->getMedia('public');

        $ids = array_map(
            static fn (array $img) => (string)($img['identifier'] ?? ''),
            $assets
        );

        $ids = array_values(array_filter($ids, static fn (string $v) => $v !== ''));
        sort($ids);

        return $ids;
    }

    public function deleteUnprocessed(string $filename): void
    {
        $dir  = rtrim($this->projectRoot . $this->paths['unprocessed'], '/');
        $file = $dir . '/' . basename($filename);

        if (is_file($file)) {
            unlink($file);
        }
    }

    public function deleteAsset(string $identifier): void
    {
        $pathFromCfg = (string)($this->paths['public'] ?? '');
        $dir = rtrim($this->publicRoot . '/' . ltrim($pathFromCfg, '/'), '/');

        if (!is_dir($dir)) {
            return;
        }

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (str_starts_with($file, $identifier . '-')) {
                @unlink($dir . '/' . $file);
            }
        }
    }
}