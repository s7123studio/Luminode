<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>body { font-family: sans-serif; line-height: 1.6; padding: 2em; } div { margin-bottom: 1em; }</style>
</head>
<body>
    <h1>Login</h1>
    <form action="/login" method="POST">
        <input type="hidden" name="_token" value="<?= \Luminode\Core\Middleware\CsrfMiddleware::generateToken() ?>">
        <div>
            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
