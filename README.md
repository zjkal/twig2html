# Twig2Html

一个强大的Twig模板转换工具，支持开发时实时预览和生产环境静态HTML生成。通过简单的配置，即可将Twig模板转换为静态HTML文件，适用于构建静态网站、邮件模板或其他需要模板渲染的场景。

## 特性

- 支持所有Twig模板语法和功能
- 开发时支持实时预览（直接访问.html即可）
- 简单直观的目录结构
- 自动加载模板对应的数据文件（可选）
- 支持批量转换多个模板
- 内置美观的默认样式
- 开箱即用的示例模板和数据文件
- 支持静态资源（CSS、JS、图片）管理

## 系统要求

- PHP >= 7.4
- Composer
- ext-fileinfo 扩展

## 快速开始

### 创建新项目

```bash
composer create-project zjkal/twig2html my-project
cd my-project
composer dev
```

然后在浏览器中访问 http://localhost:8080/ 即可看到示例页面。

## 目录结构

```
my-project/
├── templates/        # Twig模板文件目录
│   └── index.twig    # 示例模板文件
├── data/            # 模板数据文件目录（可选）
│   └── index.php    # 示例数据文件
├── public/          # 静态资源和输出目录
│   ├── assets/      # 静态资源目录
│   │   ├── css/    # CSS文件
│   │   ├── js/     # JavaScript文件
│   │   └── images/ # 图片文件
│   └── *.html      # 生成的HTML文件
├── build.php        # 构建脚本
├── build.bat        # Windows构建批处理
├── dev.php          # 开发服务器
├── init.php         # 初始化脚本
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

2. 在浏览器中访问模板：
   - 使用`.html`后缀访问，如`http://localhost:8080/about.html`
   - 开发服务器会自动查找对应的`.twig`模板
   - 如果存在同名的数据文件，会自动加载数据

### 创建页面

1. 在`templates`目录下创建Twig模板，如`about.twig`：
```twig
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>{{ title|default('页面标题') }}</title>
</head>
<body>
    <h1>{{ title|default('页面标题') }}</h1>
    {{ content|default('页面内容')|raw }}
</body>
</html>
```

2. 在`data`目录下创建对应的数据文件（可选），如`about.php`：
```php
<?php
return [
    'title' => '关于我们',
    'content' => '<p>这是一个示例页面</p>'
];
```

### 静态资源管理

- 将静态资源放在`public/assets`目录下对应的子目录中
- 在模板中使用以下路径格式引用：
```html
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/main.js"></script>
<img src="/assets/images/logo.png" alt="Logo">
```

### 生成静态HTML

开发完成后，执行以下命令生成静态HTML文件：

```bash
composer build
# 或者
php build.php
```

生成的HTML文件将保存在`public`目录中，可以直接部署到任何Web服务器。

## 高级用法

### 数据文件（可选）

- 数据文件必须返回一个PHP数组
- 文件名必须与模板文件同名（扩展名不同）
- 如果数据文件不存在，模板仍然可以正常渲染
- 可以在模板中使用`is defined`检查变量是否存在

### 批量构建

`build.php`脚本会自动处理`templates`目录下的所有`.twig`文件：
- 保持目录结构
- 自动查找对应的数据文件
- 在`public`目录下生成对应的`.html`文件

## 许可证

本项目采用 MIT 许可证，详见 [LICENSE](LICENSE) 文件。