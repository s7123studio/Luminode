<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>注册</title>
    <style>body { font-family: "Microsoft YaHei", sans-serif; line-height: 1.6; padding: 2em; } div { margin-bottom: 1em; }</style>
</head>
<body>
    <h1>注册</h1>
    <form action="/register" method="POST">
        <input type="hidden" name="_token" value="<?= \Luminode\Core\Middleware\CsrfMiddleware::generateToken() ?>">
        <div>
            <label for="username">用户名:</label><br>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="email">电子邮箱:</label><br>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="password">密码:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <label for="password_confirmation">确认密码:</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        <button type="submit">注册</button>
    </form>
</body>
</html>
