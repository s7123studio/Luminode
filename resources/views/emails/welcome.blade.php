<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <h1>欢迎加入Luminode!</h1>
    <p>您的验证码是：{{ $code }}</p>
    <p>请点击以下链接激活账户：</p>
    <a href="{{ $url }}">立即激活</a>
</body>
</html>