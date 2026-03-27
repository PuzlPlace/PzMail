<?php

declare(strict_types=1);

namespace Puzl\PzMail\Adapter;

use Puzl\PzMail\Contract\PzMailInterface;

abstract class PzMailBase implements PzMailInterface
{
    private PzMailRecipient $from;
    /** @var array<int, PzMailRecipient> */
    private array $toAddresses = [];
    /** @var array<int, PzMailRecipient> */
    private array $repliesTo = [];
    /** @var array<int, string> */
    private array $carbonCopies = [];
    /** @var array<int, string> */
    private array $blindCarbonCopies = [];
    /** @var array<int, PzMailAttachment> */
    private array $attachments = [];
    private bool $isHtml = true;
    private string $subject = '';
    private mixed $body = '';
    private PzMailSmtpSettings $smtpSettings;
    /** @var array<string, array{name: string, value: string}> */
    protected array $textHeaders = [];

    public function getFrom(): PzMailRecipient
    {
        return $this->from;
    }

    public function setFrom(string $email, string $name = ''): self
    {
        $emails = array_filter(array_map('trim', explode(',', $email)));
        $this->from = new PzMailRecipient($emails[0], $name);

        if (count($emails) > 1) {
            $extraAddresses = [];
            foreach (array_slice($emails, 1) as $extraEmail) {
                $extraAddresses[] = ['email' => $extraEmail, 'name' => ''];
            }
            $this->addToAddresses($extraAddresses);
        }

        return $this;
    }

    /**
     * @return array<int, PzMailRecipient>
     */
    public function getToAddresses(): array
    {
        return $this->toAddresses;
    }

    public function addToAddress(string $email, string $name = ''): self
    {
        $this->toAddresses[] = new PzMailRecipient($email, $name);

        return $this;
    }

    public function addToAddresses(array $addresses): self
    {
        foreach ($addresses as $toAddress) {
            $this->toAddresses[] = new PzMailRecipient($toAddress['email'], $toAddress['name'] ?? '');
        }

        return $this;
    }

    /**
     * @return array<int, PzMailRecipient>
     */
    public function getRepliesTo(): array
    {
        return $this->repliesTo;
    }

    public function addReplyTo(string $email, string $name = ''): self
    {
        $this->repliesTo[] = new PzMailRecipient($email, $name);

        return $this;
    }

    public function addRepliesTo(array $repliesTo): self
    {
        foreach ($repliesTo as $replyTo) {
            $this->repliesTo[] = new PzMailRecipient($replyTo['email'], $replyTo['name'] ?? '');
        }

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getCarbonCopies(): array
    {
        return $this->carbonCopies;
    }

    public function addCarbonCopy(string $email): self
    {
        $this->carbonCopies[] = $email;

        return $this;
    }

    public function addCarbonCopies(array $carbonCopies): self
    {
        foreach ($carbonCopies as $carbonCopy) {
            $this->carbonCopies[] = $carbonCopy;
        }

        return $this;
    }

    /**
     * @return array<int, string>
     */
    public function getBlindCarbonCopies(): array
    {
        return $this->blindCarbonCopies;
    }

    public function addBlindCarbonCopy(string $email): self
    {
        $this->blindCarbonCopies[] = $email;

        return $this;
    }

    public function addBlindCarbonCopies(array $blindCarbonCopies): self
    {
        foreach ($blindCarbonCopies as $carbonCopy) {
            $this->blindCarbonCopies[] = $carbonCopy;
        }

        return $this;
    }

    /**
     * @return array<int, PzMailAttachment>
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function addAttachment(mixed $content, string $name, string $type = ''): self
    {
        $this->attachments[] = new PzMailAttachment($name, $content, $type);

        return $this;
    }

    public function addAttachments(array $attachments): self
    {
        foreach ($attachments as $attachment) {
            $this->attachments[] = new PzMailAttachment(
                $attachment['name'] ?? '',
                $attachment['content'],
                $attachment['type'] ?? ''
            );
        }

        return $this;
    }

    public function getIsHtml(): bool
    {
        return $this->isHtml;
    }

    public function setIsHtml(bool $value): self
    {
        $this->isHtml = $value;

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $value): self
    {
        $this->subject = $value;

        return $this;
    }

    public function getBody(): mixed
    {
        return $this->body;
    }

    public function setBody(mixed $value): self
    {
        $this->body = $value;

        return $this;
    }

    public function getSmtpSettings(): PzMailSmtpSettings
    {
        return $this->smtpSettings;
    }

    public function setSmtpSettings(
        string $username,
        string $password,
        string $host,
        int $port,
        PzMailSmtpSecureEnum $smtpSecure = PzMailSmtpSecureEnum::TLS,
        bool $smtpRequireAuth = false,
    ): self {
        $this->smtpSettings = new PzMailSmtpSettings(
            $username,
            $password,
            $host,
            $port,
            $smtpSecure,
            $smtpRequireAuth
        );

        return $this;
    }

    public function addTextHeader(string $name, string $value): self
    {
        $key = strtolower(trim($name));
        if ($key === '') {
            return $this;
        }
        $this->textHeaders[$key] = [
            'name' => $name,
            'value' => $value,
        ];

        return $this;
    }

    public function prepare(): self
    {
        $this->toAddresses = [];
        $this->repliesTo = [];
        $this->carbonCopies = [];
        $this->blindCarbonCopies = [];
        $this->attachments = [];
        $this->textHeaders = [];

        return $this;
    }

    abstract public function send(): self;
}
