<?php

declare(strict_types=1);

namespace Puzl\PzMail\Factory;

enum PzMailEnum: int
{
    case SYMFONY_MAILER = 0;
    case PHP_MAILER = 1;
    case OTHERS = 2;

    public static function tryFromName(string $name): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }

        return null;
    }
}
