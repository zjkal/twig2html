<?php

require_once __DIR__ . '/vendor/autoload.php';

use zjkal\twig2html\core\Converter;

// 设置目录
$sourceDir = __DIR__ . '/templates';
$outputDir = __DIR__ . '/public';
$dataDir = __DIR__ . '/data';

// 检查必要目录是否存在
if (!file_exists($sourceDir) || !file_exists($dataDir)) {
    echo "Error: templates or data directory not found. Please run 'composer init-project' first.\n";
    exit(1);
}

// 确保输出目录存在
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// 初始化转换器
$converter = new Converter();

// 获取所有.twig文件
$templates = glob($sourceDir . '/*.twig');

if (empty($templates)) {
    echo "No .twig templates found in {$sourceDir}\n";
    exit(1);
}

// 转换每个模板
foreach ($templates as $template) {
    $templateName = basename($template);
    $outputFile = $outputDir . '/' . str_replace('.twig', '.html', $templateName);
    
    // 查找对应的数据文件
    $dataFile = $dataDir . '/' . str_replace('.twig', '.php', $templateName);
    $data = file_exists($dataFile) ? require $dataFile : [];
    
    try {
        $converter->convert($template, $outputFile, $data);
        echo "Converted {$templateName} to " . basename($outputFile) . "\n";
    } catch (Exception $e) {
        echo "Error converting {$templateName}: {$e->getMessage()}\n";
    }
}

echo "\nConversion completed successfully!\n";