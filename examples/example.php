<?php

require_once __DIR__ . '/../vendor/autoload.php';

use zjkal\twig2html\Converter;

// 创建示例模板目录和文件
if (!is_dir(__DIR__ . '/templates')) {
    mkdir(__DIR__ . '/templates');
}

// 创建示例模板文件
$templateContent = <<<TWIG
<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ title }}</title>
</head>
<body>
    <h1>{{ title }}</h1>
    <div class="content">
        {{ content|raw }}
    </div>
    <ul>
    {% for item in items %}
        <li>{{ item }}</li>
    {% endfor %}
    </ul>
</body>
</html>
TWIG;

file_put_contents(__DIR__ . '/templates/example.twig', $templateContent);

// 初始化转换器
$converter = new Converter([
    'debug' => true
]);

// 添加自定义过滤器
$converter->addFilter('uppercase', function($str) {
    return strtoupper($str);
});

// 准备数据
$data = [
    'title' => '示例页面',
    'content' => '<p>这是一个<strong>示例</strong>内容。</p>',
    'items' => ['项目1', '项目2', '项目3']
];

// 转换单个文件
try {
    $result = $converter->convert(
        __DIR__ . '/templates/example.twig',
        __DIR__ . '/public/example.html',
        $data
    );
    
    if ($result) {
        echo "转换成功！输出文件：" . __DIR__ . '/public/example.html' . "\n";
    } else {
        echo "转换失败！\n";
    }
} catch (Exception $e) {
    echo "转换出错：" . $e->getMessage() . "\n";
}

// 批量转换示例
try {
    $result = $converter->convertDirectory(
        __DIR__ . '/templates',
        __DIR__ . '/public',
        $data
    );
    
    echo "\n批量转换结果：\n";
    echo "成功：" . count($result['success']) . " 个文件\n";
    echo "失败：" . count($result['failed']) . " 个文件\n";
} catch (Exception $e) {
    echo "批量转换出错：" . $e->getMessage() . "\n";
}