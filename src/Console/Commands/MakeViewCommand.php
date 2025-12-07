<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeViewCommand extends BaseCommand
{
    protected static $defaultName = 'make:view';
    protected static $defaultDescription = '创建一个新的视图文件';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, '视图名称 (例如：posts.show)');
    }

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        if (!$name) {
            $name = $this->ask('请输入视图名称 (支持点语法，例如：users.index)');
            if (!$name) {
                $this->error('必须提供视图名称。');
                return self::FAILURE;
            }
        }

        $viewPath = APP_ROOT . '/resources/views/';
        
        // 将点语法转换为目录分隔符
        $pathParts = explode('.', $name);
        $viewName = array_pop($pathParts);
        $directory = $viewPath . implode(DIRECTORY_SEPARATOR, $pathParts);

        // 确保目录以分隔符结尾（如果不是空目录）
        if (!empty($pathParts)) {
            $directory .= DIRECTORY_SEPARATOR;
        }

        $filePath = $directory . $viewName . '.php';

        if (file_exists($filePath)) {
            if (!$this->confirm("视图 '{$name}' 已存在。是否覆盖？", false)) {
                $this->info('操作已取消。');
                return self::SUCCESS;
            }
        }

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $stubPath = APP_ROOT . '/src/Console/stubs/view.stub';
        if (!file_exists($stubPath)) {
            $this->error("未找到视图存根文件：{$stubPath}");
            return self::FAILURE;
        }
        $stub = file_get_contents($stubPath);

        $content = str_replace('{{ view_name }}', $name, $stub);

        file_put_contents($filePath, $content);

        $this->success("视图 '{$name}' 创建成功！\n路径：[resources/views/{$name}.php]"); // 简化路径显示

        return self::SUCCESS;
    }
}