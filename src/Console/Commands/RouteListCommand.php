<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Luminode\Core\Router;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteListCommand extends BaseCommand

{

    protected static $defaultName = 'route:list';

    protected static $defaultDescription = '列出所有已注册的路由';



    private Router $router;



    public function __construct(Router $router)

    {

        parent::__construct();

        $this->router = $router;

    }



    protected function handle(InputInterface $input, OutputInterface $output): int

    {

        $this->io->title("📋 已注册路由");



        // Manually load routes to ensure the router is populated

        // We use $router variable because routes/web.php likely expects it

        $router = $this->router;

        if (file_exists(APP_ROOT . '/routes/web.php')) {

            require_once APP_ROOT . '/routes/web.php';

        }



        $routes = $this->router->getRoutes();



        if (empty($routes)) {

            $this->io->warning("未找到任何路由。");

            return self::SUCCESS;

        }



        $rows = [];

        foreach ($routes as $route) {

            $method = $route['method'];

            $methodStyled = match ($method) {

                'GET' => "<fg=green>{$method}</>",

                'POST' => "<fg=yellow>{$method}</>",

                'PUT', 'PATCH' => "<fg=blue>{$method}</>",

                'DELETE' => "<fg=red>{$method}</>",

                default => $method,

            };



            $middleware = empty($route['middleware']) ? '<fg=gray>无</>' : $route['middleware'];



            $rows[] = [

                $methodStyled,

                $route['path'],

                $route['action'],

                $middleware

            ];

        }



        $this->io->table(

            ['方法 (Method)', 'URI', '动作 (Action)', '中间件 (Middleware)'],

            $rows

        );



        return self::SUCCESS;

    }

}
