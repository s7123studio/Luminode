<?php
use Luminode\Core\Middleware\CsrfMiddleware;
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>CSRF 演示表单</title>
    <style>
        body { font-family: "Microsoft YaHei", sans-serif; line-height: 1.6; padding: 2em; }
        form { border: 1px solid #ccc; padding: 1em; border-radius: 5px; }
        button { padding: 0.5em 1em; }
        hr { margin: 2em 0; }
        code { background: #eee; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>CSRF 保护演示</h1>
    <p>此表单受到 CSRF 中间件的保护。</p>
    
    <form action="/handle-form" method="POST">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="<?= CsrfMiddleware::generateToken() ?>">
        
        <div>
            <label for="data">输入数据:</label>
            <input type="text" id="data" name="some_data" value="你好，世界">
        </div>
        
        <br>
        
        <button type="submit">提交 (成功)</button>
    </form>
    
    <hr>

    <h3>如何测试失败情况</h3>
    <p>要查看保护机制的效果，您可以尝试以下两种方法：</p>
    <ol>
        <li>使用浏览器的开发者工具将隐藏的 <code>_token</code> 输入框的 <code>value</code> 值更改为任意随机字符，然后提交。</li>
        <li>在您的本地机器上创建一个单独的 HTML 文件，包含相同的表单但去除 <code>_token</code> 字段，然后提交。</li>
    </ol>
    <p>在这两种情况下，请求都应被中间件拦截，您将看到一个错误页面。</p>

</body>
</html>
