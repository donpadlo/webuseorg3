<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.
                                               
// Выполняем только при наличии у пользователя соответствующей роли
                                               // http://грибовы.рф/wiki/doku.php/основы:доступ:роли
$user->TestRoles('1,4') or die('У вас не хватает прав на добавление!');

$foldername = GetDef('foldername');

$sql = "INSERT INTO cloud_dirs (parent, name) VALUES (0, '$foldername')";
$sqlcn->ExecuteSQL($sql) or die("Не могу добавить папку! " . mysqli_error($sqlcn->idsqlconnection));
