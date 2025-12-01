<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <style>body { font-family: "Microsoft YaHei", sans-serif; line-height: 1.6; padding: 2em; } div { margin-bottom: 1em; }</style>
</head>
<body>
    <h1>登录</h1>
    <form action="/login" method="POST">
        <input type="hidden" name="_token" value="<?= \Luminode\Core\Middleware\CsrfMiddleware::generateToken() ?>">
        <div>
            <label for="username">用户名:</label><br>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">密码:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">登录</button>
    </form>
</body>
</html>
