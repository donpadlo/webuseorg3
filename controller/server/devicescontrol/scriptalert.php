<?php

/*
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф *
 * Если исходный код найден в сети - значит лицензия GPL v.3 *
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс *
 */
$page = _GET('page');
$limit = _GET('rows');
$sidx = _GET('sidx');
$sord = _GET('sord');
$oper = _POST('oper');
$id = _POST('id');
$active = _POST('active');
$group_name = _POST('group_name');
$script_name = _POST('script_name');
$comment = _POST('comment');
$current_alert_count = _POST('current_alert_count');
$lastupdatedt = _POST('lastupdatedt');
if ($oper == '') {
    $sql = "CREATE TABLE IF NOT EXISTS `script_run_monitoring` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `script_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `group_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `alert_max_count` int(11) NOT NULL,
  `alert_max_time` int(11) NOT NULL,
  `lastupdatedt` datetime NOT NULL,
  `current_alert_count` int(11) NOT NULL,
  `sms_txt` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `active` tinyint(4) NOT NULL,
  `sms_group_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
    $result = $sqlcn->ExecuteSQL($sql);
    // ///////////////////////////
    // вычисляем фильтр
    // ///////////////////////////
    // получаем наложенные поисковые фильтры
    $filters = GetDef('filters');
    $flt = json_decode($filters, true);
    $cnt = @count($flt['rules']);
    $where = '';
    for ($i = 0; $i < $cnt; $i ++) {
        $field = $flt['rules'][$i]['field'];
        $data = $flt['rules'][$i]['data'];
        $where = $where . "($field LIKE '%$data%')";
        if ($i < ($cnt - 1)) {
            $where = $where . ' AND ';
        }
    }
    echo "";
    if ($where != '') {
        $where = "WHERE $where";
    }
    
    if (! $sidx)
        $sidx = 1;
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM script_run_monitoring $where");
    $row = mysqli_fetch_array($result);
    $count = $row['count'];
    
    if ($count > 0) {
        $total_pages = ceil($count / $limit);
    } else {
        $total_pages = 0;
    }
    ;
    if ($page > $total_pages)
        $page = $total_pages;
    
    $start = $limit * $page - $limit;
    $SQL = "SELECT * FROM script_run_monitoring $where ORDER BY $sidx $sord LIMIT $start , $limit";
    // echo "$SQL";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список мониторинга!" . mysqli_error($sqlcn->idsqlconnection));
    $responce = new stdClass();
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id'] = $row['id'];
        $row['active'] = ($row['active'] == 0) ? 'No' : 'Yes';
        $responce->rows[$i]['cell'] = array(
            $row['active'],
            $row['id'],
            $row['group_name'],
            $row['script_name'],
            $row['comment'],
            $row['current_alert_count'],
            $row['lastupdatedt']
        );
        $i ++;
    }
    echo json_encode($responce);
}
;
if ($oper == 'edit') {
    $active = ($active == 'Yes') ? 1 : 0;
    $SQL = "UPDATE script_run_monitoring SET active='$active',group_name='$group_name',script_name='$script_name',comment='$comment' WHERE id='$id'";
    // echo "!$SQL!";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу обновить данные!" . mysqli_error($sqlcn->idsqlconnection));
}
;
if ($oper == 'add') {
    $active = ($active == 'Yes') ? 1 : 0;
    $SQL = "INSERT INTO script_run_monitoring (active,group_name,script_name,comment) VALUES ('$active','$group_name','$script_name','$comment')";
    // echo "$SQL";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу добавить скрипт мониторинга!" . mysqli_error($sqlcn->idsqlconnection));
}
;
if ($oper == 'del') {
    $SQL = "delete FROM script_run_monitoring WHERE id='$id'";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу удалить!" . mysqli_error($sqlcn->idsqlconnection));
}
;

?>