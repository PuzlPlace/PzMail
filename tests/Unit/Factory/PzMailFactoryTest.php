<?php

declare(strict_types=1);

namespace Puzl\PzMail\Tests\Unit\Factory;

use PHPUnit\Framework\TestCase;
use Puzl\PzMail\Adapter\Symfony\PzMailSymfony;
use Puzl\PzMail\Factory\PzMailEnum;
use Puzl\PzMail\Factory\PzMailFactory;

final class PzMailFactoryTest extends TestCase
{
    protected function tearDown(): void
    {
        putenv('PZMAIL_ADAPTER');
        unset($_ENV['PZMAIL_ADAPTER']);
        parent::tearDown();
    }

    public function testMakeWithoutArgumentReturnsSymfonyImplementation(): void
    {
        $mailer = PzMailFactory::make();
        self::assertInstanceOf(PzMailSymfony::class, $mailer);
    }

    public function testMakeWithSymfonyEnumReturnsSymfonyImplementation(): void
    {
        self::assertInstanceOf(PzMailSymfony::class, PzMailFactory::make(PzMailEnum::SYMFONY_MAILER));
    }

    public function testMakeFallsBackToSymfonyForUnimplementedAdapters(): void
    {
        self::assertInstanceOf(PzMailSymfony::class, PzMailFactory::make(PzMailEnum::PHP_MAILER));
    }
}
