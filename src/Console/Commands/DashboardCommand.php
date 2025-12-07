<?php

namespace Luminode\Core\Console\Commands;

use Luminode\Core\Console\BaseCommand;
use Luminode\Core\Router;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArrayInput;
use PDO;

class DashboardCommand extends BaseCommand
{
    protected static $defaultName = 'dashboard';
    protected static $defaultDescription = '显示项目仪表盘与统计信息';

    private Router $router;

    public function __construct(Router $router)
    {
        parent::__construct();
        $this->router = $router;
    }

    protected function handle(InputInterface $input, OutputInterface $output): int
    {
        // 1. 炫酷开场
        $this->printLogo();
        $this->io->title("🖥️  Luminode 控制中心");

        // 2. 系统加载动画
        $this->runStartupAnimation($output);

        // 3. 展示概况 (包含 DB 检查)
        $this->renderSystemOverview();

        // 4. 展示项目统计
        $this->renderProjectStats($output);

        // 5. 待办事项 (TODOs)
        $this->renderTodos($output);

        // 6. Git 状态
        $this->renderGitStatus($output);

        // 7. 每日一句
        $this->io->newLine();
        $this->io->block($this->getRandomQuote(), '每日禅语', 'fg=black;bg=cyan', '  ', true);

        // 8. 交互式菜单
        return $this->runInteractiveMenu($output);
    }

    private function runStartupAnimation($output)
    {
        $output->writeln("正在初始化核心系统...");
        $progressBar = new ProgressBar($output, 100);
        $progressBar->setFormat('debug');
        $progressBar->start();

        for ($i = 0; $i <= 100; $i += 5) {
            if ($i == 20) $progressBar->setMessage('连接数据库...');
            if ($i == 50) $progressBar->setMessage('扫描技术债 (TODOs)...');
            if ($i == 80) $progressBar->setMessage('分析 Git 时间线...');
            $progressBar->advance(5);
            usleep(5000); 
        }
        $progressBar->finish();
        $this->io->newLine(2);
    }

