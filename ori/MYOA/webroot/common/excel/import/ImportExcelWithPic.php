<?php

header("content-type:text/html;charset=utf8");
include './Classes/PHPExcel/IOFactory.php'; //引入PHPExcel类

$inputFileName = './test.xls'; //读取的excel文件
date_default_timezone_set('PRC');
// 读取excel文件
try {
    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($inputFileName);
} catch (Exception $e) {
    die('加载文件发生错误："' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
}

$sheet = $objPHPExcel->getSheet(0);
$data = $sheet->toArray(); //该方法读取不到图片 图片需单独处理
$imageFilePath = './images/' . date('Y-m-d') . '/'; //图片在本地存储的路径

if (!file_exists($imageFilePath)) {
    mkdir("$imageFilePath", 0777, true);
}

//处理图片
foreach ($sheet->getDrawingCollection() as $img) {
    list($startColumn, $startRow) = PHPExcel_Cell::coordinateFromString($img->getCoordinates()); //获取图片所在行和列
    $imageFileName = $img->getCoordinates() . mt_rand(100, 999);
    switch ($img->getMimeType()) {
        case 'image/jpg':
            $imageFileName .= '.jpg';
            imagejpeg($img->getImageResource(), $imageFilePath . $imageFileName);
            break;
        case 'image/gif':
            $imageFileName .= '.gif';
            imagegif($img->getImageResource(), $imageFilePath . $imageFileName);
            break;
        case 'image/png':
            $imageFileName .= '.png';
            imagepng($img->getImageResource(), $imageFilePath . $imageFileName);
            break;
    }
    $startColumn = ABC2decimal($startColumn); //由于图片所在位置的列号为字母，转化为数字
    $data[$startRow - 1][$startColumn] = $imageFilePath . $imageFileName; //把图片插入到数组中
}

print_r($data);
die;


function ABC2decimal($abc)
{
    $ten = 0;
    $len = strlen($abc);
    for ($i = 1; $i <= $len; $i++) {
        $char = substr($abc, 0 - $i, 1); //反向获取单个字符
        $int = ord($char);
        $ten += ($int - 65) * pow(26, $i - 1);
    }

    return $ten;
}
