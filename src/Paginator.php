<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:21:22
 */

namespace Luminode\Core;

use Luminode\Core\Database;

// 定义一个名为 Paginator 的类，用于处理分页逻辑
class Paginator {
    // 定义一个静态方法 paginate，用于执行分页查询
    public static function paginate($query, $perPage = 10) {
        // 从 GET 请求中获取当前页码，如果没有提供则默认为 1
        $page = $_GET['page'] ?? 1;
        // 计算当前页的偏移量，偏移量 = (当前页码 - 1) * 每页显示的条数
        $offset = ($page - 1) * $perPage;
        
        // 创建一个 Database 对象用于数据库操作
        $db = new Database();
        // 执行查询获取总记录数，使用子查询包装传入的查询语句
        $total = $db->fetch("SELECT COUNT(*) as total FROM ($query) as sub")['total'];
        // 执行分页查询，限制每页显示的条数和偏移量
        $results = $db->fetchAll("$query LIMIT $perPage OFFSET $offset");

        // 返回分页结果，包括数据、当前页码、总页数和分页链接
        return [
            'data' => $results, // 当前页的数据
            'current_page' => $page, // 当前页码
            'total_pages' => ceil($total / $perPage), // 总页数，向上取整
            'links' => self::generateLinks($page, ceil($total / $perPage)) // 分页链接
        ];
    }

    // 定义一个私有静态方法 generateLinks，用于生成分页链接
    private static function generateLinks($current, $total) {
        // 生成分页链接逻辑
    }
}
