<?php

namespace Luminode\Core;

use RuntimeException;

class FileUploader {
    // 允许的文件类型和对应扩展名
    const ALLOWED_TYPES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'application/pdf' => 'pdf'
    ];

    // 默认配置
    private $config = [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'upload_dir' => 'public/uploads/',
        'overwrite' => false
    ];

    public function __construct(array $config = []) {
        $this->config = array_merge($this->config, $config);
        
        // 确保上传目录存在
        if (!is_dir($this->config['upload_dir'])) {
            mkdir($this->config['upload_dir'], 0755, true);
        }
    }

    public function upload($file) {
        // 验证基本错误
        $this->validateBasic($file);
        
        // 验证文件类型
        $extension = $this->validateType($file);
        
        // 验证文件大小
        $this->validateSize($file);
        
        // 生成安全文件名
        $filename = $this->generateFilename($file['name'], $extension);
        $targetPath = $this->getSafePath($filename);

        // 移动文件
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException("文件移动失败，请检查目录权限");
        }

        return [
            'path' => $targetPath,
            'name' => $filename,
            'size' => $file['size'],
            'type' => $file['type']
        ];
    }

    private function validateBasic($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException($this->getUploadError($file['error']));
        }
    }

    private function validateType($file) {
        // 验证MIME类型
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!isset(self::ALLOWED_TYPES[$mime])) {
            throw new RuntimeException("不支持的文件类型: {$mime}");
        }

        // 验证扩展名
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowedExt = self::ALLOWED_TYPES[$mime];
        
        if (strtolower($ext) !== $allowedExt) {
            throw new RuntimeException("文件扩展名不匹配");
        }

        return $ext;
    }

    private function validateSize($file) {
        if ($file['size'] > $this->config['max_size']) {
            throw new RuntimeException("文件大小超过限制");
        }
    }

    private function generateFilename($originalName, $ext) {
        $name = substr(md5_file($originalName . microtime()), 0, 12);
        return "{$name}.{$ext}";
    }

    private function getSafePath($filename) {
        $path = realpath($this->config['upload_dir']) . DIRECTORY_SEPARATOR . $filename;
        
        // 防止目录遍历
        if (strpos($path, realpath($this->config['upload_dir'])) !== 0) {
            throw new RuntimeException("无效的文件路径");
        }

        return $path;
    }

    private function getUploadError($code) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => '文件大小超过服务器限制',
            UPLOAD_ERR_FORM_SIZE => '文件大小超过表单限制',
            UPLOAD_ERR_PARTIAL => '文件只有部分被上传',
            UPLOAD_ERR_NO_FILE => '没有文件被上传',
            UPLOAD_ERR_NO_TMP_DIR => '找不到临时文件夹',
            UPLOAD_ERR_CANT_WRITE => '文件写入失败',
            UPLOAD_ERR_EXTENSION => '文件上传被扩展阻止'
        ];

        return $errors[$code] ?? '未知上传错误';
    }
}