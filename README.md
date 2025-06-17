# Twig2Html

一个强大的Twig模板转换工具，支持开发时实时预览和生产环境静态HTML生成。

## 特性

- 支持所有Twig模板语法和功能
- 开发时支持实时预览（直接访问.html即可）
- 简单直观的目录结构
- 自动加载模板对应的数据文件
- 支持批量转换多个模板
- 内置美观的默认样式

## 系统要求

- PHP >= 7.4
- Composer

## 安装

```bash
composer create-project zjkal/twig2html my-project
cd my-project
```

## 目录结构

```
my-project/
├── templates/        # Twig模板文件
├── data/            # 模板数据文件
├── public/          # 静态资源和输出目录
│   ├── assets/     # 静态资源（CSS、JS、图片等）
│   └── *.html      # 生成的HTML文件
├── build.php        # 构建脚本
├── build.bat        # Windows构建批处理
├── dev.php          # 开发服务器
└── composer.json    # 项目配置文件
```

## 使用方法

### 开发模式

1. 启动开发服务器：
```bash
composer dev
# 或者
php -S localhost:8080 dev.php
```

2. 在浏览器中访问 http://localhost:8080/ 预览页面
   - 直接使用`.html`后缀访问，如`http://localhost:8080/about.html`
   - 会自动查找对应的`.twig`模板和数据文件

### 创建页面

1. 在`templates`目录下创建Twig模板，如`about.twig`
2. 在`data`目录下创建对应的数据文件（可选），如`about.php`：
```php
<?php
return [
    'title' => '关于我们',
    'content' => '页面内容'
];
```

### 静态资源

- 将静态资源（CSS、JS、图片等）放在`public/assets`目录下
- 在模板中引用时使用以下路径格式：
```html
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/main.js"></script>
<img src="/assets/images/logo.png">
```

### 生成静态HTML

开发完成后，执行以下命令生成静态HTML文件：

```bash
composer build
# 或者
php build.php
```

生成的HTML文件将保存在`public`目录中。

## 许可证

MIT License