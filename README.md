# Luminode Framework 🚀

[![PHP Version](https://img.shields.io/badge/PHP-≥8.0-777BB4?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Documentation](https://img.shields.io/badge/Docs-Luminode.dev-brightgreen)](https://luminode.dev)

**光速启航的PHP轻量级框架** • [English Version](README_EN.md)

---

## ✨ 核心特性

- **极简内核** - 仅 150KB 核心代码，零外部依赖
- **光速响应** - 内置OPcache优化，毫秒级路由匹配
- **模块化架构** - 按需加载组件，自由搭配功能
- **安全加固** - 自动CSRF防护 + XSS过滤 + SQL注入防御
- **全栈支持** - 从路由到模板引擎一站式解决方案

## 🚀 快速开始
访问官网
## 🛠️ 功能全景
<table>
    <thead>
        <tr>
            <th>模块</th>
            <th>特性</th>
            <th>示例代码片段</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>路由系统</strong></td>
            <td>RESTful/动态路由/中间件链</td>
            <td><code>Router::apiResource('posts')</code></td>
        </tr>
        <tr>
            <td><strong>ORM</strong></td>
            <td>关联查询/分页/事务处理</td>
            <td><code>Post::with('comments')-&gt;paginate()</code></td>
        </tr>
        <tr>
            <td><strong>安全</strong></td>
            <td>CSRF令牌/密码哈希/请求验证</td>
            <td><code>Validator::make($data, $rules)</code></td>
        </tr>
        <tr>
            <td><strong>文件处理</strong></td>
            <td>上传验证/云存储支持/图片处理</td>
            <td><code>File::upload()-&gt;resize(300)</code></td>
        </tr>
        <tr>
            <td><strong>模板引擎</strong></td>
            <td>组件化布局/自动转义/多主题</td>
            <td><code>&lt;x-alert type="success"&gt;</code></td>
        </tr>
        <tr>
            <td><strong>任务队列</strong></td>
            <td>Redis驱动/失败重试/进度追踪</td>
            <td><code>ProcessPodcast::dispatch($podcast)</code></td>
        </tr>
    </tbody>
</table>

## 🌟 为何选择Luminode？
<ul>
<li>新手友好 - 极简API设计 + 中文文档</li>
<li>企业级扩展 - 支持千万级数据分页查询</li>
<li>全栈监控 - 内置性能分析面板</li>
<li>现代工具链 - 集成Vite前端工具链</li>
</ul>

## 🤝 参与贡献
欢迎通过以下方式参与项目：
<ul>
<li>提交Issue报告问题</li>
<li>Fork仓库并提交PR</li>
<li>完善文档</li>
<li>分享使用案例</li>
</ul>

## 📜 开源协议
本项目采用 MIT 许可证，保留核心开发者署名权利。商业使用请遵循补充条款.
<p><strong>星光不问赶路人</strong> - <a href="https://xxx" target="_blank" rel="noreferrer">立即探索完整文档</a> | <a href="https://xxxx" target="_blank" rel="noreferrer">加入QQ群</a></p>