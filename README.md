# Luminode Framework 🚀

[![PHP Version](https://img.shields.io/badge/PHP-≥8.0-777BB4?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](LICENSE.txt)
[![Documentation](https://img.shields.io/badge/Docs-Luminode-brightgreen)](https://Luminode.s7123.xyz)

**光速启航的PHP轻量级框架** • [English Version](README_EN.md)

---

光枢框架是一个现代、轻量、对新手友好的PHP框架，专为快速开发和优雅的编码体验而设计。它提供了构建Web应用所需的核心功能，同时保持了极高的灵活性和性能。

## ✨ 核心特性

-   **现代ORM (Active Record)**
    -   强大的链式查询构造器 (`where`, `orderBy`, `limit` 等)。
    -   支持模型关系 (`hasMany`, `belongsTo`) 及延迟加载。
    -   解决 N+1 问题的预加载 (`with()`)。
    -   通过 `$fillable` 属性提供批量赋值安全保护。
-   **灵活的路由系统**
    -   支持 `GET`, `POST`, `PUT`, `PATCH`, `DELETE` 等 RESTful 动词。
    -   完整的中间件管道，可轻松实现请求过滤和处理。
    -   支持方法伪造 (`_method`)，方便HTML表单提交。
-   **完整的用户认证系统**
    -   基于 Session 的可注入 `Auth` 服务。
    -   `Authenticate` 中间件保护路由。
    -   安全的密码哈希 (`password_hash`)。
-   **强大的数据验证器**
    -   丰富的内置验证规则 (`required`, `email`, `numeric`, `min`, `max`, `confirmed` 等)。
    -   支持参数化规则，灵活配置验证逻辑。
-   **依赖注入容器 (DI)**
    -   基于 `PHP-DI`，实现服务自动装配和松耦合。
    -   简化控制器和服务间的依赖管理。
-   **优雅的错误处理**
    -   开发环境集成 `Whoops`，提供详尽的交互式错误页面。
    -   生产环境静默日志，确保安全性和稳定性。
    -   智能解决方案建议，帮助快速定位和解决问题。
-   **命令行工具 (CLI)**
    -   `make:controller`, `make:model`, `make:view`, `make:middleware` 快速生成代码。
    -   `route:list` 清晰展示所有注册路由。
-   **统一的响应处理**
    -   控制器方法返回统一的 `Response` 对象，支持视图、JSON、重定向等多种响应类型。

## 🚀 快速开始

### 1. 克隆仓库

```bash
git clone https://github.com/s7123studio/Luminode.git my-luminode-app
cd my-luminode-app
```

### 2. 安装依赖

```bash
composer install
```

### 3. 配置环境

复制 `.env.example` 为 `.env`，并根据您的环境修改数据库连接信息：

```bash
cp .env.example .env
```

编辑 `.env` 文件：
```dotenv
# .env
APP_ENV=development
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=luminode_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 4. Web服务器配置

将您的Web服务器（如 Nginx 或 Apache）的根目录指向项目的 `public` 文件夹。

**Nginx 示例配置:**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/your-project/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.x-fpm.sock; # 根据您的环境修改
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 5. 访问应用

完成配置后，在浏览器中访问您的域名，即可看到光枢框架的欢迎页面。

## 📚 详细文档

访问我们的 [官方文档](https://Luminode.s7123.xyz) 获取更深入的指南、API参考和高级用法。

## 🤝 参与贡献

我们非常欢迎社区的贡献！
-   提交 Issue 报告问题或提出建议。
-   Fork 仓库并提交 Pull Request。
-   完善文档或分享使用案例。

## 📜 开源协议

本项目采用 Apache 2.0 许可证。

<p><strong>星光不问赶路人</strong> - <a href="https://Luminode.s7123.xyz" target="_blank" rel="noreferrer">立即探索完整文档</a> | <a href="https://qm.qq.com/q/jMO3KX4IiA" target="_blank" rel="noreferrer">加入QQ群</a></p>