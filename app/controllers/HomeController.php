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
        $link_1_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_1'")['value'];
        if (!empty($link_1_value)) {
            $link_1 = "<a href=\"$link_1_value\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span>";
        } else {
            // 否则保持 $link_1 为空
            $link_1 = '';
        }

        $link_2_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_2'")['value'];
        if (!empty($link_2_value)) {
            $link_2 = "<a href=\"$link_2_value\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span>";
        } else {
            // 否则保持 $link_2 为空
            $link_2 = '';
        }

        $link_3_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_3'")['value'];
        if (!empty($link_3_value)) {
            $link_3 = "<a href=\"$link_3_value\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span>";
        } else {
            // 否则保持 $link_3 为空
            $link_3 = '';
        }

        $link_4_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_4'")['value'];
        if (!empty($link_4_value)) {
            $link_4 = "<a href=\"$link_4_value\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span>";
        } else {
            // 否则保持 $link_4 为空
            $link_4 = '';
        }

        $link_5_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_5'")['value'];
        if (!empty($link_5_value)) {
            $link_5 = "<a href=\"$link_5_value\"class=\"link-item\"><span class=\"holographic-effect\"></span><span class=\"blur-layer\"></span>
            <span class=\"harmony-glow\"></span>";
        } else {
            // 否则保持 $link_5 为空
            $link_5 = '';
        }

        $link_1_name_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_1_name'")['value'];
        if (!empty($link_1_name_value)) {
            $link_1_name = "<span class=\"label\">$link_1_name_value</span></a>";
        } else {
            // 否则保持 $link_1_name 为空
            $link_1_name = '';
        }
        $link_2_name_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_2_name'")['value'];
        if (!empty($link_2_name_value)) {
            $link_2_name = "<span class=\"label\">$link_2_name_value</span></a>";
        } else {
            // 否则保持 $link_1_name 为空
            $link_2_name = '';
        }

        $link_3_name_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_3_name'")['value'];
        if (!empty($link_3_name_value)) {
            $link_3_name = "<span class=\"label\">$link_3_name_value</span></a>";
        } else {
            // 否则保持 $link_1_name 为空
            $link_3_name = '';
        }

        $link_4_name_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_4_name'")['value'];
        if (!empty($link_4_name_value)) {
            $link_4_name = "<span class=\"label\">$link_4_name_value</span></a>";
        } else {
            // 否则保持 $link_1_name 为空
            $link_4_name = '';
        }

        $link_5_name_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'link_5_name'")['value'];
        if (!empty($link_5_name_value)) {
            $link_5_name = "<span class=\"label\">$link_5_name_value</span></a>";
        } else {
            // 否则保持 $link_1_name 为空
            $link_5_name = '';
        }

        // 版权
        $Copyright_date = $db->fetch("SELECT value FROM settings WHERE `key` = 'Copyright_date'")['value'];
        $Copyright_name = $db->fetch("SELECT value FROM settings WHERE `key` = 'Copyright_name'")['value'];
        // 版权下方自定义1
        $Copyright_Customize = $db->fetch("SELECT value FROM settings WHERE `key` = 'Copyright_Customize'")['value'];
        // 第三方链接

        //旧版样式
/*         // 查询QQ_link
        $QQ_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'QQ_link'")['value'];
        // 查询Mail_link
        $Mail_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Mail_link'")['value'];
        // 查询Money_link
        $Money_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Money_link'")['value'];
        // 查询Github_link
        $Github_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Github_link'")['value'];
        // 查询Gitee_link
        $Gitee_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Gitee_link'")['value'];
        // 查询Coolapk_link
        $Coolapk_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Coolapk_link'")['value']; */

        // 查询QQ_link
        $qq_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'QQ_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $QQ_link
        if (!empty($qq_link_value)) {
            $QQ_link = "<a class=\"social-item\" target=\"_blank\" href=\"$qq_link_value\"> <img src=\"images\svg\qq.svg\" width=\"30\" alt=\"QQ\" title=\"QQ\" /></a>";
        } else {
            // 否则保持 $QQ_link 为空
            $QQ_link = '';
        }

        // 查询Mail_link
        $mail_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Mail_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Mail_link
        if (!empty($mail_link_value)) {
            $Mail_link = "<a class=\"social-item\" target=\"_blank\" href=\"mailto:$mail_link_value\"><img src=\"images\svg\Mail.svg\" width=\"30\" alt=\"Mail\" title=\"Mail\" /></a>";
        } else {
            // 否则保持 $Mail_link 为空
            $Mail_link = '';
        }

        // 查询Money_link
        $money_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Money_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Money_link
        if (!empty($money_link_value)) {
            $Money_link = "<a class=\"social-item\" target=\"_blank\" href=\"$money_link_value\"> <img src=\"images\svg\Money.svg\" width=\"30\" alt=\"money\" title=\"money\" /></a>";
        } else {
            // 否则保持 $Money_link 为空
            $Money_link = '';
        }

        // 查询Github_link
        $github_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Github_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Github_link
        if (!empty($github_link_value)) {
            $Github_link = "<a class=\"social-item\" target=\"_blank\" href=\"$github_link_value\"> <img src=\"images\svg\Github.svg\" width=\"30\" alt=\"Github\" title=\"Github\" /></a>";
        } else {
            // 否则保持 $Github_link 为空
            $Github_link = '';
        }

        // 查询Gitee_link
        $gitee_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Gitee_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Gitee_link
        if (!empty($gitee_link_value)) {
            $Gitee_link = "<a class=\"social-item\" target=\"_blank\" href=\"$gitee_link_value\"> <img src=\"images\svg\Gitee.svg\" width=\"30\" alt=\"Gitee\" title=\"Gitee\" /></a>";
        } else {
            // 否则保持 $Gitee_link 为空
            $Gitee_link = '';
        }

        // 查询BiLiBiLi_link
        $bilibili_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'BiLiBiLi_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $BiLiBiLi_link
        if (!empty($bilibili_link_value)) {
            $BiLiBiLi_link = "<a class=\"social-item\" target=\"_blank\" href=\"$bilibili_link_value\"> <img src=\"images\svg\BiLiBiLi.svg\" width=\"30\" alt=\"BiLiBiLi\" title=\"BiLiBiLi\" /></a>";
        } else {
            // 否则保持 $BiLiBiLi_link 为空
            $BiLiBiLi_link = '';
        }

        // 查询Coolapk_link
        $coolapk_link_value = $db->fetch("SELECT value FROM settings WHERE `key` = 'Coolapk_link'")['value'];
        // 如果查询到的值不为空，则将其赋值给 $Coolapk_link
        if (!empty($coolapk_link_value)) {
            $Coolapk_link = "<a class=\"social-item\" target=\"_blank\" href=\"$coolapk_link_value\"> <img src=\"images\svg\Coolapk.svg\" width=\"30\" alt=\"Coolapk\" title=\"Coolapk\" /></a>";
        } else {
            // 否则保持 $Coolapk_link 为空
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