// 加载完成后隐藏加载动画
window.addEventListener('load', () => {
    document.querySelector('.loader').style.opacity = '0';
    document.querySelector('.custom-bg').style.opacity = '1'; 
    setTimeout(() => {
        document.querySelector('.loader').style.display = 'none';
    }, 500);

    // 初始化动画观察者
    initAnimations();
    // 检测是否为移动设备，并移除 .fixed-footer 类
    if (isMobile) {
        document.querySelector('footer').classList.remove('fixed-footer');
    }
});

// IP获取
fetch('https://api.s7123.xyz/cityjson.php')
    .then(response => response.json())
    .then(data => {
        document.getElementById('ip-address').textContent = data.cip;
    })
    .catch(() => {
        document.getElementById('ip-address').textContent = '获取失败';
    });

// 交互动画
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px'
    };

    const animateElements = document.querySelectorAll('.animate-pop-in, .animate-fade-in');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, observerOptions);

    animateElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        observer.observe(element);
    });
}

// 优化光效位置更新
document.querySelectorAll('.link-item').forEach(button => {
    const glow = button.querySelector('.harmony-glow');
    let targetX = 50, currentX = 50;
    let targetY = 50, currentY = 50;

    button.addEventListener('mousemove', (e) => {
        const rect = button.getBoundingClientRect();
        targetX = ((e.clientX - rect.left) / rect.width) * 100;
        targetY = ((e.clientY - rect.top) / rect.height) * 100;
    });

    function animate() {
        currentX += (targetX - currentX) * 0.15;
        currentY += (targetY - currentY) * 0.15;
        
        glow.style.setProperty('--glow-x', `${currentX}%`);
        glow.style.setProperty('--glow-y', `${currentY}%`);
        
        requestAnimationFrame(animate);
    }
    animate();
});
// 背景光效交互
document.addEventListener('mousemove', (e) => {
    const x = e.clientX / window.innerWidth;
    const y = e.clientY / window.innerHeight;
    
    document.documentElement.style.setProperty('--harmony-glow', 
        `radial-gradient(circle at ${x * 100}% ${y * 100}%, rgba(90, 140, 255, 0.60), transparent 60%)`);
});
function initAnimations() {
    try {
        if (!('IntersectionObserver' in window)) {
            throw new Error('Browser not supported');
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.05,
            rootMargin: '20px'
        });

        document.querySelectorAll('.animate-pop-in, .animate-fade-in').forEach(element => {
            observer.observe(element);
        });

    } catch (error) {
        console.error('Animation Error:', error);
        document.querySelectorAll('.animate-pop-in, .animate-fade-in').forEach(element => {
            element.classList.add('active');
        });
    }
}

// 启动动画
document.addEventListener('DOMContentLoaded', initAnimations);
// 保底显示
setTimeout(() => {
    document.querySelectorAll('.animate-pop-in, .animate-fade-in').forEach(element => {
        element.classList.add('active');
    });
}, 1000);
// 模式切换功能
const themeToggle = document.querySelector('.theme-toggle');
const html = document.documentElement;

// 初始化主题
const savedTheme = localStorage.getItem('theme') || 'light';
html.setAttribute('data-theme', savedTheme);

themeToggle.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // 添加过渡类
    html.classList.add('theme-changing');
    setTimeout(() => html.classList.remove('theme-changing'), 400);
    
    // 更新主题
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    // 更新SVG颜色
    updateSvgColors(newTheme);
    // 鸿蒙特色动画
    animateThemeChange(newTheme);
});

function animateThemeChange(theme) {
    const particles = document.createElement('div');
    particles.className = 'theme-change-particles';
    
    for(let i = 0; i < 12; i++) {
        const particle = document.createElement('div');
        particle.className = 'theme-particle';
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        particle.style.background = theme === 'dark' ? '#5A8BFF' : '#fff';
        particles.appendChild(particle);
    }
    
    document.body.appendChild(particles);
    setTimeout(() => particles.remove(), 1000);
}
// 动态模糊参数调整
document.querySelectorAll('.link-item').forEach(button => {
    let hoverProgress = 0;
    
    button.addEventListener('mousemove', (e) => {
        const rect = button.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        // 动态调整模糊强度
        const distanceFromCenter = Math.sqrt(
            Math.pow(x - rect.width/2, 2) + 
            Math.pow(y - rect.height/2, 2)
        );
        const blurValue = 20 + (distanceFromCenter / 50);
        button.style.setProperty('--dynamic-blur', `${Math.min(blurValue, 40)}px`);
        
        // 光纹位置跟踪
        button.style.backgroundPosition = `
            ${(x/rect.width)*100}% ${(y/rect.height)*100}%`;
    });
    
    button.addEventListener('mouseleave', () => {
        button.style.removeProperty('--dynamic-blur');
        button.style.removeProperty('background-position');
    });
});
document.addEventListener('copy', function(e) {
    e.preventDefault();
    return false;
});
let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

if (isMobile) {
    document.querySelectorAll('.link-item').forEach(button => {
        button.addEventListener('touchstart', () => {
            button.classList.add('active');
        });

        button.addEventListener('touchend', () => {
            setTimeout(() => button.classList.remove('active'), 300);
        });
    });
}
function updateSvgColors(theme) {
    const icons = document.querySelectorAll('.social-item img');
    icons.forEach(icon => {
        if (theme === 'dark') {
            icon.style.filter = 'invert(1)'; // 深色模式下为白色
        } else {
            icon.style.filter = 'invert(0)'; // 日间模式下为黑色
        }
    });
}

// 初始化SVG颜色
updateSvgColors(localStorage.getItem('theme') || 'light');


(function () {
  
    // ASCII艺术字模板
    const asciiArt = `
    ██╗     ██╗   ██╗███╗   ███╗██╗███╗   ██╗ ██████╗ ██████╗ ██████╗ 
    ██║     ██║   ██║████╗ ████║██║████╗  ██║██╔═══██╗██╔══██╗██╔═══╝ 
    ██║     ██║   ██║██╔████╔██║██║██╔██╗ ██║██║   ██║██║  ██║██████║
    ██║     ██║   ██║██║╚██╔╝██║██║██║╚██╗██║██║   ██║██║  ██║██╚═══╗
    ███████╗╚██████╔╝██║ ╚═╝ ██║██║██║ ╚████║╚██████╔╝██████╔╝██████║
    ╚══════╝ ╚═════╝ ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝ ╚═════╝ ╚═════╝ ╚═════╝ 
    `;
  
    // 样式配置
    const styles = {
      logo: 'color: #4F46E5; font-size: 10px; line-height: 1.2;',
      header: 'color: #2563EB; font-weight: bold;',
      item: 'color: #3B82F6;',
      link: 'color: #60A5FA; text-decoration: underline;'
    };
        console.log(`%c${asciiArt}`, styles.logo);
        console.groupCollapsed(`%c🚀 Luminode v0.1.2`, styles.header);
        console.log(`%cDocs: https://luminode.s7123.xyz`, styles.link);
        console.groupEnd();
  })();