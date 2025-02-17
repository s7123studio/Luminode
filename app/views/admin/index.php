<!DOCTYPE html>
<html>
<head>
    <title>设置</title>
    <style>
        body {
            background-image: url('public/images/Luminode_Ox2.webp');
            background-size: auto; /* Do not scale the image */
            background-position: bottom; /* Position the image at the bottom */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-attachment: fixed; /* The image will not move with the scrolling */
            margin: 0; /* Remove default margin */
            height: 100%; /* Full height */
            width: 100%; /* Full width */
        }
        /* Add additional styles for your form and other elements as needed */
    </style>
</head>
<body>
    <h1>设置</h1>
    <form action="/update_settings" method="post">
        <label for="siteName">网站名称:</label>
        <input type="text" id="siteName" name="siteName" value="<?php echo $siteName; ?>"><br><br>
        
        <label for="site_favicon">网站图标:</label>
        <input type="text" id="site_favicon" name="site_favicon" value="<?php echo $site_favicon; ?>"><br><br>
        
        <label for="site_description">网站描述:</label>
        <input type="text" id="site_description" name="site_description" value="<?php echo $site_description; ?>"><br><br>
        
        <label for="avatar_link">头像:</label>
        <input type="text" id="avatar_link" name="avatar_link" value="<?php echo $avatar_link; ?>"><br><br>
        
        <label for="signature">个性签名:</label>
        <input type="text" id="signature" name="signature" value="<?php echo $signature; ?>"><br><br>

        <label for="link_1_name">链接1 名称:</label>
        <input type="text" id="link_1_name" name="link_1_name" value="<?php echo $link_1_name; ?>"><br><br>

        <label for="link_1">链接1 地址:</label>
        <input type="text" id="link_1" name="link_1" value="<?php echo $link_1; ?>"><br><br>

        <label for="link_2_name">链接2 名称:</label>
        <input type="text" id="link_2_name" name="link_2_name" value="<?php echo $link_2_name; ?>"><br><br>

        <label for="link_2">链接2 地址:</label>
        <input type="text" id="link_2" name="link_2" value="<?php echo $link_2; ?>"><br><br>

        <label for="link_3_name">链接3 名称:</label>
        <input type="text" id="link_3_name" name="link_3_name" value="<?php echo $link_3_name; ?>"><br><br>

        <label for="link_3">链接3 地址:</label>
        <input type="text" id="link_3" name="link_3" value="<?php echo $link_3; ?>"><br><br>

        <label for="link_4_name">链接4 名称:</label>
        <input type="text" id="link_4_name" name="link_4_name" value="<?php echo $link_4_name; ?>"><br><br>

        <label for="link_4">链接4 地址:</label>
        <input type="text" id="link_4" name="link_4" value="<?php echo $link_4; ?>"><br><br>

        <label for="link_5_name">链接5 名称:</label>
        <input type="text" id="link_5_name" name="link_5_name" value="<?php echo $link_5_name; ?>"><br><br>

        <label for="link_5">链接5 地址:</label>
        <input type="text" id="link_5" name="link_5" value="<?php echo $link_5; ?>"><br><br>

        <label for="Copyright_date">版权起始时间：</label>
        <input type="text" id="Copyright_date" name="Copyright_date" value="<?php echo $Copyright_date; ?>"><br><br>

        <label for="Copyright_name">版权所有人：</label>
        <input type="text" id="Copyright_name" name="Copyright_name" value="<?php echo $Copyright_name; ?>"><br><br>

        <label for="Copyright_Customize">版权下方自定义：</label>
        <input type="text" id="Copyright_Customize" name="Copyright_Customize" value="<?php echo $Copyright_Customize; ?>"><br><br>

        <label for="QQ_link">QQ链接：</label>
        <input type="text" id="QQ_link" name="QQ_link" value="<?php echo $QQ_link; ?>"><br><br>
        
        <label for="Mail_link">邮箱链接：</label>
        <input type="text" id="Mail_link" name="Mail_link" value="<?php echo $Mail_link; ?>"><br><br>

        <label for="Money_link">赞赏链接/二维码：</label>
        <input type="text" id="Money_link" name="Money_link" value="<?php echo $Money_link; ?>"><br><br>

        <label for="Github_link">Github链接：</label>
        <input type="text" id="Github_link" name="Github_link" value="<?php echo $Github_link; ?>"><br><br>

        <label for="Gitee_link">Gitee链接：</label>
        <input type="text" id="Gitee_link" name="Gitee_link" value="<?php echo $Gitee_link; ?>"><br><br>

        <label for="Coolapk_link">酷安链接：</label>
        <input type="text" id="Coolapk_link" name="Coolapk_link" value="<?php echo $Coolapk_link; ?>"><br><br>

        <input type="submit" value="更新">
    </form>
</body>
</html>
