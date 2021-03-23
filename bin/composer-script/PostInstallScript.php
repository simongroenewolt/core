<?php

namespace Bolt\ComposerScripts;

class PostInstallScript extends Script
{
    public static function execute()
    {
        parent::init('Running composer "post-install-cmd" scripts');

        self::run('php bin/console bolt:copy-assets --ansi');
        self::run('php bin/console cache:clear --no-warmup --ansi');
        self::run('php bin/console assets:install --ansi');

        $migrationError = self::run('php bin/console doctrine:migrations:up-to-date --ansi');
        if ($migrationError) {
            self::$console->warning('Please run `php bin/console doctrine:migrations:migrate` to execute the database migrations.');
        }

        self::run('php bin/console extensions:configure --ansi');
    }
}