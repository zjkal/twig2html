<?php

// 定义需要创建的目录
$directories = [
    __DIR__ . '/templates',
    __DIR__ . '/data',
    __DIR__ . '/public'
];

// 创建目录
foreach ($directories as $directory) {
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
        echo "Created directory: {$directory}\n";
    }
}

// 创建示例模板文件
$exampleTemplate = __DIR__ . '/templates/example.twig';
if (!file_exists($exampleTemplate)) {
    $templateContent = <<<'TWIG'
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
TWIG;
    file_put_contents($exampleTemplate, $templateContent);
    echo "Created example template: {$exampleTemplate}\n";
}

// 创建示例数据文件
$exampleData = __DIR__ . '/data/example.php';
if (!file_exists($exampleData)) {
    $dataContent = <<<'PHP'
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
PHP;
    file_put_contents($exampleData, $dataContent);
    echo "Created example data file: {$exampleData}\n";
}

echo "\nProject initialization completed successfully!\n";