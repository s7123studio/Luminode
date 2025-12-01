<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>个人资料</title>
    <style>body { font-family: "Microsoft YaHei", sans-serif; line-height: 1.6; padding: 2em; }</style>
</head>
<body>
    <h1>欢迎, <?= htmlspecialchars($user['username'] ?? '访客') ?>!</h1>
    <p>这是您的个人资料页面。只有在登录状态下才能看到此页面。</p>
    <br>
    <form action="/logout" method="POST">
        <input type="hidden" name="_token" value="<?= \Luminode\Core\Middleware\CsrfMiddleware::generateToken() ?>">
        <button type="submit">注销</button>
    </form>
</body>
</html>
