<?php

namespace Luminode\Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class BaseCommand extends Command
{
    protected SymfonyStyle $io;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        // 可选：每次运行命令都显示 Logo（或者只在特定命令显示）
        // $this->printLogo(); 

        return $this->handle($input, $output);
    }

    /**
     * 子类实现此方法替代 execute
     */
    abstract protected function handle(InputInterface $input, OutputInterface $output): int;

    protected function printLogo()
    {
        $logo = <<<ASCII
  _    _   _ __  __ ___ _   _  ___  ____  _____ 
 | |  | | | |  \/  |_ _| \ | |/ _ \|  _ \| ____|
 | |  | | | | |\/| || ||  \| | | | | | | |  _|  
 | |__| |_| | |  | || || |\  | |_| | |_| | |___ 
 |_____\___/|_|  |_|___|_| \_|\___/|____/|_____|
                                                
ASCII;
        $this->io->writeln("<fg=cyan>{$logo}</>");
        $this->io->newLine();
    }

    protected function info($message)
    {
        $this->io->block($message, 'INFO', 'fg=black;bg=blue', ' ', true);
    }

    protected function success($message)
    {
        $this->io->success($message);
    }

    protected function error($message)
    {
        $this->io->error($message);
    }

    protected function ask($question, $default = null)
    {
        return $this->io->ask($question, $default);
    }

    protected function confirm($question, $default = true)
    {
        return $this->io->confirm($question, $default);
    }
}
