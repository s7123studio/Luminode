<?php
/*
 * @Author: 7123
 * @Date: 2025-10-19 01:34:37
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:15:59
 */

namespace Luminode\Core\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeViewCommand extends Command
{
    protected static $defaultName = 'make:view';
    protected static $defaultDescription = 'Create a new view file';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the view (e.g., posts.show)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $viewPath = APP_ROOT . '/resources/views/';
        
        // 将点语法转换为目录分隔符
        $pathParts = explode('.', $name);
        $viewName = array_pop($pathParts);
        $directory = $viewPath . implode(DIRECTORY_SEPARATOR, $pathParts);

        $filePath = $directory . DIRECTORY_SEPARATOR . $viewName . '.php';

        if (file_exists($filePath)) {
            $output->writeln("<error>View '{$name}' already exists!</error>");
            return Command::FAILURE;
        }

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $stubPath = APP_ROOT . '/src/Console/stubs/view.stub';
        if (!file_exists($stubPath)) {
            $output->writeln("<error>View stub not found at: {$stubPath}</error>");
            return Command::FAILURE;
        }
        $stub = file_get_contents($stubPath);

        $content = str_replace('{{ view_name }}', $name, $stub);

        file_put_contents($filePath, $content);

        $output->writeln("<info>View '{$name}' created successfully at: {$filePath}</info>");

        return Command::SUCCESS;
    }
}
