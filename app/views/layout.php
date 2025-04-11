<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $this->yield('title'); ?></title>
    <?php $this->yield('head'); ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/main.css">
</head>
<body>
    <!-- 加载动画 -->
    <div class="loader">
        <div class="loader-inner">
            <div class="loader-circle"></div>
        </div>
    </div>
    
    <?php $this->yield('content'); ?>
    
    <script src="/assets/js/main.js"></script>
    <?php $this->yield('scripts'); ?>
</body>
</html>
