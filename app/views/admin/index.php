<?php require_once '../core/Middleware/CSRF.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>设置面板 - Luminode</title>
    <link rel="stylesheet" href="/assets/css/admin_style.css">
</head>
<body>
    <?php if (!Auth::check()): ?>
        <div class="settings-card">
            <form class="login-form" action="/login" method="post">
                <h2 class="settings-title">欢迎回来</h2>
                <input type="hidden" name="csrf_token" value="<?php echo CSRF::generateToken(); ?>">
                
                <div class="form-group">
                    <input type="text" name="username" class="input-field" placeholder=" ">
                    <label class="input-label">用户名：</label>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" class="input-field" placeholder=" ">
                    <label class="input-label">密码：</label>
                </div>
                
                <button type="submit" class="btn">立即登录</button>
            </form>
        </div>
    <?php else: ?>
        <div class="settings-card">
            <h1 class="settings-title">系统设置</h1>
            <form action="/update_settings" method="post" class="form-grid">
                <!-- 第一列 -->
                <div class="form-group">
                    <input type="text" id="siteName" name="siteName" class="input-field" 
                           value="<?php echo $siteName; ?>" placeholder=" ">
                    <label class="input-label">网站名称：</label>
                </div>
                
                <div class="form-group">
                    <input type="text" id="site_favicon" name="site_favicon" class="input-field"
                           value="<?php echo $site_favicon; ?>" placeholder=" ">
                    <label class="input-label">网站图标：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="site_description" name="site_description" class="input-field"
                           value="<?php echo $site_description; ?>" placeholder=" ">
                    <label class="input-label">网站描述：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="avatar_link" name="avatar_link" class="input-field"
                           value="<?php echo $avatar_link; ?>" placeholder=" ">
                    <label class="input-label">头像:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="signature" name="signature" class="input-field"
                           value="<?php echo $signature; ?>" placeholder=" ">
                    <label class="input-label">个性签名:</label>
                </div>

                <div class="form-group"></div>
                <h2 class="settings-title" style="font-size: 1.8rem;">链接</h2>
                <div class="form-group"></div>

                <div class="form-group">
                    <input type="text" id="link_1_name" name="link_1_name" class="input-field"
                           value="<?php echo $link_1_name; ?>" placeholder=" ">
                    <label class="input-label">链接1 名称:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_1" name="link_1" class="input-field"
                           value="<?php echo $link_1; ?>" placeholder=" ">
                    <label class="input-label">链接1 地址:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_2_name" name="link_2_name" class="input-field"
                           value="<?php echo $link_2_name; ?>" placeholder=" ">
                    <label class="input-label">链接2 名称:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_2" name="link_2" class="input-field"
                           value="<?php echo $link_2; ?>" placeholder=" ">
                    <label class="input-label">链接2 地址:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_3_name" name="link_3_name" class="input-field"
                           value="<?php echo $link_3_name; ?>" placeholder=" ">
                    <label class="input-label">链接3 名称:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_3" name="link_3" class="input-field"
                           value="<?php echo $link_3; ?>" placeholder=" ">
                    <label class="input-label">链接3 地址:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_4_name" name="link_4_name" class="input-field"
                           value="<?php echo $link_4_name; ?>" placeholder=" ">
                    <label class="input-label">链接4 名称:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_4" name="link_4" class="input-field"
                           value="<?php echo $link_4; ?>" placeholder=" ">
                    <label class="input-label">链接4 地址:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_5_name" name="link_5_name" class="input-field"
                           value="<?php echo $link_5_name; ?>" placeholder=" ">
                    <label class="input-label">链接5 名称:</label>
                </div>

                <div class="form-group">
                    <input type="text" id="link_5" name="link_5" class="input-field"
                           value="<?php echo $link_5; ?>" placeholder=" ">
                    <label class="input-label">链接5 地址:</label>
                </div>

                <h2 class="settings-title" style="font-size: 1.8rem;">版权信息</h2>
                <div class="form-group"></div>
                <div class="form-group">
                    <input type="text" id="Copyright_date" name="Copyright_date" class="input-field"
                           value="<?php echo $Copyright_date; ?>" placeholder=" ">
                    <label class="input-label">版权起始时间：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Copyright_name" name="Copyright_name" class="input-field"
                           value="<?php echo $Copyright_name; ?>" placeholder=" ">
                    <label class="input-label">版权所有人：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Copyright_Customize" name="Copyright_Customize" class="input-field"
                           value="<?php echo $Copyright_Customize; ?>" placeholder=" ">
                    <label class="input-label">版权下方自定义：</label>
                </div>

                <div class="form-group"></div>
                <h2 class="settings-title" style="font-size: 1.8rem;">第三方链接</h2>
                <div class="form-group"></div>

                <div class="form-group">
                    <input type="text" id="QQ_link" name="QQ_link" class="input-field"
                           value="<?php echo $QQ_link; ?>" placeholder=" ">
                    <label class="input-label">QQ链接：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Mail_link" name="Mail_link" class="input-field"
                           value="<?php echo $Mail_link; ?>" placeholder=" ">
                    <label class="input-label">邮箱链接：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Money_link" name="Money_link" class="input-field"
                           value="<?php echo $Money_link; ?>" placeholder=" ">
                    <label class="input-label">赞赏链接/二维码：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Github_link" name="Github_link" class="input-field"
                           value="<?php echo $Github_link; ?>" placeholder=" ">
                    <label class="input-label">Github链接：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Gitee_link" name="Gitee_link" class="input-field"
                           value="<?php echo $Gitee_link; ?>" placeholder=" ">
                    <label class="input-label">Gitee链接：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="BiLiBiLi_link" name="BiLiBiLi_link" class="input-field"
                           value="<?php echo $BiLiBiLi_link; ?>" placeholder=" ">
                    <label class="input-label">Bilibili链接：</label>
                </div>

                <div class="form-group">
                    <input type="text" id="Coolapk_link" name="Coolapk_link" class="input-field"
                           value="<?php echo $Coolapk_link; ?>" placeholder=" ">
                    <label class="input-label">酷安链接：</label>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <button type="submit" class="btn">保存所有设置</button>
                </div>
            </form>
        </div>

        <div class="settings-card">
            <h2 class="settings-title" style="font-size: 1.8rem;">安全设置</h2>
            <form action="/update_settings" method="post">
                <input type="hidden" name="username" value="<?php echo $_SESSION['user']; ?>">
                
                <div class="form-group">
                    <input type="password" name="new_password" class="input-field" 
                           placeholder=" " required>
                    <label class="input-label">新密码</label>
                </div>
                
                <button type="submit" class="btn">更新密码</button>
            </form>
        </div>
    <?php endif; ?>

    <script src="/assets/js/admin_script.js"></script>
</body>
</html>