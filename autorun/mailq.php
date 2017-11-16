<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф

/*
 * смотрим почту в очереди и отправляем одно письмо за раз
 * после чего очередь сокращаем на 1 письмо
 */
$result11 = $sqlcn->ExecuteSQL('SELECT * FROM mailq LIMIT 1') or $err[] = 'Не получилось прочитать очередь сообщений ' . mysqli_error($sqlcn->idsqlconnection);
while ($row = mysqli_fetch_array($result11)) {
    mailq($row['to'], $row['title'], $row['btxt']);
    $idm = $row['id'];
    $result12 = $sqlcn->ExecuteSQL("DELETE FROM mailq WHERE id=$idm") or $err[] = 'Не получилось удалить сообщение из очереди ' . mysqli_error($sqlcn->idsqlconnection);
}
