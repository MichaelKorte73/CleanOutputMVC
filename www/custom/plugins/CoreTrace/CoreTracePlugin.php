<?php
declare(strict_types=1);

namespace Plugins\CoreTrace;

use CHK\Core\App;
use CHK\Core\HookManager;
use CHK\Core\PluginInterface;
use CHK\Logging\LogLevel;

final class CoreTracePlugin implements PluginInterface
{
    public function register(HookManager $hooks, App $app): void
    {
        $hooks->addAction('app.ready', function (
            App $app,
            mixed $response,
            int $status
        ): void {
            $logger = $app->getLogger();

            $logger->log(
                LogLevel::INFO,
                'plugin',
                self::class,
                'App ready hook triggered',
                [
                    'status'        => $status,
                    'response_type' => is_object($response)
                        ? get_class($response)
                        : gettype($response),
                ]
            );
        });
    }
}