    private function renderSystemOverview()
    {
        $this->io->section('📊 系统概况');
        
        // 检查数据库
        $dbStatus = '<fg=red>未连接</>';
        try {
            if (function_exists('config')) {
                $config = config('database.connections.' . config('database.default'));
                // 简单尝试连接
                if ($config) {
                    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                    $pdo = new PDO($dsn, $config['username'], $config['password']);
                    $dbStatus = '<fg=green>已连接</> (' . $config['database'] . ')';
                }
            }
        } catch (\Throwable $e) {
            $dbStatus = '<fg=red>连接失败</>';
        }

        $this->io->definitionList(
            ['PHP 版本' => PHP_VERSION],
            ['内存占用' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB'],
            ['数据库'   => $dbStatus],
            ['当前时间' => date('Y-m-d H:i:s')]
        );
    }

    private function renderProjectStats($output)
    {
        $stats = $this->collectStats();
        $this->io->section('📁 项目情报');
        
        $table = new Table($output);
        $table->setHeaders(['组件', '数量', '状态']);
        
        $routeStatus = $stats['routes'] > 0 ? '<fg=green>已加载</>' : '<fg=yellow>空闲</>';

        $table->setRows([
            ['Controllers', $stats['controllers'], '<fg=green>正常</>'],
            ['Models',      $stats['models'],      '<fg=green>活跃</>'],
            ['Views',       $stats['views'],       '<fg=green>就绪</>'],
            ['Routes',      $stats['routes'],      $routeStatus],
        ]);
        $table->render();
    }

    private function renderTodos($output)
    {
        $todos = $this->scanTodos(APP_ROOT . '/app');
        if (!empty($todos)) {
            $this->io->section('📝 待办事项 (TODOs)');
            $table = new Table($output);
            $table->setHeaders(['类型', '文件', '内容']);
            
            foreach (array_slice($todos, 0, 5) as $todo) {
                $type = $todo['type'] === 'FIXME' ? "<fg=red>FIXME</>" : "<fg=yellow>TODO</>";
                $table->addRow([$type, basename($todo['file']) . ':' . $todo['line'], substr($todo['message'], 0, 50)]);
            }
            $table->render();
            
            if (count($todos) > 5) {
                $this->io->text("... 以及其他 " . (count($todos) - 5) . " 项");
            }
        }
    }

    private function renderGitStatus($output)
    {
        if (is_dir(APP_ROOT . '/.git')) {
            $status = shell_exec('cd ' . APP_ROOT . ' && git status -s');
            if (!empty($status)) {
                $this->io->section('🌳 Git 变更');
                $lines = explode("\n", trim($status));
                foreach (array_slice($lines, 0, 5) as $line) {
                    $output->writeln("  " . $line);
                }
                if (count($lines) > 5) {
                    $output->writeln("  ... 以及其他 " . (count($lines) - 5) . " 个文件");
                }
            }
        }
    }

    private function runInteractiveMenu(OutputInterface $output): int
    {
        while (true) {
            $this->io->newLine();
            $choice = $this->io->choice('您想做什么？', [
                'serve' => '启动开发服务器',
                'doctor' => '系统诊断',
                'make:controller' => '创建控制器',
                'make:model' => '创建模型',
                'make:route' => '创建路由 (New!)',
                'route:list' => '查看路由',
                'log:clear' => '清理日志',
                'exit' => '退出'
            ], 'exit');

            if ($choice === 'exit') {
                $this->io->writeln("👋 再见！");
                break;
            }

            $command = $this->getApplication()->find($choice);
            
            // 特殊处理需要参数的命令，这里我们依赖命令自身的交互性
            $input = new ArrayInput([]);
            $command->run($input, $output);
        }

        return self::SUCCESS;
    }

    private function scanTodos(string $dir): array
    {
        $results = [];
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($files as $file) {
            if ($file->isDir() || $file->getExtension() !== 'php') continue;
            
            $content = file_get_contents($file->getPathname());
            if (preg_match_all('/(TODO|FIXME):\s*(.*)/i', $content, $matches, PREG_OFFSET_CAPTURE)) {
                foreach ($matches[1] as $index => $match) {
                    // 计算行号
                    $offset = $matches[0][$index][1];
                    $line = substr_count(substr($content, 0, $offset), "\n") + 1;
                    
                    $results[] = [
                        'type' => strtoupper($match[0]),
                        'message' => trim($matches[2][$index][0]),
                        'file' => $file->getPathname(),
                        'line' => $line
                    ];
                }
            }
        }
        return $results;
    }

    private function collectStats(): array
    {
        $countFiles = function ($path) {
            if (!is_dir($path)) return 0;
            return count(glob($path . '/*.php'));
        };

        $router = $this->router;
        if (file_exists(APP_ROOT . '/routes/web.php')) {
            // 抑制可能的重复定义错误
            // @require_once APP_ROOT . '/routes/web.php'; 
            // 实际上，因为 Dashboard 是长运行进程，重复 require 可能有问题
            // 但如果是第一次运行没问题。
            // 更好的方式是依赖 Router 实例已经加载的状态，或者只加载一次。
            // 为了安全，我们检查 Router 是否已经有路由了
            if (empty($this->router->getRoutes())) {
                 require_once APP_ROOT . '/routes/web.php';
            }
        }
        
        $routeCount = count($this->router->getRoutes());

        return [
            'controllers' => $countFiles(APP_ROOT . '/app/Controllers'),
            'models'      => $countFiles(APP_ROOT . '/app/Models'),
            'views'       => $countFiles(APP_ROOT . '/resources/views'),
            'routes'      => $routeCount
        ];
    }

    private function getRandomQuote(): string
    {
        $quotes = [
            "这世界上只有两样东西是无限的：宇宙和人类的愚蠢。 —— 爱因斯坦",
            "Talk is cheap. Show me the code. —— Linus Torvalds",
            "完美的代码是不存在的，但我们可以无限接近它。",
            "喝杯咖啡，放松一下。Bug 会自己跑出来的（并没有）。",
            "简单是可靠的先决条件。 —— Edsger Dijkstra",
            "不要重复造轮子，除非你想造一个更圆的。",
            "程序必须是写给人读的，只是顺便能让机器执行。",
            "过早的优化是万恶之源。",
            "Stay hungry, Stay foolish.",
            "今天你 Commit 了吗？",
            "你背朝太阳，就只能看到自己的影子。",
            "代码就像爱情，需要精心维护，却总会出人意料。",
            "Hello, World! 今天也要用心开始。",
            "最深的Bug往往藏在最简单的逻辑里。",
            "优雅的代码不是没有Bug，而是让人一眼看出Bug在哪。",
            "编程如修行，键盘为木鱼，Bug是心魔。",
            "重构是一场与过去自己的对话。",
            "不要害怕重写，凤凰浴火才能重生。",
            "优秀的程序员写代码，伟大的程序员删代码。",
            "当你的代码能运行的那一刻，宇宙会悄悄为你调整了一个参数。",
            "耐心是开发者最好的调试工具。",
            "最好的注释是干净的代码本身。",
            "程序员最讨厌两件事：写文档，和别人的代码没有文档。",
            "编程的本质是管理复杂度。 —— Edsger Dijkstra",
            "每一次Commit都是对未来的承诺。",
            "代码的沉默比千行注释更有力量。",
            "最危险的Bug是那些你以为不存在的Bug。",
            "编程如登山，重要的不是顶峰，而是沿途的风景。",
            "当所有测试都通过时，你应该怀疑测试本身。",
            "保持代码的年轻秘诀：少写代码，多思考。",
            "任何足够先进的技术都等同于魔术。 —— 亚瑟·克拉克",
            "我没有失败，我只是找到了一万种行不通的方法。 —— 托马斯·爱迪生",
            "想象力比知识更重要。 —— 阿尔伯特·爱因斯坦",
            "简单，就是终极的复杂。 —— 列奥纳多·达·芬奇",
            "预测未来的最好方式，就是去创造它。 —— 艾伦·凯",
            "唯一值得恐惧的，是恐惧本身。 —— 富兰克林·罗斯福",
            "我思故我在。 —— 勒内·笛卡尔",
            "活着就是为了改变世界，难道还有其他原因吗？ —— 史蒂夫·乔布斯",
            "艺术的目的不是再现可见，而是使不可见成为可见。 —— 保罗·克利",
            "算法不是用来计算的，而是用来思考的。 —— 高德纳",
            "逻辑会让你从A到B，想象力会带你到任何地方。 —— 爱因斯坦",
            "如果我看得更远，那是因为站在巨人的肩膀上。 —— 艾萨克·牛顿",
            "代码如诗，简洁方显力量。",
            "我们选择登月，不是因为它容易，而是因为它困难。 —— 约翰·肯尼迪",
            "计算机没什么用，它们只会告诉你答案。 —— 巴勃罗·毕加索",
            "真正的大师，永远怀着一颗学徒的心。 —— 李小龙",
            "有时候，提出问题比解决问题更重要。 —— 爱因斯坦",
            "机器应该工作，人类应该思考。 —— IBM口号",
            "未来已经到来，只是分布不均。 —— 威廉·吉布森",
            "不管黑猫白猫，能捉老鼠的就是好猫。 —— 邓小平",
            "虚心使人进步，骄傲使人落后。 —— 毛泽东",
            "为中华之崛起而读书。 —— 周恩来",
            "梦想还是要有的，万一实现了呢？ —— 马云",
            "拥抱变化，享受成长。 —— 马化腾",
            "用科技让复杂的世界更简单。 —— 李彦宏",
            "真正的对手，是你自己。 —— 姚明",
            "认真做事，踏实做人。 —— 曹德旺",
            "创新不是推翻过去，而是更好地延续。 —— 任正非",
            "热爱，是所有的理由和答案。 —— 郎朗",
            "工匠精神就是做好你认为值得的事。 —— 罗振宇",
            "把简单的事做到极致，就是绝招。 —— 董明珠",
            "读书是最好的心灵旅行。 —— 梁晓声",
            "选择比努力更重要，方向比速度更重要。 —— 雷军",
            "一个人的价值，在于他贡献了什么。 —— 袁隆平",
            "用作品说话，时间会给出答案。 —— 莫言",
            "想象力是人类最美的天赋。 —— 刘慈欣",
            "专业，就是持续做好简单的事。 —— 王石",
            "科技的本质是让人更幸福，而不是更忙碌。 —— 张一鸣",
            "持续学习，是应对变化的唯一方法。 —— 李开复",
            "真正的强大，是让自己变得更好。 —— 李宁",
            "用平常心做非凡事。 —— 丁磊",
            "创造价值，就是最好的成功。 —— 王兴",
            "面向阳光，阴影才会落在身后。",
            "大海不缺一滴水，但每一滴水都向往大海。",
            "山不向我走来，我便向山走去。",
            "种子破土之前，总要先向下扎根。",
            "风可以吹灭蜡烛，也能让篝火更旺。",
            "溪流不怕曲折，它只信最终汇入江河。",
            "竹子用四年长三厘米，第五年却每天三十厘米。",
            "真正的航行者，不是看停靠多少港口，而是穿越多少风暴。",
            "夜晚越黑，星星越亮。",
            "瀑布之所以壮观，是因为它没有退路。",
            "你定义方向，风只会改变速度，不能改变终点。",
            "石阶问佛像：为什么万人跪你？佛像说：因我承受千刀万刻，你却只挨了一刀。",
            "门槛——跨过去是门，跨不过是坎。",
            "灯塔从不寻找船只，它只是亮着。",
            "云层之上的天空永远晴朗。"
        ];

        return $quotes[array_rand($quotes)];
    }
}
