<?php

namespace Luminode\Core;

use RuntimeException;
use Luminode\Core\Exceptions\ViewNotFoundException;

class Template
{
    protected $viewsPath;
    protected $layout = 'layout';
    protected $sections = [];
    protected $currentSection;

    public function __construct($viewsPath)
    {
        $this->viewsPath = rtrim($viewsPath, '/') . '/';
    }

    public function render($view, $data = [])
    {
        extract($this->escape($data));
        ob_start();
        include $this->resolvePath($view);
        $content = ob_get_clean();

        if ($this->layout) {
            $layout = $this->resolvePath($this->layout);
            if (file_exists($layout)) {
                ob_start();
                include $layout;
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
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = null;
    }

    public function yield($name)
    {
        echo $this->sections[$name] ?? '';
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
            throw new ViewNotFoundException("View [{$view}] not found at path: {$path}");
        }
        return $path;
    }
}
