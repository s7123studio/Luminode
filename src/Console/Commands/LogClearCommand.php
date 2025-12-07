<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogClearCommand extends BaseCommand
{
    protected static $defaultName = 'log:clear';
    protected static $defaultDescription = '清空应用日志文件';

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $logFile = APP_ROOT . '/storage/logs/app.log';

        if (!file_exists($logFile)) {
            $this->io->warning("日志文件不存在，无需清理。");
            return self::SUCCESS;
        }

        if (file_put_contents($logFile, '') !== false) {
            $this->success("日志已清空。");
            return self::SUCCESS;
        } else {
            $this->error("无法清空日志文件，请检查权限。");
            return self::FAILURE;
        }
    }
}
