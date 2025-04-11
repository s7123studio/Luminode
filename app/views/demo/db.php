<?php $this->section('content') ?>
<div class="demo-container">
    <h1>数据库操作演示</h1>
    
    <div class="db-demo">
        <h2>从数据库获取的文章列表</h2>
        
        <?php if (!empty($posts)): ?>
        <table class="post-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>标题</th>
                    <th>创建时间</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= $post['id'] ?></td>
                    <td><?= $post['title'] ?></td>
                    <td><?= $post['created_at'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>没有找到文章数据</p>
        <?php endif; ?>
        
        <div class="demo-actions">
            <a href="/demo" class="btn">返回首页</a>
        </div>
    </div>
</div>
<?php $this->endSection() ?>
