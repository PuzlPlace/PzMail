# puzl/pzmail (PzMail)

Biblioteca PHP **PzMail**: envio de e-mail via **Symfony Mailer** (SMTP), pensada para o ecossistema Puzl e integração com Laravel (auto-discovery do provider, config publicável).

## Requisitos

- PHP **8.1+**
- Laravel **10+** (opcional, para auto-discovery e `config/pzmail.php` publicável)
- Extensões usadas pelo Symfony Mailer / MIME (normalmente **`mbstring`**)
- [Composer](https://getcomposer.org/)

## Instalação no Laravel

Use sempre o branch **`production`** para instalar o pacote num projeto Laravel. **Não** use `master` para essa instalação — o código estável para consumo fica em `production`.

### Passo a passo

1. **Abrir o `composer.json` do projeto Laravel** e adicionar o repositório VCS (ajuste a URL ao repositório real):

   ```json
   {
       "repositories": [
           {
               "type": "vcs",
               "url": "https://github.com/PuzlPlace/PzMail.git"
           }
       ]
   }
   ```

2. **Instalar a versão do branch `production`**. No Composer, branches têm o prefixo `dev-`; para `production` use `dev-production`:

   ```bash
   composer require puzl/pzmail:dev-production
   ```

   Alternativa equivalente: declarar no `composer.json` em `require` e correr `composer update`:

   ```json
   "require": {
       "puzl/pzmail": "dev-production"
   }
   ```

3. **Publicar a config** no Laravel (opcional mas recomendado):

   ```bash
   php artisan vendor:publish --tag=pzmail-config
   ```

O pacote regista automaticamente o `PzMailServiceProvider` (via `extra.laravel` no Composer).

### Branches

- **`production`** — linha para dependências em projetos (`dev-production` no Composer).
- **`master`** — desenvolvimento integração; não é o alvo da instalação descrita acima.

## Uso rápido

```php
<?php

declare(strict_types=1);

use Puzl\PzMail\Factory\PzMailFactory;

PzMailFactory::make()
    ->setFrom($fromEmail, $fromName)
    ->setSubject($subject)
    ->addToAddresses([['email' => 'a@ex.com', 'name' => '']])
    ->setBody($html)
    ->addAttachments($attachments)
    ->setSmtpSettings($user, $pass, $host, (int) $port)
    ->send();
```

## Configuração (`config/pzmail.php`)

Chaves principais:

- **`adapter`** — nome do caso em `PzMailEnum` (ex.: `SYMFONY_MAILER`)
- **`bcc_default`** — lista de BCC por vírgula (alinhado a `MAIL_BCC_DEFAULT`)

## Testes (PHPUnit)

Na raiz do repositório, instale as dependências (inclui o PHPUnit em `vendor/`):

```bash
composer install
```

**Forma direta de rodar a suíte**:

```bash
./vendor/bin/phpunit -c phpunit.xml.dist
```

Se o shebang do binário não for usado no seu ambiente, equivale a: `php vendor/bin/phpunit -c phpunit.xml.dist`.

**Atalho via Composer**:

```bash
composer test
```

A variável **`PZMAIL_NULL_TRANSPORT=1`** (definida no `phpunit.xml.dist`) faz o envio usar o transporte nulo do Symfony Mailer nos testes — **não** envia e-mail real nem abre SMTP.

## Estrutura

- `src/` — biblioteca (`Puzl\PzMail\…`), incluindo `Laravel/` (service provider)
- `config/pzmail.php` — defaults do pacote
- `tests/` — PHPUnit

## Licença

Proprietária (Puzl).
