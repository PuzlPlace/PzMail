<?php

declare(strict_types=1);

namespace Puzl\PzMail\Adapter;

enum PzMailSmtpSecureEnum: int
{
    case TLS = 0;
    case STARTTLS = 1;
    case SSL = 2;
}
