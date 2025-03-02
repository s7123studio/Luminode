<!DOCTYPE html>
<html>
<head>
    <title>GPU排行榜</title>
    <style>
        table { border-collapse: collapse; width: 80%; margin: 20px auto; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <h1 style="text-align: center;">GPU性能排行榜</h1>
    <table>
        <tr>
            <th>排名</th>
            <th>显卡名称</th>
            <th>分数</th>
            <th>测试时间</th>
        </tr>
        <?php echo $rankings; ?>
    </table>
    <div style="text-align: center; margin-top: 20px;">
        <a href="index.html">返回测试</a>
    </div>
</body>
</html>