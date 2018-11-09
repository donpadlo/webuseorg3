<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$user->TestRoles('1,3,4,5,6') or die('Недостаточно прав');

$id = GetDef('id');
is_numeric($id) or die('Переданы неправильные параметры');

$sql = "SELECT * FROM cloud_files WHERE id = $id";
$result = $sqlcn->ExecuteSQL($sql) or die('Ошибка получения файла из базы! ' . mysqli_error($sqlcn->idsqlconnection));
$row = mysqli_fetch_array($result);
$filename = WUO_ROOT . '/files/' . $row['filename'];

(file_exists($filename) && is_file($filename)) or die('Файл не найден');

// Органичение скорости скачивания - 10.0 MB/s
$download_rate = 10.0;

$size = filesize($filename);
$name = rawurldecode($row['title']);

// Decrease CPU usage extreme.
@ob_end_clean();

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $name . '"');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Accept-Ranges: bytes');
header('Cache-control: private');
header('Pragma: private');

// Multipart-download and resume-download.
if (isset($_SERVER['HTTP_RANGE'])) {
    list ($a, $range) = explode('=', $_SERVER['HTTP_RANGE']);
    str_replace($range, '-', $range);
    $size2 = $size - 1;
    $new_length = $size - $range;
    header('HTTP/1.1 206 Partial Content');
    header("Content-Length: $new_length");
    header("Content-Range: bytes $range$size2/$size");
} else {
    $size2 = $size - 1;
    header("Content-Length: $size");
    header("Content-Range: bytes 0-$size2/$size");
}

$chunksize = round($download_rate * 1048576);

// Flush content.
flush();

if ($fp = @fopen($filename, 'rb')) {
    flock($fp, LOCK_SH);
    if (isset($_SERVER['HTTP_RANGE'])) {
        fseek($fp, $range);
    }
    while (! feof($fp) and (connection_status() == 0)) {
        echo fread($fp, $chunksize);
        
        // Flush the content to the browser.
        flush();
        
        // Decrease download speed.
        sleep(1);
    }
    flock($fp, LOCK_UN);
    
    fclose($fp);
} else {
    // die('Невозможно открыть файл');
}