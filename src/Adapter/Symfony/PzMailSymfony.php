<?php

declare(strict_types=1);

namespace Puzl\PzMail\Adapter\Symfony;

use Puzl\PzMail\Adapter\PzMailBase;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\DataPart;

final class PzMailSymfony extends PzMailBase
{
    private string $dsn = '';

    private Email $message;

    public function send(): self
    {
        return $this
            ->setMessage()
            ->setTargetAddress()
            ->setCarbonCopies()
            ->setBlindCarbonCopies()
            ->setAttachments()
            ->setDsn()
            ->sendSymfony();
    }

    private function setMessage(): self
    {
        $this->message = new Email();
        $from = new Address(
            $this->getFrom()->address,
            $this->getFrom()->name
        );
        $this->message
            ->subject($this->getSubject())
            ->from($from)
            ->html($this->getBody());
        foreach ($this->textHeaders as $textHeader) {
            $trimmed = trim($textHeader['value']);
            if ($trimmed !== '') {
                $this->message->getHeaders()->addTextHeader($textHeader['name'], $trimmed);
            }
        }

        return $this;
    }

    private function setTargetAddress(): self
    {
        foreach ($this->getToAddresses() as $address) {
            $this->message->addTo(new Address(
                $address->address,
                $address->name
            ));
        }

        return $this;
    }

    private function setCarbonCopies(): self
    {
        foreach ($this->getCarbonCopies() as $carbonCopy) {
            $this->message->addCc($carbonCopy);
        }

        return $this;
    }

    private function setBlindCarbonCopies(): self
    {
        foreach (explode(',', $this->defaultBccString()) as $email) {
            $email = trim($email);
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->message->addBcc($email);
            }
        }
        foreach ($this->getBlindCarbonCopies() as $blindCarbonCopy) {
            if (filter_var($blindCarbonCopy, FILTER_VALIDATE_EMAIL)) {
                $this->message->addBcc($blindCarbonCopy);
            }
        }

        return $this;
    }

    private function defaultBccString(): string
    {
        if (function_exists('config')) {
            $pz = config('pzmail.bcc_default');
            if (is_string($pz) && $pz !== '') {
                return $pz;
            }
            $mail = config('mail.bcc.default');
            if (is_string($mail) && $mail !== '') {
                return $mail;
            }
        }
        $env = getenv('MAIL_BCC_DEFAULT');

        return is_string($env) ? $env : '';
    }

    private function setAttachments(): self
    {
        foreach ($this->getAttachments() as $attachment) {
            $type = $attachment->type === null || $attachment->type === ''
                ? 'application/octet-stream'
                : $attachment->type;
            match (is_string($attachment->content) && file_exists($attachment->content)) {
                true => $this->message->attachFromPath($attachment->content, $attachment->name, $type),
                false => $this->message->addPart(new DataPart($attachment->content, $attachment->name, $type)),
            };
        }

        return $this;
    }

    private function setDsn(): self
    {
        if ($this->shouldUseNullTransport()) {
            $this->dsn = 'null://null';

            return $this;
        }

        $smtpSettings = $this->getSmtpSettings();
        $username = urlencode($smtpSettings->username);
        $password = urlencode($smtpSettings->password);

        $this->dsn = sprintf(
            'smtp://%s:%s@%s:%d?encryption=tls&auth_mode=login&timeout=5',
            $username,
            $password,
            $smtpSettings->host,
            $smtpSettings->port
        );

        return $this;
    }

    private function shouldUseNullTransport(): bool
    {
        $v = $_ENV['PZMAIL_NULL_TRANSPORT'] ?? getenv('PZMAIL_NULL_TRANSPORT');

        return $v === '1' || $v === 'true';
    }

    private function sendSymfony(): self
    {
        try {
            $transport = Transport::fromDsn($this->dsn);
            $mailer = new Mailer($transport);
            $mailer->send($this->message);
        } finally {
            $this->prepare();
        }

        return $this;
    }
}
