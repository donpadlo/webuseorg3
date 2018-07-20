<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

$mode = _GET("mode");


$page = _GET("page");
$rows = _GET("rows");
$sidx = _GET("sidx");
$sord = _GET("sord");
$oper = _POST("oper");
$filename = _POST("filename");
$limit = _GET("rows");
$id = _POST("id");
$idcontract = _GET("idcontract");

$where = " WHERE idcontract='$idcontract'";
if ($oper == '') {
    if ($user->TestRoles('1,3')) {
        if (!$sidx) $sidx = 1;
        $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM files_contract" . $where);
        $row = mysqli_fetch_array($result);
        $count = $row['count'];
        if ($limit==0){$limit=1;};
        if ($count > 0) {$total_pages = ceil($count / $limit);} else {$total_pages = 0;}
        
        if ($page > $total_pages) $page = $total_pages;
        
        $start = $limit * $page - $limit;
        $SQL = "SELECT * FROM files_contract " . $where . " ORDER BY $sidx $sord LIMIT $start , $limit";
        //echo "!$SQL!";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать список договоров!" . mysqli_error($sqlcn->idsqlconnection));
        $responce = new stdClass();
        $responce->page = $page;
        $responce->total = $total_pages;
        $responce->records = $count;
        $i = 0;
        while ($row = mysqli_fetch_array($result)) {
            $responce->rows[$i]['id'] = $row['id'];
            $filename = $row['filename'];
            $dt = $row['dt'];
            $userfreandlyfilename = $row['userfreandlyfilename'];
            if ($userfreandlyfilename == "") {
                $userfreandlyfilename = 'Посмотреть';
            }            
            if ($mode == "") {
                $responce->rows[$i]['cell'] = array(
                    $row['id'],
                    "<a target=_blank href='files/$filename'>$userfreandlyfilename</a>",
                    $dt
                );
            } else {
                $responce->rows[$i]['cell'] = array(
                    $row['id'],
                    "<a target=_blank href='files/$filename'>Скачать</a>",
                    "$userfreandlyfilename",
                    $dt
                );
            }
            $i ++;
        }
        echo json_encode($responce);
    }
}
if ($oper == 'del') {
    if ($user->TestRoles('1,6')) {
        $SQL = "DELETE FROM files_contract WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не смог удалить файл!" . mysqli_error($sqlcn->idsqlconnection));
    }
}
if ($oper == 'edit') {
    if ($user->TestRoles('1,5')) {
        $filename = mysqli_real_escape_string($sqlcn->idsqlconnection, $filename);
        $SQL = "update files_contract set userfreandlyfilename='$filename' WHERE id='$id'";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не смог изменить файл!" . mysqli_error($sqlcn->idsqlconnection));
    } else {
        echo "-не хватает прав!";
    }
}
?>
