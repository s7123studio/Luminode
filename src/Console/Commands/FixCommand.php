<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends BaseCommand
{
    protected static $defaultName = 'fix';
    protected static $defaultDescription = '自动格式化代码 (PHP-CS-Fixer)';

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title("🧹 代码格式化工具");

        // 检查 vendor/bin/php-cs-fixer 是否存在
        $fixerPath = APP_ROOT . '/vendor/bin/php-cs-fixer';
        
        // Windows 下可能是 .bat
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $fixerPath .= '.bat';
        }

        if (!file_exists($fixerPath)) {
            $this->error("未找到 php-cs-fixer。");
            $this->io->text("请先运行: composer require friendsofphp/php-cs-fixer --dev");
            return self::FAILURE;
        }

        $this->io->text("正在格式化代码...");

        // 格式化 app 和 src 目录
        $command = sprintf(
            '%s fix %s --rules=@PSR12 --allow-risky=yes',
            escapeshellarg($fixerPath),
            escapeshellarg(APP_ROOT . '/app')
        );
        passthru($command);
        
        $commandSrc = sprintf(
            '%s fix %s --rules=@PSR12 --allow-risky=yes',
            escapeshellarg($fixerPath),
            escapeshellarg(APP_ROOT . '/src')
        );
        passthru($commandSrc);

        $this->success("代码格式化完成！");

        return self::SUCCESS;
    }
}
