<?php
/*
 * @Author: 7123
 * @Date: 2025-10-18 19:27:15
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:16:18
 */

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Router;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class RouteListCommand extends Command
{
    protected static $defaultName = 'route:list';
    protected static $defaultDescription = 'List all registered routes';

    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $routes = $this->router->getRoutes();

        if (empty($routes)) {
            $output->writeln('<info>No routes defined.</info>');
            return Command::SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['Method', 'Path', 'Action']);

        $tableRows = [];
        foreach ($routes as $route) {
            $tableRows[] = ['<comment>' . $route['method'] . '</comment>', $route['path'], $route['action']];
        }
        $table->setRows($tableRows);

        $table->render();

        return Command::SUCCESS;
    }
}
