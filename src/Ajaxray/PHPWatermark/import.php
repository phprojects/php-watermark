<?php
namespace Ajaxray\PHPWatermark;

// 未使用composer时导入本文件
include_once __DIR__.'/CommandBuilders/AbstractCommandBuilder.php';
include_once __DIR__.'/CommandBuilders/ImageCommandBuilder.php';
include_once __DIR__.'/CommandBuilders/PDFCommandBuilder.php';
include_once __DIR__.'/Requirements/RequirementsChecker.php';
include_once __DIR__.'/Watermark.php';

//外部调用设置命令前缀：\Ajaxray\PHPWatermark\Watermark::$commandPrefix='"C:\\Program Files\\ImageMagick-7.0.8-Q16\\magick" ';
//setlocale(LC_CTYPE, "en_US.UTF-8");