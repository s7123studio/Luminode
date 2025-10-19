<?php $this->section('content'); ?>

<div style="text-align: center;">
    <h1 style="font-size: 3rem; color: #2c3e50;">欢迎使用 Open Luminode!</h1>
    <p style="font-size: 1.2rem; color: #555;">Active Record ORM 功能演示</p>
</div>

<div style="margin-top: 2rem; padding: 1rem 2rem; border: 1px solid #ddd; border-radius: 8px; background-color: #fff;">
    <h2>文章列表 (来自MySQL数据库)</h2>
    <hr>
    <?php if (empty($posts)): ?>
        <p>数据库中没有找到文章，或者数据库未正确配置。</p>
    <?php else: ?>
        <ul style="list-style: none; padding: 0;">
            <?php foreach ($posts as $post): ?>
                <li style="margin-bottom: 1.5rem; border-bottom: 1px solid #eee; padding-bottom: 1rem;">
                    <h3><?php echo $post->title; ?></h3>
                    <p><?php echo $post->content; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php $this->endSection(); ?>
