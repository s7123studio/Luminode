document.addEventListener('DOMContentLoaded', () => {
    // 输入框动效
    document.querySelectorAll('.input-field').forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.classList.add('active');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) input.parentElement.classList.remove('active');
        });
    });

    // 表单提交动画
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            const btn = form.querySelector('.btn');
            if (btn) {
                btn.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    btn.style.transform = '';
                }, 200);
            }
        });
    });

    // 卡片入场动画
    const cards = document.querySelectorAll('.settings-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});