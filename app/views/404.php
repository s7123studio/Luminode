<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>页面未找到 - 404</title>
    <style>
        :root {
            --primary-color: #5A8BFF;
            --glass-bg: rgba(255, 255, 255, 0.1);
            --blur-intensity: 20px;
            --text-color: #1A1A1A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-user-select: none;
            user-select: none;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'HarmonyOS Sans', sans-serif;
            background: #f0f2f5;
            color: var(--text-color);
            position: relative;
            overflow: hidden;
        }

        /* 动态背景光效 */
        .background-glow {
            position: fixed;
            width: 200vw;
            height: 200vh;
            background: radial-gradient(
                circle at 50% 50%,
                rgba(90, 139, 255, 0.1),
                transparent 60%
            );
            filter: blur(80px);
            animation: glow-rotate 20s linear infinite;
            z-index: -1;
        }

        @keyframes glow-rotate {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .container {
            text-align: center;
            padding: 2rem;
            background: var(--glass-bg);
            border-radius: 24px;
            backdrop-filter: blur(var(--blur-intensity));
            box-shadow: 0 16px 40px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.1);
            transform-style: preserve-3d;
            position: relative;
        }

        .error-code {
            font-size: 8rem;
            color: var(--primary-color);
            text-shadow: 0 4px 16px rgba(90,139,255,0.3);
            margin-bottom: 1rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .error-text {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .error-text::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            width: 60px;
            height: 2px;
            background: var(--primary-color);
            transform: translateX(-50%);
            animation: line-glow 2s infinite;
        }

        @keyframes line-glow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        .back-btn {
            padding: 1rem 2.5rem;
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .back-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255,255,255,0.2),
                transparent
            );
            animation: btn-glow 3s infinite linear;
        }

        @keyframes btn-glow {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(90,139,255,0.3);
        }

        /* 光标特效 */
        .cursor-effect {
            position: fixed;
            width: 20px;
            height: 20px;
            border: 2px solid var(--primary-color);
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            animation: cursor-pulse 1.5s infinite;
        }

        @keyframes cursor-pulse {
            0%, 100% { 
                transform: translate(-50%, -50%) scale(1);
                opacity: 0.3;
            }
            50% { 
                transform: translate(-50%, -50%) scale(1.8);
                opacity: 0.1;
            }
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 5rem;
            }
            
            .error-text {
                font-size: 1.2rem;
            }
            
            .back-btn {
                padding: 0.8rem 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-glow"></div>
    <div class="cursor-effect"></div>

    <div class="container">
        <div class="error-code">404</div>
        <div class="error-text">页面已进入量子领域</div>
        <button class="back-btn" onclick="window.history.back()">返回安全区域</button>
    </div>

    <script>
        // 光标跟随效果
        const cursor = document.querySelector('.cursor-effect');
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = `${e.clientX}px`;
            cursor.style.top = `${e.clientY}px`;
        });

        // 动态背景互动
        document.addEventListener('mousemove', (e) => {
            const glow = document.querySelector('.background-glow');
            const x = e.clientX / window.innerWidth * 30;
            const y = e.clientY / window.innerHeight * 30;
            glow.style.transform = `translate(calc(-50% + ${x}px), calc(-50% + ${y}px))`;
        });
    </script>
</body>
</html>