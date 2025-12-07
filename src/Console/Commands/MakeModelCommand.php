<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class MakeModelCommand extends BaseCommand
{
    protected static $defaultName = 'make:model';
    protected static $defaultDescription = '创建一个新的模型类';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, '模型名称');
        $this->addOption('controller', 'c', InputOption::VALUE_NONE, '同时为该模型创建一个控制器');
    }

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        
        // 交互式询问
        if (!$name) {
            $name = $this->ask('请输入模型名称 (例如：Post)');
            if (!$name) {
                $this->error('必须提供模型名称。');
                return self::FAILURE;
            }
        }

        $modelPath = APP_ROOT . '/app/Models/';
        $filePath = $modelPath . $name . '.php';

        // 检查覆盖
        if (file_exists($filePath)) {
            if (!$this->confirm("模型 '{$name}' 已存在。是否覆盖？", false)) {
                $this->info('操作已取消。');
                return self::SUCCESS;
            }
        }

        if (!is_dir($modelPath)) {
            mkdir($modelPath, 0755, true);
        }

        $stubPath = APP_ROOT . '/src/Console/stubs/model.stub';
        if (!file_exists($stubPath)) {
            $this->error("未找到模型存根文件：{$stubPath}");
            return self::FAILURE;
        }
        $stub = file_get_contents($stubPath);

        $replacements = [
            '{{ namespace }}' => 'App\Models',
            '{{ class }}' => $name,
            '{{ base_class }}' => 'Luminode\Core\ORM\BaseModel',
            '{{ base_class_name }}' => 'BaseModel',
        ];

        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);

        file_put_contents($filePath, $content);

        $this->success("模型 '{$name}' 创建成功！\n路径：[app/Models/{$name}.php]");

        // 创建关联控制器
        if ($input->getOption('controller')) {
            $this->io->section('正在创建关联控制器...');
            
            $controllerName = $name . 'Controller';
            $controllerCommand = $this->getApplication()->find('make:controller');
            
            // 我们不能直接调用 handle，因为它是 protected 的。
            // 我们通过 Application 运行它。
            // 为了让 MakeControllerCommand 不进入交互模式，我们需要确保提供了参数。
            $controllerInput = new ArrayInput([
                'name' => $controllerName,
                '--resource' => true, 
            ]);
            
            // 这里可能会有个小问题：如果控制器已存在，MakeControllerCommand 会询问。
            // 由于我们共享了 Input/Output，用户应该能看到询问并输入。
            $controllerCommand->run($controllerInput, $output);
        }

        return self::SUCCESS;
    }
}