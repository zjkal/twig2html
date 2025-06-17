<?php

namespace zjkal\twig2html\Tests;

use Exception;
use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use zjkal\twig2html\Converter;

class ConverterTest extends TestCase
{
    private string $tempDir;
    private Converter $converter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tempDir = sys_get_temp_dir() . '/twig2html_test_' . uniqid();
        mkdir($this->tempDir);
        mkdir($this->tempDir . '/templates');
        mkdir($this->tempDir . '/output');

        $this->converter = new Converter([
            'debug' => true
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tempDir);
        parent::tearDown();
    }

    public function testConvertSingleFile(): void
    {
        // 创建测试模板
        $templateContent = '<!DOCTYPE html><html lang="en"><body>{{ message }}</body></html>';
        $templateFile = $this->tempDir . '/templates/test.twig';
        $outputFile = $this->tempDir . '/output/test.html';
        file_put_contents($templateFile, $templateContent);

        // 测试转换
        try {
            $result = $this->converter->convert($templateFile, $outputFile, [
                'message' => 'Hello World'
            ]);
        } catch (Exception $e) {
            $result = false;
        }

        $this->assertTrue($result);
        $this->assertFileExists($outputFile);
        $this->assertEquals(
            '<!DOCTYPE html><html lang="en"><body>Hello World</body></html>',
            file_get_contents($outputFile)
        );
    }

    public function testConvertNonExistentTemplate(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('模板文件不存在');

        try {
            $this->converter->convert(
                $this->tempDir . '/non_existent.twig',
                $this->tempDir . '/output/test.html',
            );
        } catch (Exception $e) {
            $this->expectExceptionMessage('模板文件不存在');
        }
    }

    public function testConvertNonExistentSourceDirectory(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('源目录不存在');

        $this->converter->convertDirectory(
            $this->tempDir . '/non_existent',
            $this->tempDir . '/output',
        );
    }

    public function testConvertDirectory(): void
    {
        // 创建多个测试模板
        $templates = [
            'test1.twig' => '{{ message1 }}',
            'test2.twig' => '{{ message2 }}'
        ];

        foreach ($templates as $file => $content) {
            file_put_contents($this->tempDir . '/templates/' . $file, $content);
        }

        // 测试批量转换
        $result = $this->converter->convertDirectory(
            $this->tempDir . '/templates',
            $this->tempDir . '/output',
            [
                'message1' => 'Hello',
                'message2' => 'World'
            ]
        );

        $this->assertCount(2, $result['success']);
        $this->assertCount(0, $result['failed']);
        $this->assertFileExists($this->tempDir . '/output/test1.html');
        $this->assertFileExists($this->tempDir . '/output/test2.html');
    }

    public function testCustomFilter(): void
    {
        // 添加自定义过滤器
        $this->converter->addFilter('test_upper', function ($str) {
            return strtoupper($str);
        });

        // 创建测试模板
        $templateContent = '{{ message|test_upper }}';
        $templateFile = $this->tempDir . '/templates/filter_test.twig';
        $outputFile = $this->tempDir . '/output/filter_test.html';
        file_put_contents($templateFile, $templateContent);

        // 测试转换
        try {
            $result = $this->converter->convert($templateFile, $outputFile, [
                'message' => 'hello'
            ]);
        } catch (Exception $e) {
            $result = false;
        }

        $this->assertTrue($result);
        $this->assertEquals('HELLO', file_get_contents($outputFile));
    }

    public function testCustomFunction(): void
    {
        // 添加自定义函数
        $this->converter->addFunction('test_concat', function ($str1, $str2) {
            return $str1 . $str2;
        });

        // 创建测试模板
        $templateContent = '{{ test_concat(text1, text2) }}';
        $templateFile = $this->tempDir . '/templates/function_test.twig';
        $outputFile = $this->tempDir . '/output/function_test.html';
        file_put_contents($templateFile, $templateContent);

        // 测试转换
        try {
            $result = $this->converter->convert($templateFile, $outputFile, [
                'text1' => 'Hello',
                'text2' => 'World'
            ]);
        } catch (Exception $e) {
            $result = false;
        }

        $this->assertTrue($result);
        $this->assertEquals('HelloWorld', file_get_contents($outputFile));
    }

    private function removeDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }
}