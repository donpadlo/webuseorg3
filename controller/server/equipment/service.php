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

if (isset($_GET["step"])) {
    $step = $_GET["step"];
} else {
    $step = "";
}
;
if (isset($_GET["eqid"])) {
    $eqid = $_GET["eqid"];
} else {
    $eqid = "";
}
;
if (isset($_POST["oper"])) {
    $oper = $_POST['oper'];
} else {
    $oper = "";
}
;
if (isset($_POST["id"])) {
    $id = $_POST['id'];
} else {
    $id = "";
}
;
$responce = new stdClass();
if ($id != "") {
    $eqid = $id;
}
;

// Выполняем токма если юзер зашел!
if ($user->TestRoles("1,4,5,6") == true) {
    if ($step == 'add') {
        $dtpost = DateToMySQLDateTime2($_POST["dtpost"] . " 00:00:00");
        if ($dtpost == "") {
            $err[] = "Не выбрана дата!";
        }
        ;
        $dt = $_POST["dt"];
        if ($dt == "") {
            $dt = "9999-12-01 00:00:00";
        } else {
            $dt = DateToMySQLDateTime2($dt . " 00:00:00");
        }
        ;
        $kntid = $_POST["kntid"];
        if ($kntid == "") {
            $err[] = "Не выбран контрагент!";
        }
        ;
        if (isset($_POST["cst"])) {
            $cst = $_POST["cst"];
        } else {
            $cst = "";
        }
        ;
        if (isset($_POST["status"])) {
            $status = $_POST["status"];
        } else {
            $status = "";
        }
        ;
        if (isset($_POST["comment"])) {
            $comment = $_POST["comment"];
        } else {
            $comment = "";
        }
        ;
        if (isset($_POST["doc"])) {
            $doc = $_POST["doc"];
        } else {
            $doc = "";
        }
        ;
        if (isset($_POST["suserid1"])) {
            $suserid1 = $_POST["suserid1"];
        } else {
            $suserid1 = "-1";
        }
        ;
        if (isset($_POST["suserid2"])) {
            $suserid2 = $_POST["suserid2"];
        } else {
            $suserid2 = "-1";
        }
        ;
        if (count($err) == 0) {
            $sql = "INSERT INTO repair (id,dt,kntid,eqid,cost,comment,dtend,status,userfrom,userto,doc)
            VALUES (NULL,'$dtpost','$kntid','$eqid','$cst','$comment','$dt','1','$suserid1','$suserid2','$doc')";
            $result = $sqlcn->ExecuteSQL($sql);
            if ($result == '') {
                die('Не смог добавить ремонт!: ' . mysqli_error($sqlcn->idsqlconnection));
            }
            // ставим статус "ремонт", только если нужен сервис в общем списке ТМЦ
            if ($status != 0) {
                $sql = "UPDATE equipment SET repair='$status' WHERE id='$eqid'";
                $result = $sqlcn->ExecuteSQL($sql);
                if ($result == '') {
                    die('Не смог обновить запись о ремонте!: ' . mysqli_error($sqlcn->idsqlconnection));
                }
            }
            ;
        }
        ;
    }
    ;
    if ($step == 'edit') {
        
        $dt = DateToMySQLDateTime2($_POST["dtpost"] . " 00:00:00");
        $dtend = DateToMySQLDateTime2($_POST["dt"] . " 00:00:00");
        $cost = $_POST['cst'];
        $comment = $_POST['comment'];
        $rstatus = $_POST['status'];
        $doc = $_POST['doc'];
        $suserid1 = $_POST['suserid1'];
        $suserid2 = $_POST['suserid2'];
        $kntid = $_POST["kntid"];
        $SQL = "UPDATE repair SET dt='$dt',dtend='$dtend',cost='$cost',comment='$comment',status='$rstatus',doc='$doc',userfrom='$suserid1',userto='$suserid2',kntid='$kntid' WHERE id='$eqid'";
        // echo "$SQL";
        $result = $sqlcn->ExecuteSQL($SQL) or die("Не смог обновить статус ремонта!" . mysqli_error($sqlcn->idsqlconnection));
        ReUpdateRepairEq();
    }
    ;
}
;

if ($step != "list") {
    if (count($err) == 0) {
        echo "ok";
    } else {
        echo '<script>$("#messenger").addClass("alert alert-error");</script>';
        for ($i = 0; $i <= count($err); $i ++) {
            echo "$err[$i]<br>";
        }
        ;
    }
    ;
}
;
?>
