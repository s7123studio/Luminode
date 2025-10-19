<?php

namespace Luminode\Core\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeControllerCommand extends Command
{
    protected static $defaultName = 'make:controller';
    protected static $defaultDescription = 'Create a new controller class';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the controller');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $controllerPath = APP_ROOT . '/app/Controllers/';
        $filePath = $controllerPath . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln("<error>Controller '{$name}' already exists!</error>");
            return Command::FAILURE;
        }

        if (!is_dir($controllerPath)) {
            mkdir($controllerPath, 0755, true);
        }

        $stubPath = APP_ROOT . '/src/Console/stubs/controller.stub';
        if (!file_exists($stubPath)) {
            $output->writeln("<error>Controller stub not found at: {$stubPath}</error>");
            return Command::FAILURE;
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

        $output->writeln("<info>Controller '{$name}' created successfully.</info>");

        return Command::SUCCESS;
    }
}
