<?php

// 定义AdminController类，用于处理主页相关的请求
class AdminController {
    // index方法用于显示主页
    public function index() {
        // 创建Database对象，用于数据库操作
        $db = new Database();
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
        // 查询背景图片链接
        $background_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'background_link'")['value'];
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
        $QQ_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'QQ_link'")['value'];
        $Mail_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Mail_link'")['value'];
        $Money_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Money_link'")['value'];
        $Github_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Github_link'")['value'];
        $Gitee_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Gitee_link'")['value'];
        $BiLiBiLi_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'BiLiBiLi_link'")['value'];
        $Coolapk_link = $db->fetch("SELECT value FROM settings WHERE `key` = 'Coolapk_link'")['value'];

        // 包含主页视图文件，将$posts和$siteName变量传递给视图
        include '../app/views/admin/index.php';
    }
}