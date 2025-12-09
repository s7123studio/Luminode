<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DownCommand extends BaseCommand
{
    protected static $defaultName = 'down';
    protected static $defaultDescription = '将应用置于维护模式';

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        $file = APP_ROOT . '/storage/framework/down';
        
        // 确保目录存在
        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }

        if (file_exists($file)) {
            $this->io->warning("应用已经处于维护模式！");
            return self::SUCCESS;
        }

        // 创建维护文件，内容可以是维护开始的时间
        file_put_contents($file, json_encode([
            'time' => time(),
            'message' => '系统正在维护中...'
        ]));

        $this->success("应用已进入维护模式。访问将返回 503 状态码。");

        return self::SUCCESS;
    }
}
