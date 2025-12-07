<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMiddlewareCommand extends BaseCommand
{
    protected static $defaultName = 'make:middleware';
    protected static $defaultDescription = '创建一个新的中间件';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, '中间件名称');
    }

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (!$name) {
            $name = $this->ask('请输入中间件名称 (例如：CheckAge)');
            if (!$name) {
                $this->error('必须提供中间件名称。');
                return self::FAILURE;
            }
        }

        $path = APP_ROOT . '/app/Middleware/';
        $filePath = $path . $name . '.php';

        if (file_exists($filePath)) {
            if (!$this->confirm("中间件 '{$name}' 已存在。是否覆盖？", false)) {
                $this->info('操作已取消。');
                return self::SUCCESS;
            }
        }

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $stubPath = APP_ROOT . '/src/Console/stubs/middleware.stub';
        $stub = file_get_contents($stubPath);

        $content = str_replace(
            ['{{ namespace }}', '{{ class }}'],
            ['App\Middleware', $name],
            $stub
        );

        file_put_contents($filePath, $content);

        $this->success("中间件 '{$name}' 创建成功！\n路径：[app/Middleware/{$name}.php]");

        return self::SUCCESS;
    }
}
