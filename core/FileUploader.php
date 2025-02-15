<?php

// 定义一个名为 FileUploader 的类
class FileUploader {
    // 定义一个静态方法 upload，用于处理文件上传
    // $file: 上传的文件信息，$allowedTypes: 允许的文件类型，默认为 ['image/jpeg', 'image/png']
    public static function upload($file, $allowedTypes = ['image/jpeg', 'image/png']) {
        // 检查上传的文件类型是否在允许的类型列表中
        if (!in_array($file['type'], $allowedTypes)) {
            // 如果文件类型不在允许列表中，抛出异常
            throw new Exception("文件类型不允许");
        }

        // 定义文件上传的目标目录
        $uploadDir = 'public/uploads/';
        // 生成一个唯一的文件名，避免文件名冲突
        $filename = uniqid().'_'.$file['name'];
        // 拼接目标文件路径
        $targetPath = $uploadDir.$filename;

        // 尝试将上传的文件移动到目标路径
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            // 如果文件移动失败，抛出异常
            throw new Exception("文件上传失败");
        }

        // 返回文件上传后的目标路径
        return $targetPath;
    }
}