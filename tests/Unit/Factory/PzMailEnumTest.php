<?php

declare(strict_types=1);

namespace Puzl\PzMail\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Puzl\PzMail\Factory\PzMailEnum;

final class PzMailEnumTest extends TestCase
{
    public function testTryFromNameReturnsCase(): void
    {
        self::assertSame(PzMailEnum::SYMFONY_MAILER, PzMailEnum::tryFromName('SYMFONY_MAILER'));
        self::assertSame(PzMailEnum::PHP_MAILER, PzMailEnum::tryFromName('PHP_MAILER'));
    }

    public function testTryFromNameReturnsNullForUnknown(): void
    {
        self::assertNull(PzMailEnum::tryFromName('UNKNOWN'));
    }
}
