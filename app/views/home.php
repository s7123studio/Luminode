<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<?php $this->extend('layout'); ?>

<?php $this->section('title'); ?>
<?php echo $settings['sitename']; ?>
<?php $this->endSection(); ?>

<?php $this->section('head'); ?>
<meta name="description" content="<?php echo $settings['site_description']; ?>">
<link rel="shortcut icon" href="<?php echo $settings['site_favicon']; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no">
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<button class="theme-toggle">
    <div class="sun-moon">
        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>
</button>

<!-- 主容器 -->
<main class="container">
    <!-- 头像区 -->
    <section class="avatar-section">
        <div class="avatar-wrapper">
            <img src="<?php echo $settings['avatar_link']; ?>" 
                 alt="<?php echo $settings['sitename']; ?>" 
                 class="avatar"
                 loading="lazy">
            <div class="avatar-glow"></div>
        </div>
    </section>

    <!-- 文字信息 -->
    <section class="text-section" style="font-family: 'HarmonyOS', sans-serif;">
        <h1 class="site-title animate-pop-in" ><?php echo $settings['sitename']; ?></h1>
        <p class="site-description animate-pop-in"><?php echo $settings['signature']; ?></p>
    </section>

    <!-- 主要链接 -->
    <nav class="main-links animate-fade-in" style="font-family: 'HarmonyOS', sans-serif;">
        <?php echo $settings['link_1'] ?? '' ?>
        <?php echo $settings['link_2'] ?? '' ?>
        <?php echo $settings['link_3'] ?? '' ?>
        <?php echo $settings['link_4'] ?? '' ?>
        <?php echo $settings['link_5'] ?? '' ?>
    </nav>

    <!-- 社交链接 -->
    <div class="social-links animate-fade-in" style="font-family: 'HarmonyOS', sans-serif;">
        <?php echo $settings['QQ_link'] ?? '' ?>
        <?php echo $settings['Mail_link'] ?? '' ?>
        <?php echo $settings['Money_link'] ?? '' ?>
        <?php echo $settings['Github_link'] ?? '' ?>
        <?php echo $settings['Gitee_link'] ?? '' ?>
        <?php echo $settings['BiLiBiLi_link'] ?? '' ?>
        <?php echo $settings['Coolapk_link'] ?? '' ?>
    </div>

    <!-- 版权信息 -->
    <footer class="footer animate-fade-in fixed-footer" style="font-family: 'HarmonyOS', sans-serif;">
        <p align="center" class="ip-address">您的IP：<span id="ip-address">正在获取...</span></p>
        <p align="center" class="copyright">
            <script>
                document.write(`Copyright © <?php echo $settings['Copyright_date']; ?> - ${new Date().getFullYear()} <?php echo $settings['Copyright_name'] ?>`);
            </script>
            <br>
            <?php echo $settings['Copyright_Customize']; ?>
        </p>
    </footer>
</main>

<!-- 背景元素 -->
<div class="background">
    <div class="custom-bg"></div>
    <div class="gradient-circle pink"></div>
    <div class="gradient-circle blue"></div>
</div>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<style>
    /* 自定义背景层 */
    .custom-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("<?php echo $settings['background_link']; ?>");
        background-size: cover;
        background-position: center;
        opacity: 0;
        transition: opacity 0.6s ease;
        z-index: -2;
    }
    /* 添加背景模糊选项 */
    .custom-bg {
        filter: blur(10px);
        transition: filter 0.4s ease;
    }

    /* 暗色遮罩 */
    .custom-bg::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        mix-blend-mode: multiply;
    }

    /* 亮色模式适配 */
    [data-theme="light"] .custom-bg::after {
        background: rgba(255, 255, 255, 0);
        mix-blend-mode: overlay;
    }
</style>
<?php $this->endSection(); ?>