<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../../class/employees.php");		// загружаем классы работы с профилем пользователя
include_once("../../../../class/bp.php");		// загружаем классы работы c "Бизнес процессами"


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся
?>
<table class="table table-condensed table-hover table-striped">
  <thead>
    <tr>
      <th>№</th>
      <th>Дата</th>
      <th>Исполнитель</th>
      <th>Задача</th>
      <th>Результат</th>
      <th>Комментарий</th>
    </tr>
  </thead>  
 <tbody> 
<?php
$bpid=GetDef("bpid");
$bp=new Tbp;
$bp->GetById($bpid);
$sql="SELECT * FROM bp_xml_userlist WHERE bpid='$bpid' order by id";
  		$result = $sqlcn->ExecuteSQL($sql);                
  		if ($result!=''){                    
                    while ($myrow = mysqli_fetch_array($result)){
                    $numid=$myrow['id'];
                    $cm=$myrow['comment'];
                    $uz=new Tusers;
                    $uz->GetById($myrow['userid']);
                    $uname=$uz->fio;
                    $bpnode=$bp->GetTitleAndCommentNode($myrow['node']);                        
                    $bpnodetitle=$bpnode['title'];
                    $bpnodecomment=$bpnode['comment'];
                    $rz='Пока не определено';
                    if ($myrow['result']==$myrow['accept']) {$rz='Утвердить';};
                    if ($myrow['result']==$myrow['cancel']) {$rz='Отменить';};
                    if ($myrow['result']==$myrow['thinking']) {$rz='Доработать';};
                    if ($myrow['result']==$myrow['yes']) {$rz='Да';};
                    if ($myrow['result']==$myrow['no']) {$rz='Нет';};
                    if ($myrow['result']==$myrow['one']) {$rz='Выбран вариант 1';};
                    if ($myrow['result']==$myrow['two']) {$rz='Выбран вариант 2';};
                    if ($myrow['result']==$myrow['three']) {$rz='Выбран вариант 3';};
                    if ($myrow['result']==$myrow['four']) {$rz='Выбран вариант 4';};
                    if ($myrow['node']==$bp->node) {echo "<tr class='error'>";} else {echo "<tr>";}
                    echo "<td>$numid</td>";
                    $tt=MySQLDateTimeToDateTime($myrow['dtend']);
                    echo "<td>$tt</td>";
                    echo "<td>$uname</td>";
                    echo "<td><strong>$bpnodetitle</strong><br>$bpnodecomment</td>";
                    echo "<td>$rz</td>";
                    echo "<td>$cm</td>";
                    echo "</tr>";
                    unset ($uz);
                    };
                }  else {die('Неверный запрос при выборке шагов БП: ' . mysqli_error($sqlcn->idsqlconnection));}

?>
</tbody>     
</table>