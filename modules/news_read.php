<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

$newsid=$num=$_GET['id'];
if (isset($_GET["id"])) {$newsid=$num=$_GET['id'];} else {$newsid="1";};
if ($newsid!="") {
    $SQL = "SELECT * FROM news WHERE id=$newsid";
    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список новостей!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
        $news_dt=$row["dt"];
        $news_title=$row["title"];
        $news_body=$row["body"];
    };
};

?>