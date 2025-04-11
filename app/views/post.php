<?php $this->extend('layout'); ?>

<?php $this->section('title'); ?>
<?php echo htmlspecialchars($post['title'] ?? '文章详情'); ?>
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<!-- 主容器 -->
<main class="container">
    <article class="post-content">
        <h1><?php echo htmlspecialchars($post['title'] ?? '无标题'); ?></h1>
        <div class="post-meta">
            <time><?php echo date('Y-m-d', strtotime($post['created_at'] ?? '')); ?></time>
        </div>
        <div class="post-body">
            <?php echo $post['content'] ?? '文章内容不存在'; ?>
        </div>
    </article>

    <a href="/" class="back-link">返回首页</a>
</main>
<?php $this->endSection(); ?>

<?php $this->section('scripts'); ?>
<style>
    .post-content {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 1rem;
        backdrop-filter: blur(10px);
    }
    
    .post-meta {
        color: #aaa;
        margin: 1rem 0;
        font-size: 0.9rem;
    }
    
    .post-body {
        line-height: 1.6;
        font-size: 1.1rem;
    }
    
    .back-link {
        display: block;
        text-align: center;
        margin: 2rem auto;
        color: #4a8bfc;
        text-decoration: none;
    }
</style>
<?php $this->endSection(); ?>
