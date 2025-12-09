<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpCommand extends BaseCommand
{
    protected static $defaultName = 'up';
    protected static $defaultDescription = '解除维护模式，应用恢复上线';

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $file = APP_ROOT . '/storage/framework/down';

        if (!file_exists($file)) {
            $this->io->warning("应用并未处于维护模式。");
            return self::SUCCESS;
        }

        unlink($file);

        $this->success("维护模式已解除，应用已恢复上线。");

        return self::SUCCESS;
    }
}
