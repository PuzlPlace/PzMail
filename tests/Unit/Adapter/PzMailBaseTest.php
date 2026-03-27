<?php

declare(strict_types=1);

namespace Puzl\PzMail\Tests\Unit\Adapter;

use PHPUnit\Framework\TestCase;
use Puzl\PzMail\Adapter\PzMailAttachment;
use Puzl\PzMail\Adapter\PzMailBase;
use Puzl\PzMail\Adapter\PzMailSmtpSecureEnum;

final class PzMailBaseTest extends TestCase
{
    public function testSetFromWithMultipleCommaSeparatedEmailsUsesFirstAsFromAndRestAsTo(): void
    {
        $m = $this->concrete();
        $m->setFrom('a@example.com, b@example.com', 'Sender');
        self::assertSame('a@example.com', $m->getFrom()->address);
        self::assertSame('Sender', $m->getFrom()->name);
        self::assertCount(1, $m->getToAddresses());
        self::assertSame('b@example.com', $m->getToAddresses()[0]->address);
    }

    public function testAddCarbonCopyAppendsEmailString(): void
    {
        $m = $this->concrete();
        $m->setFrom('from@example.com', '');
        $m->addCarbonCopy('cc@example.com');
        self::assertSame(['cc@example.com'], $m->getCarbonCopies());
    }

    public function testAddBlindCarbonCopyAppendsEmailString(): void
    {
        $m = $this->concrete();
        $m->setFrom('from@example.com', '');
        $m->addBlindCarbonCopy('bcc@example.com');
        self::assertSame(['bcc@example.com'], $m->getBlindCarbonCopies());
    }

    public function testAddAttachmentsFromArray(): void
    {
        $m = $this->concrete();
        $m->setFrom('from@example.com', '');
        $m->addAttachments([
            ['name' => 'f.txt', 'content' => 'hello', 'type' => 'text/plain'],
        ]);
        $atts = $m->getAttachments();
        self::assertCount(1, $atts);
        self::assertInstanceOf(PzMailAttachment::class, $atts[0]);
        self::assertSame('f.txt', $atts[0]->name);
        self::assertSame('hello', $atts[0]->content);
        self::assertSame('text/plain', $atts[0]->type);
    }

    public function testSetSmtpSettings(): void
    {
        $m = $this->concrete();
        $m->setFrom('from@example.com', '');
        $m->setSmtpSettings('u', 'p', 'smtp.example.com', 587, PzMailSmtpSecureEnum::TLS);
        $s = $m->getSmtpSettings();
        self::assertSame('u', $s->username);
        self::assertSame('p', $s->password);
        self::assertSame('smtp.example.com', $s->host);
        self::assertSame(587, $s->port);
        self::assertSame(PzMailSmtpSecureEnum::TLS, $s->smtpSecure);
    }

    public function testAddTextHeaderTrimsEmptyName(): void
    {
        $m = $this->concrete();
        $m->setFrom('from@example.com', '');
        $m->addTextHeader('', 'x');
        $m->addTextHeader('X-Test', '  value  ');
        $ref = new \ReflectionClass($m);
        $prop = $ref->getProperty('textHeaders');
        $prop->setAccessible(true);
        /** @var array<string, array{name: string, value: string}> $headers */
        $headers = $prop->getValue($m);
        self::assertArrayHasKey('x-test', $headers);
        self::assertSame('  value  ', $headers['x-test']['value']);
    }

    public function testPrepareClearsRecipientsAndAttachments(): void
    {
        $m = $this->concrete();
        $m->setFrom('from@example.com', '');
        $m->addToAddress('to@example.com');
        $m->addAttachment('x', 'n');
        $m->prepare();
        self::assertCount(0, $m->getToAddresses());
        self::assertCount(0, $m->getAttachments());
    }

    private function concrete(): PzMailBase
    {
        return new class extends PzMailBase {
            public function send(): self
            {
                return $this;
            }
        };
    }
}
