<?php

try {
/*     // 创建表结构
    $db->query("CREATE TABLE IF NOT EXISTS celestial_bodies (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        semi_major_axis DOUBLE,
        eccentricity DOUBLE,
        inclination DOUBLE,
        perihelion_time DATETIME,
        orbital_period DOUBLE,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 插入/更新小行星数据
    $sql = "REPLACE INTO celestial_bodies 
        (name, semi_major_axis, eccentricity, inclination, perihelion_time, orbital_period)
        VALUES (?, ?, ?, ?, ?, ?)";
    $params = [
        '2024YR4',
        (0.85 + 4.18) / 2,  // 半长轴
        0.662,
        3.408,
        '2024-11-15 00:00:00',
        3.99 * 365.25  // 转换为天数
    ];
    $db->query($sql, $params); */

    // 获取轨道参数
    $asteroid = $db->fetch("SELECT * FROM celestial_bodies WHERE name = '2024YR4'");
    $earth = [
        'semi_major_axis' => 1.00000261,
        'eccentricity' => 0.0167086,
        'inclination' => 0.00005,  // 微小倾角
        'perihelion_time' => '2024-01-02 06:00:00',  // 2024年地球近日点精确时间
        'orbital_period' => 365.256363004
    ];

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}


// 倒计时计算
$target = new DateTime('2032-12-22 00:00:00', new DateTimeZone('UTC'));
$now = new DateTime('now', new DateTimeZone('UTC'));
$interval = $now->diff($target);
?>
<!DOCTYPE html>
<html>
<head>
    <title>2024YR4跟踪</title>
    <style>
        @font-face {
            font-family: 'zihun59hao-chuangcuhei';
            src: url('https://cdn.s7123.xyz/zihun59hao-chuangcuhei.woff2') format('woff2');
        }
        @font-face {
            font-family: 'DIN1451LTW06-Mittelschrift';
            src: url('https://cdn.s7123.xyz/DIN1451LTW06-Mittelschrift.woff2') format('woff2');
        }
        body.black-background {
            background-color: black;
        }
        #timer {
            text-align: center;
            font-size: 2em;
            margin: 20px;
            font-family: 'zihun59hao-chuangcuhei', monospace !important;
        }
        .date-font {
            font-family: 'DIN1451LTW06-Mittelschrift', monospace !important;
        }
        canvas {
            display: block;
            margin: 20px auto;
            background: #000;
        }
        /* 底部图片和文字样式 */
        .footer-image {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .luminode-logo {
            opacity: 0.45;
            max-width: 100%;
            height: auto;
        }        

        .footer-text {
            margin-top: 10px;
            font-size: 1em;
            color: white;
        }
    </style>
</head>
<body class="black-background">
    <div id="timer">加载中...</div>
    <canvas id="orbitalCanvas" width="800" height="750"></canvas>

    <script>
        // 天文单位到像素的转换比例 (1 AU = 100 pixels)
        const AU_SCALE = 150;
        const CANVAS_CENTER = 400;
        // 精确位置计算（轨道倾角修正）
        function getAsteroidPosition(time) {
            const t = (time - asteroidParams.t0) / 864e5;
            const n = 2 * Math.PI / asteroidParams.T;  // 平均角速度
            const M = n * t % (2 * Math.PI);
            const E = solveKepler(M, asteroidParams.e);
    
            // 真近点角计算（使用atan2提高精度）
            const cosE = Math.cos(E);
            const sinE = Math.sin(E);
            const tan_v2 = Math.sqrt((1 + asteroidParams.e)/(1 - asteroidParams.e)) * Math.tan(E/2);
            const v = 2 * Math.atan(tan_v2);

            // 三维坐标变换
            const r = asteroidParams.a * (1 - asteroidParams.e**2) / (1 + asteroidParams.e * Math.cos(v));
            const x = r * (cosE - asteroidParams.e);
            const y = r * Math.sqrt(1 - asteroidParams.e**2) * sinE;
    
            // 轨道面旋转（黄道坐标系转换）
            const cosi = Math.cos(asteroidParams.i);
            const sini = Math.sin(asteroidParams.i);
            return {
                x: x * cosi - y * sini,
                y: x * sini + y * cosi
            };
        }

        // 地球位置计算
        function getEarthPosition(time) {
            const t = (time - earthParams.t0) / 864e5;
            const M = (2 * Math.PI * t) / earthParams.T;
            const E = solveKepler(M, earthParams.e);
            
            const x = earthParams.a * (Math.cos(E) - earthParams.e);
            const y = earthParams.a * Math.sqrt(1 - earthParams.e**2) * Math.sin(E);
            return {x, y};
        }
        // 从PHP获取参数
        const asteroidParams = {
            a: <?= $asteroid['semi_major_axis'] ?>,
            e: <?= $asteroid['eccentricity'] ?>,
            i: <?= $asteroid['inclination'] * Math.PI / 180 ?>,
            T: <?= $asteroid['orbital_period'] ?>,
            t0: new Date('<?= $asteroid['perihelion_time'] ?> UTC').getTime()
        };

        const earthParams = {
            a: <?= $earth['semi_major_axis'] ?>,
            e: <?= $earth['eccentricity'] ?>,
            T: <?= $earth['orbital_period'] ?>,
            t0: new Date('<?= $earth['perihelion_time'] ?> UTC').getTime()
        };

        // 实时倒计时更新
        function updateTimer() {
            const target = new Date('2032-12-22T00:00:00Z').getTime();
            const now = Date.now();
            const diff = target - now;

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (864e5)) / 36e5);
            const minutes = Math.floor((diff % 36e5) / 6e4);
            const seconds = Math.floor((diff % 6e4) / 1000);

            document.getElementById('timer').style.color = 'red';
            document.getElementById('timer').innerHTML = 
                `<span class="date-font">${days}</span>天 - <span class="date-font">${hours}</span>小时 - <span class="date-font">${minutes}</span>分钟 - <span class="date-font">${seconds}</span>秒`;
        }
        setInterval(updateTimer, 1000);
        updateTimer();

        // 天体位置计算
        function solveKepler(M, e, iterations=100) {
            let E = M;
            for(let i = 0; i < iterations; i++) {
                const delta = E - e * Math.sin(E) - M;
                if(Math.abs(delta) < 1e-9) break;
                E -= delta / (1 - e * Math.cos(E));
            }
            return E;
        }

        function getOrbitPosition(params, time) {
            const t = (time - params.t0) / (864e5); // 转换为天数
            const M = (2 * Math.PI * t) / params.T;
            const E = solveKepler(M, params.e);
            
            // 计算真近点角
            const v = 2 * Math.atan2(
                Math.sqrt(1 + params.e) * Math.sin(E/2),
                Math.sqrt(1 - params.e) * Math.cos(E/2)
            );

            // 计算笛卡尔坐标
            const r = params.a * (1 - params.e**2) / (1 + params.e * Math.cos(v));
            return {
                x: r * Math.cos(v),
                y: r * Math.sin(v)
            };
        }

        // 绘图系统
        const canvas = document.getElementById('orbitalCanvas');
        const ctx = canvas.getContext('2d');
        
        function drawBody(x, y, color, size=5) {
            ctx.beginPath();
            ctx.arc(
                CANVAS_CENTER + x * AU_SCALE,
                CANVAS_CENTER - y * AU_SCALE,  // 反转Y轴
                size, 0, 2 * Math.PI
            );
            ctx.fillStyle = color;
            ctx.fill();
        }

        function drawOrbit(params, color) {
            ctx.beginPath();
            const a = params.a * AU_SCALE;
            const b = a * Math.sqrt(1 - params.e**2);
            
            ctx.save();
            ctx.translate(CANVAS_CENTER, CANVAS_CENTER);
            ctx.rotate(params.i || 0);
            
            ctx.ellipse(0, 0, a, b, 0, 0, 2 * Math.PI);
            ctx.strokeStyle = color + '66';
            ctx.stroke();
            ctx.restore();
        }

        function updateSimulation() {
            ctx.clearRect(0, 0, 800, 800);
            
            // 绘制太阳
            drawBody(0, 0, 'yellow', 10);
            
            // 绘制轨道
            drawOrbit(earthParams, 'blue');
            drawOrbit(asteroidParams, 'red');
            
            // 计算并绘制天体位置
            const now = Date.now();
            
            // 地球位置（精确计算）
            const earthPos = getEarthPosition(now);
            drawBody(earthPos.x, earthPos.y, 'blue');
            
            // 小行星位置（轨道倾斜）
            const asteroidPos = getAsteroidPosition(now);
            const rotatedX = asteroidPos.x * Math.cos(asteroidParams.i) - asteroidPos.y * Math.sin(asteroidParams.i);
            const rotatedY = asteroidPos.x * Math.sin(asteroidParams.i) + asteroidPos.y * Math.cos(asteroidParams.i);
            drawBody(asteroidPos.x, asteroidPos.y, 'red');

            // 精确距离计算（三维空间距离）
            const dx = earthPos.x - asteroidPos.x;
            const dy = earthPos.y - asteroidPos.y;
            const dz = 0;  // 忽略Z轴分量（二维显示）
            const distance = Math.sqrt(dx*dx + dy*dy + dz*dz).toFixed(3);

            ctx.fillStyle = 'white';
            ctx.font = '14px Arial';
            ctx.fillText(`与地球的距离: ${distance} AU (实时)`, 10, 20);
        }

        // 启动模拟
        setInterval(updateSimulation, 1000);
        updateSimulation();
    </script>
    
    <div class="footer-image">
      <img src="../images/Luminode_990x170.png" alt="Luminode Logo" class="luminode-logo" width="220" height="auto">
      <p class="footer-text" style="font-size: 0.9em;">由<a href="https://luminode.s7123.xyz/" target="_blank" style="color: inherit; text-decoration: none;">Luminode</a>强力驱动</p>
    </div>

</body>
</html>
