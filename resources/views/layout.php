<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>光枢框架 (Open Luminode)</title>
    <style>
        body { 
            background-color: #f4f6f9; 
            color: #333; 
            margin: 0; 
            font-family: "Microsoft YaHei", "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        .container { 
            padding: 2rem; 
        }
    </style>
</head>
<body>
    <div class="container">
        <?php $this->yieldContent('content'); ?>
    </div>
</body>
</html>
