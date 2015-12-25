<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../class/config.php");		// загружаем классы настроек
include_once("../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../class/employees.php");		// загружаем классы работы с профилем пользователя


// загружаем все что нужно для работы движка

include_once("../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../inc/functions.php");		// загружаем функции
include_once("../../../inc/login.php");		// загружаем функции

$kntid = GetDef('kntid');

$SQL = "SELECT * FROM knt WHERE id='$kntid' and active=1";
$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список контрагентов!".mysql_error());
$dogcount=0;
while($row = mysqli_fetch_array($result)) {
    if ($row['dog']=='1'){
        echo '<div class="alert alert-success">Контрагент:';
        $nm=$row['name'];
        echo "$nm<br>";
        $SQL = "SELECT * FROM contract WHERE kntid='$kntid' and work=1 and datestart<=CURDATE() and dateend>=CURDATE() and active=1";
        $result2 = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список договоров!".mysqli_error($sqlcn->idsqlconnection));
        while($row2 = mysqli_fetch_array($result2)) {
            $dogcount++;
            echo '<div class="well"><span class="label label-info">Активный договор:</span></br>';
            $dt1=  MySQLDateToDate($row2['datestart']);
            $dt2=MySQLDateToDate($row2['dateend']);
            $num=$row2['num'];$nm=$row2['name'];
            echo "Номер: $num, $nm</br>";            
            echo "Срок действия с $dt1 по $dt2<br>";
            echo "Файлы: ";
                    $rid=$row2['id'];
                    $SQL = "SELECT * FROM files_contract WHERE idcontract=$rid";
                    $result3 = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список файлов!".mysqli_error($sqlcn->idsqlconnection));
                    while($row3 = mysqli_fetch_array($result3)) {
                        $fn1=$row3['filename'];$fn2=$row3['userfreandlyfilename'];
                        echo "<a target='_blank' href='files/$fn1'>$fn2</a>;";
                    };
            echo "<br>";
            echo "</div>";
        };
        if ($dogcount==0) { 
            echo '<div class="alert alert-error">
                    <b>Внимание!</b> У контрагента нет активных договоров. Обратитесь в юридический отдел!
                  </div>';                    
         };
        echo '</div>';
    } else
    {
        echo '<div class="alert alert-error">';
        $nm=$row['name'];
        echo "<b>Внимание!</b> У контрагента $nm не выставлен конроль договоров. Обратитесь в юридический отдел!";
        echo '</div>';
    };
};

?>
