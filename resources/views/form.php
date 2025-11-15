<?php
use Luminode\Core\Middleware\CsrfMiddleware;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF Demo Form</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 2em; }
        form { border: 1px solid #ccc; padding: 1em; border-radius: 5px; }
        button { padding: 0.5em 1em; }
        hr { margin: 2em 0; }
        code { background: #eee; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>CSRF Protection Demo</h1>
    <p>This form is protected by CSRF middleware.</p>
    
    <form action="/handle-form" method="POST">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="<?= CsrfMiddleware::generateToken() ?>">
        
        <div>
            <label for="data">Some Data:</label>
            <input type="text" id="data" name="some_data" value="Hello World">
        </div>
        
        <br>
        
        <button type="submit">Submit (Success)</button>
    </form>
    
    <hr>

    <h3>How to Test Failure</h3>
    <p>To see the protection in action, you can try two things:</p>
    <ol>
        <li>Use your browser's developer tools to change the <code>value</code> of the hidden <code>_token</code> input field to something random, then submit.</li>
        <li>Create a separate HTML file on your local machine with the same form but without the <code>_token</code> field, and submit it.</li>
    </ol>
    <p>In both cases, the request should be blocked by the middleware, and you should see an error page.</p>

</body>
</html>
