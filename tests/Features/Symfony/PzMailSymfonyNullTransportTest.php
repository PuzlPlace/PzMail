<?php

declare(strict_types=1);

namespace Puzl\PzMail\Tests\Features\Symfony;

use PHPUnit\Framework\TestCase;
use Puzl\PzMail\Adapter\Symfony\PzMailSymfony;

/**
 * Com PZMAIL_NULL_TRANSPORT=1 (phpunit.xml.dist), o envio usa DSN null://null e não abre rede.
 */
final class PzMailSymfonyNullTransportTest extends TestCase
{
    public function testSendCompletesWithNullTransport(): void
    {
        self::assertTrue(
            ($_ENV['PZMAIL_NULL_TRANSPORT'] ?? '') === '1'
            || getenv('PZMAIL_NULL_TRANSPORT') === '1'
        );

        $mailer = new PzMailSymfony();
        $mailer
            ->setFrom('from@example.com', 'From Name')
            ->setSubject('Subject')
            ->setBody('<p>Hello</p>')
            ->addToAddress('to@example.com', 'To')
            ->setSmtpSettings('user', 'pass', '127.0.0.1', 587);

        $mailer->send();

        self::assertCount(0, $mailer->getToAddresses(), 'prepare() deve limpar após send()');
    }
}
