<?php

declare(strict_types=1);

namespace Puzl\PzMail\Contract;

use Puzl\PzMail\Adapter\PzMailAttachment;
use Puzl\PzMail\Adapter\PzMailRecipient;
use Puzl\PzMail\Adapter\PzMailSmtpSecureEnum;
use Puzl\PzMail\Adapter\PzMailSmtpSettings;

interface PzMailInterface
{
    public function getFrom(): PzMailRecipient;

    public function setFrom(string $email, string $name = ''): self;

    /**
     * @return array<int, PzMailRecipient>
     */
    public function getToAddresses(): array;

    public function addToAddress(string $email, string $name = ''): self;

    public function addToAddresses(array $addresses): self;

    /**
     * @return array<int, PzMailRecipient>
     */
    public function getRepliesTo(): array;

    public function addReplyTo(string $email, string $name = ''): self;

    public function addRepliesTo(array $repliesTo): self;

    /**
     * @return array<int, string>
     */
    public function getCarbonCopies(): array;

    public function addCarbonCopy(string $email): self;

    public function addCarbonCopies(array $carbonCopies): self;

    /**
     * @return array<int, string>
     */
    public function getBlindCarbonCopies(): array;

    public function addBlindCarbonCopy(string $email): self;

    public function addBlindCarbonCopies(array $blindCarbonCopies): self;

    /**
     * @return array<int, PzMailAttachment>
     */
    public function getAttachments(): array;

    public function addAttachment(mixed $content, string $name, string $type = ''): self;

    public function addAttachments(array $attachments): self;

    public function getIsHtml(): bool;

    public function setIsHtml(bool $value): self;

    public function getSubject(): string;

    public function setSubject(string $value): self;

    public function getBody(): mixed;

    public function setBody(mixed $value): self;

    public function getSmtpSettings(): PzMailSmtpSettings;

    public function setSmtpSettings(
        string $username,
        string $password,
        string $host,
        int $port,
        PzMailSmtpSecureEnum $smtpSecure = PzMailSmtpSecureEnum::TLS,
        bool $smtpRequireAuth = false,
    ): self;

    public function addTextHeader(string $name, string $value): self;

    public function prepare(): self;

    public function send(): self;
}
