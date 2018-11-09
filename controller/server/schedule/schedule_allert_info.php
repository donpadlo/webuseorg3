<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sql = "select * from schedule where now() between dtstart and dtend and (sms=1 or mail=1 or view=1)";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список заданий!" . mysqli_error($sqlcn->idsqlconnection));
while ($row = mysqli_fetch_array($result)) {
    $title = $row["title"];
    $comment = $row["comment"];
    $dtstart = $row["dtstart"];
    $dtend = $row["dtend"];
    $sms = $row["sms"];
    $mail = $row["mail"];
    $view = $row["view"];
    if ($view == 1) {
        
        echo '<div class="container-fluid"> 
		<div class="row-fluid">';
        echo "<div class='alert alert-success'>";
        if ($sms == 1) {
            $sms = "Да";
        } else {
            $sms = "Нет";
        }
        ;
        if ($mail == 1) {
            $mail = "Да";
        } else {
            $mail = "Нет";
        }
        ;
        if ($view == 1) {
            $view = "Да";
        } else {
            $view = "Нет";
        }
        ;
        echo "<strong>$title</strong><br>";
        echo "С $dtstart по $dtend </br>";
        echo "<pre>$comment</pre></br>";
        echo "Запрет СМС: $sms Запрет почты: $mail</br>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    ;
}
;


    
