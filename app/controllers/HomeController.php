<?php

// 定义HomeController类，用于处理主页相关的请求
class HomeController {
    // index方法用于显示主页
    public function index() {
        // 创建Database对象，用于数据库操作
        $db = new Database();
        // 从数据库中获取所有帖子，并按创建时间降序排列
        $posts = $db->fetchAll("SELECT * FROM posts ORDER BY created_at DESC");
        // 查询网站名称
        $siteName = $db->fetch("SELECT value FROM settings WHERE `key` = 'sitename'")['value'];
        // 查询网站图标
        $site_favicon = $db->fetch("SELECT value FROM settings WHERE `key` = 'site_favicon'")['value'];
        // 查询网站描述/关键词
        $site_description = $db->fetch("SELECT value FROM settings WHERE `key` = 'site_description'")['value'];
        // 查询头像链接
        $avatar_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'avatar_link'")['value'];
        // 查询个性签名
        $signature = $db->fetch("SELECT value FROM settings WHERE `key` = 'signature'")['value'];
        // 查询链接名和链接
        $link_1_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_1_name'")['value'];
        $link_2_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_2_name'")['value'];
        $link_3_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_3_name'")['value'];
        $link_4_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_4_name'")['value'];
        $link_5_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_5_name'")['value'];
        $link_1 = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_1'")['value'];
        $link_2 = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_2'")['value'];
        $link_3 = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_3'")['value'];
        $link_4 = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_4'")['value'];
        $link_5 = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_5'")['value'];
        // 版权
        $Copyright_date = $db->fetch("SELECT value FROM settings WHERE `key` = 'Copyright_date'")['value'];
        $Copyright_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'Copyright_name'")['value'];
        // 版权下方自定义1
        $Copyright_Customize = $db->fetch("SELECT value FROM settings WHERE `key` = 'Copyright_Customize'")['value'];
        // 第三方链接

        // 查询QQ_link
        $qq_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'QQ_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $QQ_link
        if (!empty($qq_link_value)) {
            $QQ_link = "<a target=\"_blank\" href=\"$qq_link_value\"> <img src=\"images\svg\qq.svg\" width=\"30\" alt=\"QQ\" title=\"QQ\" />&nbsp;&nbsp;</a>";
        } else {
            // 否则保持 $QQ_link 为空
            $QQ_link = '';
        }

        // 查询Mail_link
        $mail_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Mail_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Mail_link
        if (!empty($mail_link_value)) {
            $Mail_link = "<a target=\"_blank\" href=\"mailto:$mail_link_value\"> <img src=\"images\svg\mail.svg\" width=\"30\" alt=\"Mail\" title=\"Mail\" />&nbsp;&nbsp;</a>";
        } else {
            // 否则保持 $Mail_link 为空
            $Mail_link = '';
        }

        // 查询Money_link
        $money_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Money_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Money_link
        if (!empty($money_link_value)) {
            $Money_link = "<a target=\"_blank\" href=\"$money_link_value\"> <img src=\"images\svg\money_receiving_QR_code.svg\" width=\"30\" alt=\"money\" title=\"money\" />&nbsp;&nbsp;</a>";
        } else {
            // 否则保持 $Money_link 为空
            $Money_link = '';
        }

        // 查询Github_link
        $github_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Github_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Github_link
        if (!empty($github_link_value)) {
            $Github_link = "<a target=\"_blank\" href=\"$github_link_value\"> <img src=\"images\svg\github.svg\" width=\"30\" alt=\"Github\" title=\"Github\" />&nbsp;&nbsp;</a>";
        } else {
            // 否则保持 $Github_link 为空
            $Github_link = '';
        }

        // 查询Gitee_link
        $gitee_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Gitee_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Gitee_link
        if (!empty($gitee_link_value)) {
            $Gitee_link = "<a target=\"_blank\" href=\"$gitee_link_value\"> <img src=\"images\svg\gitee.svg\" width=\"30\" alt=\"Gitee\" title=\"Gitee\" />&nbsp;&nbsp;</a>";
        } else {
            // 否则保持 $Gitee_link 为空
            $Gitee_link = '';
        }

        // 查询Coolapk_link
        $coolapk_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Coolapk_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Github_link
        if (!empty($coolapk_link_value)) {
            $Coolapk_link = "<a target=\"_blank\" href=\"$coolapk_link_value\"> <img src=\"images\svg\coolapk.svg\" width=\"30\" alt=\"Coolapk\" title=\"Coolapk\" />&nbsp;&nbsp;</a>";
        } else {
            // 否则保持 $Github_link 为空
            $Coolapk_link = '';
        }

        // 包含主页视图文件，将$posts和$siteName变量传递给视图
        include '../app/views/home.php';
    }
    
    // showPost方法用于显示单个帖子的详细内容
    public function showPost($id) {
        // 创建Database对象，用于数据库操作
        $db = new Database();
        // 从数据库中获取指定ID的帖子
        $post = $db->fetch("SELECT * FROM posts WHERE id = ?", [$id]);
        // 包含帖子视图文件，将$post变量传递给视图
        include '../app/views/post.php';
    }
}