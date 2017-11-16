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
$user->TestRoles('1,6') or die('У вас не хватает прав на удаление!');

$folderkey = GetDef('folderkey');

$sql = "DELETE FROM cloud_dirs WHERE id='$folderkey'";
$sqlcn->ExecuteSQL($sql) or die('Не могу удалить папку! ' . mysqli_error($sqlcn->idsqlconnection));
