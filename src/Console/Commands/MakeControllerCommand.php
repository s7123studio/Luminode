<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends BaseCommand

{

    protected static $defaultName = 'make:controller';

    protected static $defaultDescription = '创建一个新的控制器类';



    protected function configure(): void

    {

        $this->addArgument('name', InputArgument::OPTIONAL, '控制器名称');

        $this->addOption('resource', 'r', InputOption::VALUE_NONE, '生成包含 CRUD 方法的资源控制器');

    }



    protected function handle(InputInterface $input, OutputInterface $output): int

    {

        $name = $input->getArgument('name');

        

        // 交互式询问：如果用户没有提供名称

        if (!$name) {

            $name = $this->ask('请输入控制器名称 (例如：UserController)');

            if (!$name) {

                $this->error('必须提供控制器名称。');

                return self::FAILURE;

            }

        }



        $isResource = $input->getOption('resource');



        // 自动补充 "Controller" 后缀

        if (!str_ends_with($name, 'Controller')) {

            $name .= 'Controller';

        }



        $controllerPath = APP_ROOT . '/app/Controllers/';

        $filePath = $controllerPath . $name . '.php';



        // 检查文件是否存在，并询问覆盖

        if (file_exists($filePath)) {

            if (!$this->confirm("控制器 '{$name}' 已存在。是否覆盖？", false)) {

                $this->info('操作已取消。');

                return self::SUCCESS;

            }

        }



        if (!is_dir($controllerPath)) {

            mkdir($controllerPath, 0755, true);

        }



        $stubFileName = $isResource ? 'controller.resource.stub' : 'controller.stub';

        $stubPath = APP_ROOT . '/src/Console/stubs/' . $stubFileName;



        if (!file_exists($stubPath)) {

            $this->error("未找到控制器存根文件：{$stubPath}");

            return self::FAILURE;

        }

        $stub = file_get_contents($stubPath);



        $replacements = [

            '{{ namespace }}' => 'App\Controllers',

            '{{ class }}' => $name,

            '{{ base_class }}' => 'Luminode\Core\Controller',

            '{{ base_class_name }}' => 'Controller',

        ];



        $content = str_replace(array_keys($replacements), array_values($replacements), $stub);



        file_put_contents($filePath, $content);



        $this->success("控制器 '{$name}' 创建成功！\n路径：[app/Controllers/{$name}.php]");



        return self::SUCCESS;

    }

}
