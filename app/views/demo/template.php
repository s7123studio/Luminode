<?php $this->section('content') ?>
<div class="demo-container">
    <h1><?= $sectionTitle ?></h1>
    
    <div class="template-demo">
        <div class="content-box">
            <?= $content ?>
        </div>
        
        <div class="template-features">
            <h2>模板引擎功能</h2>
            <ul>
                <li>布局继承</li>
                <li>内容区块</li>
                <li>自动HTML转义</li>
                <li>原生PHP语法</li>
            </ul>
        </div>
        
        <div class="demo-actions">
            <a href="/demo" class="btn">返回首页</a>
        </div>
    </div>
</div>
<?php $this->endSection() ?>
