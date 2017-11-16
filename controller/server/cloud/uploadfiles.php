<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.
                                               
// Проверяем: может ли пользователь добавлять файлы?
$user->TestRoles('1,4') or die('Недостаточно прав');

$selectedkey = $_POST['selectedkey'];
$orig_file = $_FILES['filedata']['name'];
$dis = array(
    '.htaccess'
); // Запрещённые для загрузки файлы

$rs = array(
    'msg' => 'error'
); // Ответ по умолчанию, если пойдёт что-то не так

if (! in_array($orig_file, $dis)) {
    $userfile_name = GetRandomId(8) . '.' . pathinfo($orig_file, PATHINFO_EXTENSION);
    $src = $_FILES['filedata']['tmp_name'];
    $dst = WUO_ROOT . '/files/' . $userfile_name;
    $res = move_uploaded_file($src, $dst);
    if ($res) {
        $rs['msg'] = $userfile_name;
        $sz = filesize($dst);
        if ($selectedkey != '') {
            $SQL = "INSERT INTO cloud_files (id, cloud_dirs_id, title, filename, dt, sz)
				VALUES (null, '$selectedkey', '$orig_file', '$userfile_name', NOW(), $sz)";
            $sqlcn->ExecuteSQL($SQL) or die('Не могу добавить файл! ' . mysqli_error($sqlcn->idsqlconnection));
        }
    }
}

jsonExit($rs);
