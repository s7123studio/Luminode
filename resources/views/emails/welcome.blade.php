<!DOCTYPE html>
<html>
<head>
    <!-- 设置网页的标题，标题内容通过变量 $subject 动态生成 -->
    <title>{{ $subject }}</title>
</head>
<body>
    <!-- 显示欢迎信息 -->
    <h1>欢迎加入Luminode!</h1>
    <!-- 显示用户的验证码，验证码内容通过变量 $code 动态生成 -->
    <p>您的验证码是：{{ $code }}</p>
    <!-- 提示用户点击链接激活账户 -->
    <p>请点击以下链接激活账户：</p>
    <!-- 创建一个链接，链接的地址通过变量 $url 动态生成 -->
    <a href="{{ $url }}">立即激活</a>
</body>
</html>