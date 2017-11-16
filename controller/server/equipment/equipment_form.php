<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф
defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

include_once (WUO_ROOT . '/class/class.phpmailer.php'); // Класс управления почтой
include_once (WUO_ROOT . '/class/equipment.php');
 // Класс управления почтой
function SendEmailByPlaces($plid, $title, $txt)
{
    global $sqlcn;
    $sql = "SELECT userid AS uid, users.email AS email FROM places_users
		INNER JOIN users ON users.id = places_users.userid
		WHERE places_users.placesid=$plid AND users.email<>''";
    $result = $sqlcn->ExecuteSQL($sql);
    while ($row = mysqli_fetch_array($result)) {
        smtpmail($row['email'], $title, $txt);
    }
}

$step = GetDef('step');
$sorgid = PostDef('sorgid');
$splaces = PostDef('splaces');
$suserid = PostDef('suserid');

// Выполняем только при наличии у пользователя соответствующей роли
// http://грибовы.рф/wiki/doku.php/основы:доступ:роли

if (($user->TestRoles('1,4,5,6')) && ($step != '')) {
    if ($step != 'move') {
        $dtpost = DateToMySQLDateTime2(PostDef('dtpost') . ' 00:00:00');
        $dtendgar = DateToMySQLDateTime2(PostDef('dtendgar') . ' 00:00:00');
        if ($dtpost == '') {
            $err[] = 'Не выбрана дата!';
        }
        if ($sorgid == '') {
            $err[] = 'Не выбрана организация!';
        }
        if ($splaces == '') {
            $err[] = 'Не выбрано помещение!';
        }
        if ($suserid == '') {
            $err[] = 'Не выбран пользователь!';
        }
        $sgroupname = PostDef('sgroupname');
        if ($sgroupname == '') {
            $err[] = 'Не выбрана группа номенклатуры!';
        }
        $svendid = PostDef('svendid');
        if ($svendid == '') {
            $err[] = 'Не выбран производитель!';
        }
        $snomeid = PostDef('snomeid');
        if ($snomeid == '') {
            $err[] = 'Не выбрана номенклатура!';
        }
        $kntid = PostDef('kntid');
        if ($kntid == '') {
            $err[] = 'Не выбран поставщик!';
        }
        $os = PostDef('os', '0');
        $mode = PostDef('mode', '0');
        $mapyet = PostDef('mapyet', '0');
        $buhname = PostDef('buhname');
        $sernum = PostDef('sernum');
        $invnum = PostDef('invnum');
        $shtrihkod = PostDef('shtrihkod');
        $cost = PostDef('cost');
        $picphoto = PostDef('picname');
        $currentcost = PostDef('currentcost');
        $comment = PostDef('comment');
        $ip = PostDef('ip');
    } else {
        if ($sorgid == '') {
            $err[] = "Не выбрана организация!";
        }
        if ($splaces == '') {
            $err[] = 'Не выбрано помещение!';
        }
        if ($suserid == '') {
            $err[] = 'Не выбран пользователь!';
        }
        if (isset($_POST['tmcgo'])) {
            $tmcgo = ($_POST['tmcgo'] == 'on') ? '1' : '0';
        } else {
            $tmcgo = '0';
        }
        $comment = PostDef('comment');
    }
    
    // Добавляем родимую
    if ($step == 'add') {
        if (count($err) == 0) {
            $sql = "INSERT INTO equipment (id, orgid, placesid, usersid, nomeid, buhname, datepost, cost,
				currentcost, sernum, invnum, shtrihkod, os, mode, comment, active, ip, mapyet, photo,
				kntid, dtendgar) VALUES (NULL, '$sorgid', '$splaces', '$suserid', '$snomeid', '$buhname', 
				'$dtpost', '$cost', '$currentcost', '$sernum', '$invnum', '$shtrihkod', '$os', 
				'$mode', '$comment', '1', '$ip', '$mapyet', '$picphoto', '$kntid', '$dtendgar')";
            $sqlcn->ExecuteSQL($sql) or die('Не смог добавить номенклатуру!: ' . mysqli_error($sqlcn->idsqlconnection));
            $eqid = mysqli_insert_id($sqlcn->idsqlconnection);
            // добавляем в регистр хранения инфу о добавлении
            $sql = "insert into register (id,dt,eqid,moveid,cnt,orgid,placesid,usersid) values (null,'$dtpost',$eqid,null,1,$sorgid,$splaces,$suserid)";
            $sqlcn->ExecuteSQL($sql) or die('Не смог добавить в регистр!: ' . mysqli_error($sqlcn->idsqlconnection));
            
            if ($cfg->sendemail == 1) {
                // $txt="Внимание! На Вашу ответственность переведена новая единица ТМЦ. <a href=$url?content_page=eq_list&usid=$suserid>Подробности здесь.</a>";
                // smtpmail("$touser->email","Уведомление о перемещении ТМЦ",$txt);
                // SendEmailByPlaces($splaces,"Изменился состав ТМЦ в помещении","Внимание! В закрепленном за вами помещении изменился состав ТМЦ. <a href=$url?content_page=eq_list>Подробнее здесь.</a>");
            }
            ;
        }
    }
    
    if ($step == 'edit') {
        if (count($err) == 0) {
            $id = GetDef('id');
            $buhname = mysqli_real_escape_string($sqlcn->idsqlconnection, $buhname);
            $sql = "UPDATE equipment SET usersid='$suserid', nomeid='$snomeid', buhname='$buhname',
				datepost='$dtpost', cost='$cost', currentcost='$currentcost', sernum='$sernum', invnum='$invnum',
				shtrihkod='$shtrihkod', os='$os', mode='$mode', comment='$comment', photo='$picphoto', ip='$ip',
				mapyet='$mapyet', kntid='$kntid', dtendgar='$dtendgar' WHERE id='$id'";
            $sqlcn->ExecuteSQL($sql) or die('Не смог изменить номенклатуру!: ' . mysqli_error($sqlcn->idsqlconnection));
            // изменяем регистр
            $sql = "update register set dt='$dtpost',placesid=$splaces,usersid=$suserid where eqid=$id and moveid is null";
            $sqlcn->ExecuteSQL($sql) or die('Не смог изменить регистр!: ' . mysqli_error($sqlcn->idsqlconnection));
        }
    }
    
    if ($step == 'move') {
        if (count($err) == 0) {
            $id = GetDef('id');
            $etmc = new Tequipment();
            $idar = explode(",", $id);
            foreach ($idar as $id) {
                $etmc->GetById($id);
                $sql = "UPDATE equipment SET tmcgo='$tmcgo', mapmoved=1, orgid='$sorgid',
				    placesid='$splaces', usersid='$suserid' WHERE id='$id'";
                $result = $sqlcn->ExecuteSQL($sql);
                if ($result == '') {
                    $err[] = 'Не смог изменить регистр номенклатуры - перемещение!: ' . mysqli_error($sqlcn->idsqlconnection);
                }
                $sql = "INSERT INTO move (id, eqid, dt, orgidfrom, orgidto, placesidfrom, placesidto, useridfrom, useridto, comment)
					    VALUES (NULL, '$id', NOW(), '$etmc->orgid', '$sorgid', '$etmc->placesid', '$splaces', '$etmc->usersid',
					    '$suserid', '$comment')";
                $result = $sqlcn->ExecuteSQL($sql);
                if ($result == '') {
                    $err[] = 'Не смог добавить перемещение!: ' . mysqli_error($sqlcn->idsqlconnection);
                } else {
                    // двигаю регистры
                    // убавляю откуда переместили
                    $move_id = mysqli_insert_id($sqlcn->idsqlconnection);
                    $sql = "insert into register (id,dt,eqid,moveid,cnt,orgid,placesid,usersid) values (null,now(),$id,$move_id,-1,'$etmc->orgid','$etmc->placesid','$etmc->usersid')";
                    $sqlcn->ExecuteSQL($sql) or die('Не смог убавить в регистре!: ' . mysqli_error($sqlcn->idsqlconnection));
                    // добавляю куда переместили
                    $sql = "insert into register (id,dt,eqid,moveid,cnt,orgid,placesid,usersid) values (null,now(),$id,$move_id,1,'$sorgid','$splaces','$suserid')";
                    $sqlcn->ExecuteSQL($sql) or die('Не смог убавить в регистре!: ' . mysqli_error($sqlcn->idsqlconnection));
                }
                ;
                if ($cfg->sendemail == 1) {
                    $touser = new Tusers();
                    $touser->GetById($suserid);
                    $url = $cfg->urlsite;
                    $tmcname = $etmc->tmcname;
                    $txt = "Внимание! На Вашу ответственность переведена новая единица ТМЦ ($tmcname). <a href=$url/index.php?content_page=eq_list&usid=$suserid>Подробности здесь.</a>";
                    smtpmail("$touser->email", "Уведомление о перемещении ТМЦ", $txt); // отсылаем уведомление кому пришло
                    SendEmailByPlaces($etmc->placesid, "Изменился состав ТМЦ в помещении", "Внимание! В закрепленном за вами помещении изменился состав ТМЦ. <a href=$url/index.php?content_page=eq_list>Подробнее здесь.</a>");
                    SendEmailByPlaces($splaces, "Изменился состав ТМЦ в помещении", "Внимание! В закрепленном за вами помещении изменился состав ТМЦ. <a href=$url/index.php?content_page=eq_list>Подробнее здесь.</a>");
                    $touser = new Tusers();
                    $touser->GetById($etmc->usersid);
                    $txt = "Внимание! С вашей отвественности снята единица ТМЦ ($tmcname). <a href=$url/index.php?content_page=eq_list&usid=$etmc->usersid>Подробности здесь.</a>";
                    smtpmail("$touser->email", "Уведомление о перемещении ТМЦ", $txt);
                }
            }
            ;
            unset($etmc);
        }
    }
}

if (count($err) == 0) {
    echo 'ok';
} else {
    echo "<script>$('#messenger').addClass('alert alert-error');</script>";
    for ($i = 0; $i <= count($err); $i ++) {
        echo "$err[$i]<br>";
    }
}
