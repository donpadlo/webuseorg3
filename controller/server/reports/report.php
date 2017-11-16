<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
include_once ("../../../config.php"); // загружаем первоначальные настройки
                                      
// загружаем классы

include_once ("../../../class/sql.php"); // загружаем классы работы с БД
include_once ("../../../class/config.php"); // загружаем классы настроек
include_once ("../../../class/users.php"); // загружаем классы работы с пользователями
include_once ("../../../class/employees.php"); // загружаем классы работы с профилем пользователя
                                              
// загружаем все что нужно для работы движка

include_once ("../../../inc/connect.php"); // соеденяемся с БД, получаем $mysql_base_id
include_once ("../../../inc/config.php"); // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once ("../../../inc/functions.php"); // загружаем функции
include_once ("../../../inc/login.php"); // соеденяемся с БД, получаем $mysql_base_id

$responce = new stdClass();
if (isset($_GET["page"])) {
    $page = $_GET['page'];
} else {
    $page = "";
}
;
if (isset($_GET["rows"])) {
    $limit = $_GET['rows'];
} else {
    $limit = "";
}
;
if (isset($_GET["sidx"])) {
    $sidx = $_GET['sidx'];
} else {
    $sidx = "";
}
;
if (isset($_GET["sord"])) {
    $sord = $_GET['sord'];
} else {
    $sord = "";
}
;
if (isset($_POST["oper"])) {
    $oper = $_POST['oper'];
} else {
    $oper = "";
}
;
if (isset($_GET["curuserid"])) {
    $curuserid = $_GET['curuserid'];
} else {
    $curuserid = "";
}
;
if (isset($_GET["curplid"])) {
    $curplid = $_GET['curplid'];
} else {
    $curplid = "";
}
;
if (isset($_GET["curorgid"])) {
    $curorgid = $_GET['curorgid'];
} else {
    $curorgid = "";
}
;
if (isset($_GET["tpo"])) {
    $tpo = $_GET['tpo'];
} else {
    $tpo = "";
}
;
if (isset($_GET["os"])) {
    $os = $_GET['os'];
} else {
    $os = "";
}
;
if (isset($_GET["repair"])) {
    $repair = $_GET['repair'];
} else {
    $repair = "";
}
;
if (isset($_GET["mode"])) {
    $mode = $_GET['mode'];
} else {
    $mode = "";
}
;
if (isset($_POST["id"])) {
    $id = $_POST['id'];
} else {
    $id = "";
}
;

$where = "";
if ($curuserid != '-1') {
    $where = $where . " and equipment.usersid='$curuserid'";
}
if ($curplid != '-1') {
    $where = $where . " and equipment.placesid='$curplid'";
}
if ($curorgid != '-1') {
    $where = $where . " and equipment.orgid='$curorgid'";
}
if ($os == 'true') {
    $where = $where . " and equipment.os=1";
}
if ($repair == 'true') {
    $where = $where . " and equipment.repair=1";
}
if ($mode == 'true') {
    $where = $where . " and equipment.mode=1";
}
if ($tpo == '2') {
    $where = $where . " and equipment.mode=0  and equipment.os=0";
}

if ($oper == '') {
    if (! $sidx)
        $sidx = 1;
    $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count,places.name as plname,res.* FROM places INNER JOIN(
                SELECT name AS namenome, eq . *  FROM nome INNER JOIN (
                SELECT equipment.id AS eqid, equipment.placesid AS plid, equipment.nomeid AS nid, equipment.buhname AS bn, equipment.cost AS cs, equipment.currentcost AS curc, equipment.invnum, equipment.sernum, equipment.shtrihkod, equipment.mode, equipment.os FROM equipment 
                WHERE equipment.active =1" . $where . ")
                AS eq ON nome.id = eq.nid)
                AS res ON places.id=res.plid") or die("Не могу выбрать сформировать список по оргтехнике/помещениям/пользователю!(1)" . mysqli_error($sqlcn->idsqlconnection));
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
    $SQL = "SELECT name as grname,res2.* FROM group_nome INNER JOIN (SELECT places.name as plname,res.* FROM places  INNER JOIN(
                SELECT name AS namenome,nome.groupid as grpid, eq . *  FROM nome INNER JOIN (
                SELECT equipment.id AS eqid, equipment.placesid AS plid, equipment.nomeid AS nid, equipment.buhname AS bn, equipment.cost AS cs, equipment.currentcost AS curc, equipment.invnum, equipment.sernum, equipment.shtrihkod, equipment.mode, equipment.os FROM equipment 
                WHERE equipment.active =1" . $where . ")
                AS eq ON nome.id = eq.nid)
                AS res ON places.id=res.plid) AS res2 ON group_nome.id=res2.grpid ORDER BY $sidx $sord LIMIT $start , $limit";
    // echo "!$SQL!";
    $result = $sqlcn->ExecuteSQL($SQL) or die("Не могу выбрать сформировать список по оргтехнике/помещениям/пользователю!" . mysqli_error($sqlcn->idsqlconnection));
    
    $responce->page = $page;
    $responce->total = $total_pages;
    $responce->records = $count;
    $i = 0;
    while ($row = mysqli_fetch_array($result)) {
        $responce->rows[$i]['id'] = $row['eqid'];
        $responce->rows[$i]['cell'] = array(
            $row['eqid'],
            $row['plname'],
            $row['namenome'],
            $row['grname'],
            $row['invnum'],
            $row['sernum'],
            $row['shtrihkod'],
            $row['mode'],
            $row['os'],
            $row['bn'],
            $row['cs'],
            $row['curc']
        );
        $i ++;
    }
    echo json_encode($responce);
}
;

?>