<?php

declare(strict_types=1);

namespace Puzl\PzMail\Factory;

use Puzl\PzMail\Adapter\Symfony\PzMailSymfony;
use Puzl\PzMail\Contract\PzMailInterface;

final class PzMailFactory
{
    public static function make(?PzMailEnum $adapter = null): PzMailInterface
    {
        if ($adapter === null) {
            $adapter = self::resolveAdapterFromConfig();
        }

        return match ($adapter) {
            PzMailEnum::SYMFONY_MAILER => new PzMailSymfony(),
            PzMailEnum::PHP_MAILER,
            PzMailEnum::OTHERS => new PzMailSymfony(),
        };
    }

    private static function resolveAdapterFromConfig(): PzMailEnum
    {
        $name = 'SYMFONY_MAILER';
        if (function_exists('config')) {
            $name = (string) config('pzmail.adapter', 'SYMFONY_MAILER');
        } else {
            $env = getenv('PZMAIL_ADAPTER');
            if (is_string($env) && $env !== '') {
                $name = $env;
            }
        }

        return PzMailEnum::tryFromName($name) ?? PzMailEnum::SYMFONY_MAILER;
    }
}
