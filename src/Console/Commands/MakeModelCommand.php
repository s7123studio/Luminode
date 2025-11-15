<?php

namespace Luminode\Core\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

class MakeModelCommand extends Command
{
    protected static $defaultName = 'make:model';
    protected static $defaultDescription = 'Create a new model class';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the model');
        $this->addOption('controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $modelPath = APP_ROOT . '/app/Models/';
        $filePath = $modelPath . $name . '.php';

        if (file_exists($filePath)) {
            $output->writeln("<error>Model '{$name}' already exists!</error>");
            return Command::FAILURE;
        }

        if (!is_dir($modelPath)) {
            mkdir($modelPath, 0755, true);
        }

        $stubPath = APP_ROOT . '/src/Console/stubs/model.stub';
        if (!file_exists($stubPath)) {
            $output->writeln("<error>Model stub not found at: {$stubPath}</error>");
            return Command::FAILURE;
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

        $output->writeln("<info>Model '{$name}' created successfully.</info>");

        // Call make:controller command if --controller option is present
        if ($input->getOption('controller')) {
            $controllerName = $name . 'Controller';
            $controllerCommand = $this->getApplication()->find('make:controller');
            $controllerInput = new ArrayInput([
                'name' => $controllerName,
                '--resource' => true, // Always make it a resource controller
            ]);
            $controllerCommand->run($controllerInput, $output);
        }

        return Command::SUCCESS;
    }
}
