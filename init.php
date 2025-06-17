<?php

// 定义需要创建的目录
$directories = [
    __DIR__ . '/templates',
    __DIR__ . '/data',
    __DIR__ . '/public',
    __DIR__ . '/public/assets',
    __DIR__ . '/public/assets/css',
    __DIR__ . '/public/assets/js',
    __DIR__ . '/public/assets/images'
];

// 创建目录
foreach ($directories as $directory) {
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
        echo "Created directory: {$directory}\n";
    }
}

// 创建示例模板文件
$exampleTemplate = __DIR__ . '/templates/index.twig';
if (!file_exists($exampleTemplate)) {
    $templateContent = <<<'TWIG'
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>欢迎使用Twig2Html</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
    </style>
</head>
<body>
    <h1>欢迎使用Twig2Html</h1>
    <div class="content">
        <p>这是一个示例页面，展示了如何使用Twig2Html工具将Twig模板转换为静态HTML文件。</p>
        <p>开发时可以直接访问 index.html 预览效果，完成后执行 composer build 生成静态文件。</p>
    </div>
    {% if items is defined and items is not empty %}
    <div class="feature-list">
        <h2>数据文件演示：</h2>
        <ul>
            {% for item in items %}
            <li>{{ item }}</li>
            {% endfor %}
        </ul>
    </div>
    {% else %}
    <div class="note">
        <p>提示：当前页面没有加载数据文件，这是正常的。</p>
        <p>如果需要展示动态数据，可以创建 data/index.php 文件并返回数据数组。</p>
    </div>
    {% endif %}
    <script src="/assets/js/main.js"></script>
</body>
</html>
TWIG;
    file_put_contents($exampleTemplate, $templateContent);
    echo "Created example template: {$exampleTemplate}\n";
}

// 创建示例数据文件
$exampleData = __DIR__ . '/data/index.php';
if (!file_exists($exampleData)) {
    $dataContent = <<<'PHP'
<?php

return [
    'items' => [
        '这是从数据文件加载的第一条信息',
        '这是从数据文件加载的第二条信息',
        '这是从数据文件加载的第三条信息'
    ]
];
PHP;
    file_put_contents($exampleData, $dataContent);
    echo "Created example data file: {$exampleData}\n";
}

// 创建示例CSS文件
$exampleCss = __DIR__ . '/public/assets/css/style.css';
if (!file_exists($exampleCss)) {
    $cssContent = <<<'CSS'
/* 示例样式文件 */
.content {
    background: #f5f5f5;
    padding: 20px;
    border-radius: 5px;
    margin: 20px 0;
}

.feature-list ul {
    list-style-type: circle;
    color: #333;
}

.feature-list li {
    padding: 5px 0;
}
CSS;
    file_put_contents($exampleCss, $cssContent);
    echo "Created example CSS file: {$exampleCss}\n";
}

// 创建示例JavaScript文件
$exampleJs = __DIR__ . '/public/assets/js/main.js';
if (!file_exists($exampleJs)) {
    $jsContent = <<<'JS'
// 示例JavaScript文件
console.log('Twig2Html开发服务器已启动');
JS;
    file_put_contents($exampleJs, $jsContent);
    echo "Created example JavaScript file: {$exampleJs}\n";
}

$projectDir = basename(__DIR__);
echo "\n项目初始化完成！\n";
echo "请先进入项目目录：cd {$projectDir}\n";
echo "然后运行 'composer dev' 启动开发服务器，访问 http://localhost:8080/ 预览页面。\n";
echo "完成开发后，运行 'composer build' 生成静态HTML文件。\n";