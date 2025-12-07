<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServeCommand extends BaseCommand
{
    protected static $defaultName = 'serve';
    protected static $defaultDescription = '启动内置开发服务器';

    protected function configure()
    {
        $this
            ->addOption('host', null, InputOption::VALUE_OPTIONAL, '服务器监听地址', '127.0.0.1')
            ->addOption('port', null, InputOption::VALUE_OPTIONAL, '服务器监听端口', '8000');
    }

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->printLogo();

        $host = $input->getOption('host');
        $port = $input->getOption('port');
        $docRoot = APP_ROOT . '/public';

        $this->io->title("🚀 Luminode 开发服务器");
        
        $this->io->text([
            "服务器运行于 <fg=yellow>http://{$host}:{$port}</>",
            "文档根目录: <fg=gray>{$docRoot}</>",
            "按 <fg=red>Ctrl+C</> 停止服务器."
        ]);

        $this->io->newLine();

        $command = sprintf(
            'php -S %s:%s -t %s',
            $host,
            $port,
            escapeshellarg($docRoot)
        );

        // 使用 passthru 直接将输出透传到终端，这样可以看到访问日志
        passthru($command);

        return self::SUCCESS;
    }
}
