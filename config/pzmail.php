<?php

declare(strict_types=1);

/**
 * PzMail — configuração do adaptador de envio.
 *
 * Publicar no app: php artisan vendor:publish --tag=pzmail-config
 *
 * @var array<string, mixed>
 */
return [
    /**
     * Nome do caso em {@see \Puzl\PzMail\Factory\PzMailEnum} (ex.: SYMFONY_MAILER).
     */
    'adapter' => env('PZMAIL_ADAPTER', 'SYMFONY_MAILER'),

    /**
     * BCC padrão (lista separada por vírgula), espelhando mail.bcc.default quando migrar.
     */
    'bcc_default' => env('MAIL_BCC_DEFAULT', ''),
];
