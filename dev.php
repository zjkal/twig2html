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
        // 根据文件扩展名设置正确的Content-Type
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            // 样式表和脚本
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            // 图片
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
            'webp' => 'image/webp',
            'avif' => 'image/avif',
            // 字体
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'otf' => 'font/otf',
            'eot' => 'application/vnd.ms-fontobject',
            // 文档
            'html' => 'text/html',
            'htm' => 'text/html',
            'xml' => 'application/xml',
            'txt' => 'text/plain',
            'md' => 'text/markdown',
            'pdf' => 'application/pdf',
            // 音视频
            'mp3' => 'audio/mpeg',
            'wav' => 'audio/wav',
            'ogg' => 'audio/ogg',
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'm4v' => 'video/x-m4v',
            // 压缩文件
            'zip' => 'application/zip',
            'gz' => 'application/gzip',
            'tar' => 'application/x-tar',
            // 其他常用类型
            'csv' => 'text/csv',
            'ics' => 'text/calendar',
            'vcf' => 'text/vcard'
        ];
        $mimeType = isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : mime_content_type($filePath);
        header('Content-Type: ' . $mimeType);
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0'); // 确保所有浏览器都不缓存
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
$data = file_exists($dataFile) ? require $dataFile : [];

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