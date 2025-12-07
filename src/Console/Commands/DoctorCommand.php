<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DoctorCommand extends BaseCommand
{
    protected static $defaultName = 'doctor';
    protected static $defaultDescription = '检查系统环境与配置健康状况';

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title("🚑 Luminode 系统诊断 (Doctor)");

        $checks = [
            'PHP 版本 >= 8.0' => version_compare(PHP_VERSION, '8.0.0', '>='),
            '扩展: mbstring' => extension_loaded('mbstring'),
            '扩展: pdo_mysql' => extension_loaded('pdo_mysql'),
            '扩展: json' => extension_loaded('json'),
            '扩展: ctype' => extension_loaded('ctype'),
            '配置文件 (.env)' => file_exists(APP_ROOT . '/.env'),
            '日志目录可写 (storage/logs)' => is_writable(APP_ROOT . '/storage/logs'),
        ];

        $allPassed = true;

        foreach ($checks as $requirement => $passed) {
            if ($passed) {
                $this->io->writeln(" <fg=green>✓</> $requirement");
            } else {
                $this->io->writeln(" <fg=red>✗</> $requirement");
                $allPassed = false;
            }
        }

        $this->io->newLine();

        if ($allPassed) {
            $this->success("诊断完成：系统状态良好，随时可以起飞！🚀");
            return self::SUCCESS;
        } else {
            $this->error("诊断完成：发现一些问题，请修复上述标记为 ✗ 的项目。");
            return self::FAILURE;
        }
    }
}
