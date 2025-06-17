<?php

require __DIR__ . '/vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// 设置目录
$templatesDir = __DIR__ . '/templates';
$dataDir = __DIR__ . '/data';
$publicDir = __DIR__ . '/public';

// 创建Twig环境
$loader = new FilesystemLoader($templatesDir);
$twig = new Environment($loader, [
    'debug' => true,
    'cache' => false,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// 获取请求的URI
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// 如果请求的是根目录，默认显示index.html
if ($requestPath === '/') {
    $requestPath = '/index.html';
}

// 如果请求的是静态资源，直接从public目录提供服务
if (strpos($requestPath, '/assets/') === 0) {
    $filePath = $publicDir . $requestPath;
    if (file_exists($filePath)) {
        $mimeType = mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        readfile($filePath);
        exit;
    }
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

// 将.html替换为.twig以匹配模板文件
$templateFile = str_replace('.html', '.twig', ltrim($requestPath, '/'));
$templatePath = $templatesDir . '/' . $templateFile;

// 检查模板文件是否存在
if (!file_exists($templatePath)) {
    http_response_code(404);
    echo '404 Not Found';
    exit;
}

// 查找对应的数据文件
$dataFile = $dataDir . '/' . str_replace('.twig', '.php', $templateFile);
$data = [];
if (file_exists($dataFile)) {
    $data = require $dataFile;
}

try {
    // 渲染模板
    $html = $twig->render($templateFile, $data);
    echo $html;
} catch (Exception $e) {
    http_response_code(500);
    if ($twig->isDebug()) {
        echo '<h1>Error</h1>';
        echo '<pre>' . htmlspecialchars($e->getMessage()) . '</pre>';
    } else {
        echo '500 Internal Server Error';
    }
}