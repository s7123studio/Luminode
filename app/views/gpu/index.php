<!DOCTYPE html>
<html>
<head>
    <title>GPU性能测试</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .container { text-align: center; margin-top: 50px; }
        button { padding: 15px 30px; font-size: 18px; cursor: pointer; }
        #result { margin-top: 30px; display: none; }
        form { margin-top: 20px; }
        input[type="text"] { padding: 10px; width: 300px; }
        #loading { display: none; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>GPU性能测试</h1>
        <button id="startBtn" onclick="startBenchmark()">开始测试</button>
        <div id="loading">测试中... 请勿刷新页面</div>
        
        <div id="result">
            <h2>测试分数: <span id="scoreValue">0</span></h2>
            <form action="gpu/submit_score" method="post" onsubmit="return validateForm()">
                <input type="text" name="gpu_name" placeholder="输入您的显卡型号" required>
                <input type="hidden" name="score" id="finalScore">
                <br><br>
                <button type="submit">提交分数</button>
            </form>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="gpu/rank">查看排行榜</a>
        </div>
    </div>

    <script>
        let isTesting = false;
        let animationFrameId = null;
        let glContext = null;
        let testCanvas = null;
        
        // 测试参数
        const WARMUP_FRAMES = 15;   // 预热帧数
        const TEST_FRAMES = 50;     // 有效测试帧数
        const MAX_FRAME_TIME = 50;  // 最大有效帧时间(ms)

        const HIGH_PRESSURE_KERNEL = `
            float kernel(vec3 ver) {
                vec3 a = ver;
                float dist = 0.0;
                for(int i=0; i<18; i++){  // 增加数学复杂度
                    float r = length(a);
                    float theta = atan(a.y, a.x) * 12.0;
                    float phi = acos(a.z/r) * 12.0;
                    r = pow(r, 10.0);
                    a = ver + vec3(
                        r * sin(phi) * cos(theta),
                        r * sin(phi) * sin(theta),
                        r * cos(phi)
                    );
                    if(r > 12.0) break;
                    dist += 0.1/(0.1 + r*r);  // 累积距离计算
                }
                return 12.0 - dist*24.0 - length(a);
            }
        `;

        function cleanupResources() {
            // 取消动画帧请求
            if(animationFrameId) {
                cancelAnimationFrame(animationFrameId);
                animationFrameId = null;
            }

            // 释放WebGL资源
            if(glContext) {
                const gl = glContext;
                try {
                    gl.getExtension('WEBGL_lose_context')?.loseContext();
                    gl.bindBuffer(gl.ARRAY_BUFFER, null);
                    gl.bindTexture(gl.TEXTURE_2D, null);
                } catch(e) {
                    console.warn('资源释放异常:', e);
                }
                glContext = null;
            }

            // 移除测试画布
            if(testCanvas) {
                document.body.removeChild(testCanvas);
                testCanvas = null;
            }

            isTesting = false;
        }

        function calculateScore(frameTimes) {
            // 数据清洗和统计分析
            const validFrames = frameTimes
                .slice(WARMUP_FRAMES)              // 排除预热帧
                .filter(t => t < MAX_FRAME_TIME);  // 排除异常帧
                // 转换帧时间为FPS并过滤异常值
            const fpsArray = frameTimes
                .map(t => t > 0 ? 1000/t : 0)
                .filter(fps => fps >= TEST_CONFIG.MIN_VALID_FPS && fps <= TEST_CONFIG.MAX_VALID_FPS);

                        // 统计分析法排除异常
            const mean = fpsArray.reduce((a,b) => a+b) / fpsArray.length;
            const stdDev = Math.sqrt(
                fpsArray.map(fps => Math.pow(fps - mean, 2)).reduce((a,b) => a+b) / fpsArray.length
            );
            const validFPS = fpsArray.filter(fps => 
            Math.abs(fps - mean) <= TEST_CONFIG.OUTLIER_THRESHOLD * stdDev
            );
            if(validFrames.length < TEST_FRAMES/2) {
                return 0;  // 有效数据不足
            }

            // 使用截尾均值计算（去掉最高和最低10%）
            const sorted = [...validFrames].sort((a,b) => a-b);
            const trimCount = Math.floor(sorted.length * 0.1);
            const trimmed = sorted.slice(trimCount, -trimCount);
            
            const avg = trimmed.reduce((a,b) => a+b) / trimmed.length;
            return Math.floor(1000000 / avg);
            const stabilityFactor = 1 - (stdDev / mean);
            return Math.round(mean * stabilityFactor * 1000);
            
        }

        function startBenchmark() {
            if(isTesting) return;
            isTesting = true;
            
            // 清理前次资源
            cleanupResources();

            // 初始化界面
            document.getElementById('startBtn').disabled = true;
            document.getElementById('loading').style.display = 'block';
            document.getElementById('result').style.display = 'none';

            try {
                // 创建隐藏画布
                testCanvas = document.createElement('canvas');
                testCanvas.width = 2560;  // 2.5K分辨率
                testCanvas.height = 1440;
                testCanvas.style.cssText = 'position:fixed;left:-9999px;';
                document.body.appendChild(testCanvas);

                // 初始化WebGL
                const gl = testCanvas.getContext('webgl', {
                    antialias: false,
                    powerPreference: "high-performance"
                });
                glContext = gl;

                // 编译着色器
                const vertexShader = gl.createShader(gl.VERTEX_SHADER);
                gl.shaderSource(vertexShader, `
                    attribute vec4 position;
                    void main() { gl_Position = position; }
                `);
                gl.compileShader(vertexShader);

                const fragmentShader = gl.createShader(gl.FRAGMENT_SHADER);
                gl.shaderSource(fragmentShader, `
                    precision highp float;
                    uniform vec2 resolution;
                    ${HIGH_PRESSURE_KERNEL}

                    void main() {
                        vec3 rayPos = vec3(gl_FragCoord.xy/resolution, 0.0);
                        float t = 0.0;
                        for(int i=0; i<256; i++){  // 增加光线步进次数
                            float d = kernel(rayPos);
                            if(d < 0.0001) break;
                            t += d * 0.3;
                            rayPos += vec3(0.1, 0.1, 1.0) * d;
                        }
                        gl_FragColor = vec4(vec3(t*0.15), 1.0);
                    }
                `);
                gl.compileShader(fragmentShader);

                // 创建渲染程序
                const program = gl.createProgram();
                gl.attachShader(program, vertexShader);
                gl.attachShader(program, fragmentShader);
                gl.linkProgram(program);
                gl.useProgram(program);

                // 设置顶点缓冲
                const buffer = gl.createBuffer();
                gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
                gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([
                    -1, -1, 1, -1, -1, 1, 1, 1
                ]), gl.STATIC_DRAW);

                const position = gl.getAttribLocation(program, 'position');
                gl.enableVertexAttribArray(position);
                gl.vertexAttribPointer(position, 2, gl.FLOAT, false, 0, 0);

                // 性能测量
                let frameCount = 0;
                const frameTimes = [];
                const startTime = performance.now();

                const measureFrame = () => {
                    try {
                        const frameStart = performance.now();
                        
                        // 执行渲染
                        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
                        gl.finish();
                        
                        frameTimes.push(performance.now() - frameStart);

                        // 更新进度
                        const progress = Math.min(100, (frameCount / (WARMUP_FRAMES + TEST_FRAMES)) * 100);
                        document.getElementById('loading').textContent = 
                            `测试中... ${progress.toFixed(0)}%`;

                        if(++frameCount < WARMUP_FRAMES + TEST_FRAMES) {
                            animationFrameId = requestAnimationFrame(measureFrame);
                        } else {
                            // 计算最终分数
                            const score = calculateScore(frameTimes);
                            
                            document.getElementById('scoreValue').textContent = score;
                            document.getElementById('finalScore').value = score;
                            document.getElementById('result').style.display = 'block';
                            
                            cleanupResources();
                            document.getElementById('loading').style.display = 'none';
                            document.getElementById('startBtn').disabled = false;
                        }
                    } catch(e) {
                        console.error('测试异常:', e);
                        cleanupResources();
                        alert('测试中断: ' + e.message);
                    }
                };

                animationFrameId = requestAnimationFrame(measureFrame);
            } catch(e) {
                cleanupResources();
                alert('初始化失败: ' + e.message);
            }
        }

        function validateForm() {
            const gpuName = document.querySelector('input[name="gpu_name"]').value.trim();
            if(!gpuName) {
                alert('请输入显卡型号');
                return false;
            }
            return true;
        }

        // 页面关闭时清理资源
        window.addEventListener('beforeunload', cleanupResources);
    </script>
</body>
</html>