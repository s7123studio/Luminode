<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <style>body { font-family: sans-serif; line-height: 1.6; padding: 2em; }</style>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($user['username'] ?? 'Guest') ?>!</h1>
    <p>This is your profile page. You can only see this if you are logged in.</p>
    <br>
    <form action="/logout" method="POST">
        <input type="hidden" name="_token" value="<?= \Luminode\Core\Middleware\CsrfMiddleware::generateToken() ?>">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
