<?php

class GpuRankController {
    public function index() {
        // 获取数据库连接
        $db = new Database(); // 假设你的框架有一个 Database 类用于数据库操作

        // 执行查询
        $sql = "SELECT gpu_name, score, test_date FROM benchmarks ORDER BY score DESC LIMIT 100";
        $result = $db->query($sql);
        $rank = 1;

        // 处理查询结果
        $rankings = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $rankings[] = [
                    'rank' => $rank,
                    'gpu_name' => $row['gpu_name'],
                    'score' => number_format($row['score']),
                    'test_date' => $row['test_date']
                ];
                $rank++;
            }
        } else {
            $rankings = null;
        }

        // 渲染视图
        include '../app/views/gpu/rank.php';
    }
}
