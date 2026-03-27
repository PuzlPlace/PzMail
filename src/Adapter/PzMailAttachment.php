<?php

declare(strict_types=1);

namespace Puzl\PzMail\Adapter;

final class PzMailAttachment
{
    public function __construct(
        public readonly string $name,
        public readonly mixed $content,
        public readonly string $type = '',
    ) {
    }
}
