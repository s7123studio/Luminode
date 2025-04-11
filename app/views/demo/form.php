<?php $this->section('content') ?>
<div class="demo-container">
    <h1>表单处理演示</h1>
    
    <div class="form-demo">
        <?php if (isset($success)): ?>
        <div class="alert success">
            <?= $success ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
        <div class="alert error">
            <?= $error ?>
        </div>
        <?php endif; ?>
        
        <form method="post" action="/demo/form">
            <div class="form-group">
                <label for="name">姓名</label>
                <input type="text" id="name" name="name" value="<?= $data['name'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" id="email" name="email" value="<?= $data['email'] ?? '' ?>">
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn">提交</button>
            </div>
        </form>
        
        <div class="demo-actions">
            <a href="/demo" class="btn">返回首页</a>
        </div>
    </div>
</div>
<?php $this->endSection() ?>
