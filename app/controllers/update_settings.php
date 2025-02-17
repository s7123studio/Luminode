<?php
// 创建一个 Database 对象用于数据库操作
$db = new Database();

// 获取表单数据
$siteName = $_POST['siteName'];
$site_favicon = $_POST['site_favicon'];
$site_description = $_POST['site_description'];
$avatar_link = $_POST['avatar_link'];
$signature = $_POST['signature'];
$link_1_name = $_POST['link_1_name'];
$link_1_url = $_POST['link_1_url'];
$link_2_name = $_POST['link_2_name'];
$link_2_url = $_POST['link_2_url'];
$link_3_name = $_POST['link_3_name'];
$link_3_url = $_POST['link_3_url'];
$link_4_name = $_POST['link_4_name'];
$link_4_url = $_POST['link_4_url'];
$link_5_name = $_POST['link_5_name'];
$link_5_url = $_POST['link_5_url'];
$Copyright_date = $_POST['Copyright_date'];
$Copyright_name = $_POST['Copyright_name'];
$Copyright_Customize = $_POST['Copyright_Customize'];
$QQ_link = $_POST['QQ_link'];
$Email_link = $_POST['Email_link'];
$Money_link = $_POST['Money_link'];
$Github_link = $_POST['Github_link'];
$Gitee_link = $_POST['Gitee_link'];
$Coolapk_link = $_POST['Coolapk_link'];

// 更新数据库
$db->query("UPDATE settings SET value = ? WHERE `key` = 'sitename'", [$siteName]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'site_favicon'", [$site_favicon]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'site_description'", [$site_description]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'avatar_link'", [$avatar_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'signature'", [$signature]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_1_name'", [$link_1_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_1_url'", [$link_1_url]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_2_name'", [$link_2_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_2_url'", [$link_2_url]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_3_name'", [$link_3_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_3_url'", [$link_3_url]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_4_name'", [$link_4_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_4_url'", [$link_4_url]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_5_name'", [$link_5_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'link_5_url'", [$link_5_url]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Copyright_date'", [$Copyright_date]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Copyright_name'", [$Copyright_name]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Copyright_Customize'", [$Copyright_Customize]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'QQ_link'", [$QQ_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Email_link'", [$Email_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Money_link'", [$Money_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Github_link'", [$Github_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Gitee_link'", [$Gitee_link]);
$db->query("UPDATE settings SET value = ? WHERE `key` = 'Coolapk_link'", [$Coolapk_link]);
header("Location: /admin/");
exit;
