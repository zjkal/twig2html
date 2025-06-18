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

// 使用批量转换方法
try {
    $result = $converter->convertDirectory($sourceDir, $outputDir, $dataDir);
    
    echo "\n转换结果:\n";
    
    // 输出成功转换的文件
    if (!empty($result['success'])) {
        echo "\n✅ 成功: " . count($result['success']) . " 个文件\n";
        foreach ($result['success'] as $file) {
            echo "  {$file}\n";
        }
    }
    
    // 输出跳过的文件
    if (!empty($result['skipped'])) {
        echo "\n⏭️ 跳过: " . count($result['skipped']) . " 个文件\n";
        foreach ($result['skipped'] as $file) {
            echo "  {$file}\n";
        }
    }
    
    // 输出失败的文件
    if (!empty($result['failed'])) {
        echo "\n❌ 失败: " . count($result['failed']) . " 个文件\n";
        foreach ($result['failed'] as $file) {
            echo "  {$file}\n";
        }
    }
    
    echo "\n转换完成！\n";
    
} catch (Exception $e) {
    echo "转换过程出错: {$e->getMessage()}\n";
    exit(1);
}