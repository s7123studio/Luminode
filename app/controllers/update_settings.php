<?php
// 创建一个 Database 对象用于数据库操作
$db = new Database();

// 获取表单数据
$siteName = $_POST['siteName']; // 网站名称
$site_favicon = $_POST['site_favicon']; // 网站favicon图标
$site_description = $_POST['site_description']; // 网站描述
$avatar_link = $_POST['avatar_link']; // 头像链接
$signature = $_POST['signature']; // 个性签名
$link_1_name = $_POST['link_1_name']; // 链接1名称
$link_1 = $_POST['link_1']; // 链接1 URL
$link_2_name = $_POST['link_2_name']; // 链接2名称
$link_2 = $_POST['link_2']; // 链接2 URL
$link_3_name = $_POST['link_3_name']; // 链接3名称
$link_3 = $_POST['link_3']; // 链接3 URL
$link_4_name = $_POST['link_4_name']; // 链接4名称
$link_4 = $_POST['link_4']; // 链接4 URL
$link_5_name = $_POST['link_5_name']; // 链接5名称
$link_5 = $_POST['link_5']; // 链接5 URL
$Copyright_date = $_POST['Copyright_date']; // 版权日期
$Copyright_name = $_POST['Copyright_name']; // 版权名称
$Copyright_Customize = $_POST['Copyright_Customize']; // 版权自定义信息
$QQ_link = $_POST['QQ_link']; // QQ链接
$Email_link = $_POST['Email_link']; // 邮箱链接
$Money_link = $_POST['Money_link']; // 赞助链接
$Github_link = $_POST['Github_link']; // Github链接
$Gitee_link = $_POST['Gitee_link']; // Gitee链接
$BiLiBiLi_link = $_POST['BiLiBiLi_link']; // bilibili链接
$Coolapk_link = $_POST['Coolapk_link']; // Coolapk链接
$newPassword = $_POST['new_password']; // 新密码
$background_link = $_POST['background_link']; // 背景链接
// 获取当前用户信息
$user = $_SESSION['user'];
// 更新密码，如果是空的就不更新
if (!empty($newPassword)) {
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT); // 对新密码进行哈希处理
    $db->query("UPDATE users SET password = ? WHERE username = ?", [$hashedPassword, $user]); // 更新用户密码
    $user['password'] = $hashedPassword; // 更新会话中的用户密码信息
    $_SESSION['user'] = $user; // 更新会话中的用户信息
}
// 更新数据库，如果是空的就不更新
if (!empty($siteName)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'sitename'", [$siteName]);
}
if (!empty($site_favicon)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'site_favicon'", [$site_favicon]);
}
if (!empty($site_description)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'site_description'", [$site_description]);
}
if (!empty($avatar_link)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'avatar_link'", [$avatar_link]);
}
if (!empty($signature)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'signature'", [$signature]);
}
$db->query("UPDATE settings SET value = ? WHERE `key` = 'background_link'", [$background_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_1_name'", [$link_1_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_1'", [$link_1]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_2_name'", [$link_2_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_2'", [$link_2]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_3_name'", [$link_3_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_3'", [$link_3]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_4_name'", [$link_4_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_4'", [$link_4]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_5_name'", [$link_5_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_5'", [$link_5]);
if (!empty($Copyright_date)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'Copyright_date'", [$Copyright_date]);
}
if (!empty($Copyright_name)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'Copyright_name'", [$Copyright_name]);
}
if (!empty($Copyright_Customize)) {
    $db->query("UPDATE settings SET value = ? WHERE `key` = 'Copyright_Customize'", [$Copyright_Customize]);
}
$db->query("UPDATE settings SET value = ? WHERE `key` = 'QQ_link'", [$QQ_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Email_link'", [$Email_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Money_link'", [$Money_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Github_link'", [$Github_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Gitee_link'", [$Gitee_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'BiLiBiLi_link'", [$BiLiBiLi_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Coolapk_link'", [$Coolapk_link]);

header("Location: /admin"); // 重定向到管理员页面
exit; // 结束脚本执行
