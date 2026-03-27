<?php

declare(strict_types=1);

namespace Puzl\PzMail\Adapter;

final class PzMailSmtpSettings
{
    public function __construct(
        public readonly string $username,
        public readonly string $password,
        public readonly string $host,
        public readonly int $port,
        public readonly PzMailSmtpSecureEnum $smtpSecure = PzMailSmtpSecureEnum::TLS,
        public readonly bool $smtpRequireAuth = false,
    ) {
    }
}
