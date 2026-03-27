<?php

declare(strict_types=1);

namespace Puzl\PzMail\Adapter;

final class PzMailRecipient
{
    public function __construct(
        public readonly string $address,
        public readonly string $name = '',
    ) {
    }
}
