# Twig2Html

一个强大的工具，用于将Twig模板转换为静态HTML文件。

## 特性

- 支持将Twig模板转换为静态HTML文件
- 支持使用PHP数据文件为模板提供数据
- 支持Twig的所有基本功能（条件、循环、过滤器等）
- 简单的目录结构和使用方法
- 支持批量转换多个模板文件

## 安装

```bash
composer create-project zjkal/twig2html your-project-name
```

这个命令会：
1. 创建一个新的项目目录
2. 安装所有依赖
3. 自动运行初始化脚本
4. 生成示例文件

## 目录结构

```
├── templates/     # Twig模板文件目录
├── data/         # 模板数据文件目录
└── public/       # 生成的HTML文件目录
```

## 使用方法

### 1. 初始化项目

```bash
composer init-project
```

这个命令会：
- 创建必要的目录结构
- 生成示例模板文件 (templates/example.twig)
- 生成示例数据文件 (data/example.php)

### 2. 创建模板

在 `templates` 目录中创建 `.twig` 文件，例如：

```twig
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ title }}</title>
</head>
<body>
    <h1>{{ title|upper }}</h1>
    <p>Welcome, {{ name }}!</p>
    <ul>
        {% for item in items %}
        <li>{{ item }}</li>
        {% endfor %}
    </ul>
</body>
</html>
```

### 3. 创建数据文件

在 `data` 目录中创建与模板同名的PHP文件（将.twig替换为.php），例如：

```php
<?php

return [
    'title' => 'Welcome to Twig2Html',
    'name' => 'John Doe',
    'items' => [
        'Item 1',
        'Item 2',
        'Item 3'
    ]
];
```

### 4. 生成HTML

```bash
composer build
```

这个命令会：
- 读取 `templates` 目录中的所有 `.twig` 文件
- 查找 `data` 目录中对应的数据文件
- 生成HTML文件到 `public` 目录

## 高级用法

### 在PHP代码中使用

```php
use zjkal\twig2html\Converter;

$converter = new Converter();

// 转换单个文件
$converter->convert('template.twig', 'output.html', [
    'title' => 'Hello World',
    'content' => 'Welcome to my website'
]);

// 转换整个目录
$converter->convertDirectory('templates', 'public');
```

### 自定义过滤器

```php
$converter = new Converter();

// 添加自定义过滤器
$converter->addFilter('rot13', function ($string) {
    return str_rot13($string);
});
```

### 自定义函数

```php
$converter = new Converter();

// 添加自定义函数
$converter->addFunction('formatDate', function ($date, $format = 'Y-m-d') {
    return date($format, strtotime($date));
});
```

## 贡献

欢迎提交Issue和Pull Request！

## 许可证

MIT