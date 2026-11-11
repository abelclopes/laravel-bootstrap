# Atualizacao de seguranca do Laravel

Este projeto foi migrado do Laravel 6.5.2 para o Laravel 12.72.0 para corrigir
GHSA-f9x3-7vq9-cph6 (CVE-2026-114509). O composer.json exige Laravel ^12.68.0,
primeira versao corrigida da serie 12, e o composer.lock fixa a versao instalada.
A serie 10 foi descartada porque o Composer identificou outros avisos de seguranca.

## Requisitos e implantacao

- Atualize o PHP da CLI e do servidor web para 8.2 ou superior antes de instalar.
- Use Composer 2 e as extensoes exigidas por `composer check-platform-reqs`.
- Faca backup e valide a atualizacao em homologacao antes de implantar: esta e
  uma migracao de versao principal, incluindo dependencias Symfony e Flysystem.
- Execute `composer install --no-dev --optimize-autoloader` a partir do lockfile.
- Execute `php artisan optimize:clear` e reinicie processos de fila e PHP persistentes.
- Preserve APP_KEY e as credenciais existentes. Nao gere uma nova chave em producao.
- A configuracao SMTP agora usa mailers; MAIL_DRIVER continua aceito como fallback
  de MAIL_MAILER. Integracoes externas de email e armazenamento precisam ser
  verificadas no ambiente que realmente as utiliza.

## Validacao

`composer validate --strict`, `composer audit --locked` e
`php vendor/phpunit/phpunit/phpunit` devem passar. Os testes usam SQLite em memoria.
Os testes do TokenGuard verificam que credenciais nao textuais nunca chegam ao
UserProvider, evitando depender da coercao de tipos especifica de MySQL/MariaDB.
Tambem ha cobertura do login, da pagina autenticada e do token Bearer valido.

O alerta do Dependabot so podera ser encerrado automaticamente depois que o
lockfile corrigido entrar na branch padrao. A protecao do servidor depende da
implantacao das novas dependencias.

## Testes em Docker

Crie a imagem com PHP 8.2 e instale as dependencias diretamente do lockfile:

```sh
docker build -f Dockerfile.test -t laravel-bootstrap-security-test .
docker run --rm laravel-bootstrap-security-test
```

O comando padrao valida o Composer, executa a auditoria e roda a suite com SQLite.
Para executar a mesma suite com MySQL 8.4, sem expor portas do banco no host:

```sh
docker compose -p laravel-bootstrap-security-tests -f compose.test.yaml up --build --abort-on-container-exit --exit-code-from tests
docker compose -p laravel-bootstrap-security-tests -f compose.test.yaml down --volumes
```

As credenciais do Compose sao exclusivas do banco descartavel de testes.

Validado em 11/11/2026 com PHP 8.2.35: SQLite e MySQL 8.4 passaram com
16 testes e 45 assercoes cada. A validacao estrita do Composer passou e a
auditoria do lockfile nao encontrou avisos de seguranca em ambas as execucoes.
