<?php $this->section('content') ?>
<div class="demo-container">
    <h1><?= $title ?></h1>
    
    <div class="features">
        <h2>框架主要功能</h2>
        <ul>
            <?php foreach ($features as $feature): ?>
            <li><?= $feature ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="demo-links">
        <h2>功能演示</h2>
        <a href="/demo/db" class="btn">数据库操作演示</a>
        <a href="/demo/form" class="btn">表单处理演示</a>
        <a href="/demo/template" class="btn">模板引擎演示</a>
    </div>
</div>
<?php $this->endSection() ?>
