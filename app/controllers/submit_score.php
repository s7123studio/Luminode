<?php

// 检查请求方法是否为 POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取并处理表单数据
    $gpu_name = htmlspecialchars($_POST['gpu_name']);
    $score = intval($_POST['score']);

    // 创建数据库对象
    $db = new Database();

    // 准备 SQL 语句
    $sql = "INSERT INTO benchmarks (gpu_name, score) VALUES (?, ?)";
    
    // 执行 SQL 语句
    $result = $db->query($sql, [$gpu_name, $score]);

    // 检查执行结果
    if ($result) {
        // 成功插入数据，重定向到排名页面
        header("Location: /rank");
        exit();
    } else {
        // 插入数据失败，输出错误信息
        echo "Error: " . $db->errorInfo();
    }
}
?>
