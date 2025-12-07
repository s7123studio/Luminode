<?php
/*
 * @Author: 7123
 * @Date: 2025-04-11 18:30:29
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:29:27
 */

namespace Luminode\Core;

use RuntimeException;
use Luminode\Core\Exceptions\ViewNotFoundException;

class Template
{
    protected $viewsPath;
    protected $layout = null;
    protected $sections = [];
    protected $currentSection;

    public function __construct($viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    public function render($view, $data = [])
    {
        // 每次渲染重置布局和区块，避免实例复用时污染
        $this->layout = null;
        $this->sections = [];
        $this->currentSection = null;

        // 提取视图数据
        extract($this->escape($data));

        // 开始捕获视图输出
        ob_start();
        include $this->resolvePath($view);
        $content = ob_get_clean();

        // 如果视图中定义了布局 (通过 $this->extend('layoutName'))
        if ($this->layout) {
            // 如果主缓冲有内容，且 'content' 区块未定义，则将其作为 content
            // 或者：如果你希望主内容追加到 content 中，可以调整逻辑
            // 这里我们只在 content 为空时填充它，避免覆盖 section('content') 的结果
            if (!isset($this->sections['content']) || empty($this->sections['content'])) {
                 $this->sections['content'] = $content;
            }

            // 渲染布局
            $layoutPath = $this->resolvePath($this->layout);
            if (file_exists($layoutPath)) {
                ob_start();
                include $layoutPath;
                return ob_get_clean();
            }
        }

        return $content;
    }

    public function extend($layout)
    {
        $this->layout = $layout;
    }

    public function section($name)
    {
        $this->currentSection = $name;
        ob_start();
    }

    public function endSection()
    {
        if (is_null($this->currentSection)) {
            throw new RuntimeException("无法结束区块：未开始任何区块。");
        }
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = null;
    }

    public function yieldContent($name, $default = '')
    {
        echo $this->sections[$name] ?? $default;
    }

    public function escape($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'escape'], $data);
        }

        if (is_object($data)) {
            return $data;
        }

        return htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8');
    }

    protected function resolvePath($view)
    {
        $path = $this->viewsPath . str_replace('.', '/', $view) . '.php';
        if (!file_exists($path)) {
            throw new ViewNotFoundException("未找到视图 [{$view}]，路径：{$path}");
        }
        return $path;
    }
}
