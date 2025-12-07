<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;
use ReflectionClass;
use ReflectionMethod;

class MakeRouteCommand extends BaseCommand
{
    protected static $defaultName = 'make:route';
    protected static $defaultDescription = '交互式向导生成新路由';

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title("🛣️  智能路由生成器");

        // 1. 选择 HTTP 方法
        $method = $this->io->choice('请选择 HTTP 方法', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], 'GET');

        // 2. 输入 URI
        $uri = $this->ask('请输入路由路径 (URI)', '/new-route');
        if (!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        // 3. 选择控制器
        $controllers = $this->scanControllers();
        if (empty($controllers)) {
            $this->error('未找到任何控制器，请先使用 make:controller 创建。');
            return self::FAILURE;
        }
        $controllers['<fg=cyan>+ 创建新控制器</>'] = 'NEW';
        
        $controllerChoice = $this->io->choice('请选择控制器', array_keys($controllers));
        
        if ($controllers[$controllerChoice] === 'NEW') {
            $newControllerName = $this->ask('新控制器名称');
            $command = $this->getApplication()->find('make:controller');
            $command->run(new ArrayInput(['name' => $newControllerName]), $output);
            $controllers = $this->scanControllers();
            $targetControllerClass = 'App\\Controllers\\' . $newControllerName;
            if (!str_ends_with($targetControllerClass, 'Controller')) {
                $targetControllerClass .= 'Controller';
            }
        } else {
            $targetControllerClass = $controllers[$controllerChoice];
        }

        // 4. 选择方法
        $methods = $this->scanControllerMethods($targetControllerClass);
        $action = empty($methods) ? 'index' : $this->io->choice('请选择控制器方法', $methods, 'index');

        // 5. 选择中间件
        $middlewares = $this->scanMiddlewares();
        $selectedMiddlewares = [];
        if (!empty($middlewares)) {
            if ($this->confirm('是否需要应用中间件？', false)) {
                $selectedMiddlewares = $this->io->choice('请选择中间件 (多选，逗号分隔)', array_keys($middlewares), null, true);
            }
        }

        // 6. 选择路由位置 (根目录 或 分组)
        $routeFile = APP_ROOT . '/routes/web.php';
        $fileContent = file_get_contents($routeFile);
        $groups = $this->scanRouteGroups($fileContent);
        
        $groupChoices = ['<fg=green>根目录 (不分组)</>' => 'ROOT'];
        foreach ($groups as $index => $group) {
            $desc = isset($group['prefix']) ? "Prefix: {$group['prefix']}" : "Group #{$index}";
            $groupChoices["分组: {$desc}"] = $index;
        }

        $targetGroupIndex = 'ROOT';
        if (!empty($groups)) {
            $choice = $this->io->choice('请选择路由插入位置', array_keys($groupChoices), 0);
            $targetGroupIndex = $groupChoices[$choice];
        }

        // 7. 生成代码
        $middlewareCode = '';
        if (!empty($selectedMiddlewares)) {
            $middlewareClasses = array_map(function($m) use ($middlewares) {
                return $middlewares[$m] . '::class'; 
            }, $selectedMiddlewares);
            $middlewareCode = ', [' . implode(', ', $middlewareClasses) . ']';
        }

        $controllerShortName = substr(strrchr($targetControllerClass, "\\"), 1);
        $handlerString = "'{$controllerShortName}@{$action}'";

        $code = sprintf(
            "\$router->%s('%s', %s%s);",
            strtolower($method),
            $uri,
            $handlerString,
            $middlewareCode
        );

        // 8. 预览与确认
        $this->io->section('预览生成的代码');
        $this->io->block($code, null, 'fg=black;bg=yellow', '  ', true);

        if (!$this->confirm('确认写入 routes/web.php 吗？')) {
            $this->io->text('操作已取消。');
            return self::SUCCESS;
        }

        // 9. 写入文件
        if ($targetGroupIndex === 'ROOT') {
            // 追加到末尾
            file_put_contents($routeFile, PHP_EOL . $code . PHP_EOL, FILE_APPEND);
        } else {
            // 插入到指定分组
            $group = $groups[$targetGroupIndex];
            $insertPos = $group['end'] - 1; // 插入到闭包结束大括号之前
            
            // 寻找合适的缩进
            // 简单起见，我们假设缩进是 4 个空格
            $indent = "    ";
            $codeWithIndent = PHP_EOL . $indent . $code;

            $newContent = substr_replace($fileContent, $codeWithIndent, $insertPos, 0);
            file_put_contents($routeFile, $newContent);
        }

        $this->success("路由已成功添加！");

        return self::SUCCESS;
    }

    private function scanRouteGroups(string $content): array
    {
        $groups = [];
        // 匹配 $router->group(['prefix' => '...'], function...) 
        // 这是一个简化正则，可能无法覆盖所有复杂写法
        if (preg_match_all('/\$router->group\(\s*(\[.*?\])\s*,\s*function\s*\(/s', $content, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[1] as $index => $match) {
                $arrayString = $match[0];
                $offset = $match[1];
                
                // 尝试解析 prefix
                $prefix = 'Unknown';
                if (preg_match("/'prefix'\s*=>\s*'([^']+)'/", $arrayString, $pMatch)) {
                    $prefix = $pMatch[1];
                }

                // 寻找闭包的起始大括号
                $closureStartPos = strpos($content, '{', $offset);
                if ($closureStartPos !== false) {
                    $endPos = $this->findClosureEnd($content, $closureStartPos);
                    if ($endPos !== false) {
                        $groups[] = [
                            'prefix' => $prefix,
                            'start' => $closureStartPos,
                            'end' => $endPos
                        ];
                    }
                }
            }
        }
        return $groups;
    }

    private function findClosureEnd($content, $startPos)
    {
        $balance = 0;
        $len = strlen($content);
        for ($i = $startPos; $i < $len; $i++) {
            $char = $content[$i];
            if ($char === '{') {
                $balance++;
            } elseif ($char === '}') {
                $balance--;
                if ($balance === 0) {
                    return $i;
                }
            }
        }
        return false;
    }

    private function scanControllers(): array
    {
        $controllers = [];
        foreach (glob(APP_ROOT . '/app/Controllers/*.php') as $file) {
            $className = basename($file, '.php');
            $fqcn = "App\\Controllers\\{$className}";
            $controllers[$className] = $fqcn;
        }
        return $controllers;
    }

    private function scanControllerMethods(string $controllerClass): array
    {
        if (!class_exists($controllerClass)) {
            $file = APP_ROOT . '/app/Controllers/' . substr(strrchr($controllerClass, "\\"), 1) . '.php';
            if (file_exists($file)) require_once $file;
        }

        if (!class_exists($controllerClass)) return [];

        $ref = new ReflectionClass($controllerClass);
        $methods = [];
        foreach ($ref->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (!str_starts_with($method->name, '__')) {
                $methods[] = $method->name;
            }
        }
        return $methods;
    }

    private function scanMiddlewares(): array
    {
        $middlewares = [];
        foreach (glob(APP_ROOT . '/app/Middleware/*.php') as $file) {
            $className = basename($file, '.php');
            $fqcn = "App\\Middleware\\{$className}";
            $middlewares[$className] = $fqcn;
        }
        $middlewares['CsrfMiddleware'] = 'Luminode\Core\Middleware\CsrfMiddleware';
        $middlewares['Authenticate'] = 'Luminode\Core\Middleware\Authenticate';
        $middlewares['LogRequestMiddleware'] = 'Luminode\Core\Middleware\LogRequestMiddleware';
        
        return $middlewares;
    }
}
