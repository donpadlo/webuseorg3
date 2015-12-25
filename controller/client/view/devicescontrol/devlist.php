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


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся
include_once("../../../../inc/lbfunc.php");		// загружаем функции

$devid=GetDef("devid");

$SQL = "SELECT id,dname,command,bcolor FROM devnames WHERE devid='$devid' ";
$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список устройств!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
  $btname=$row["dname"];
  $id=$row["id"];
  $bcolor=$row["bcolor"];
  if (RulesDevices($user->id,$id)==true){echo "<input id='name$id' class='btn $bcolor' type='button' value='$btname'>";};
?>
<script>
$("#name<?php echo "$id";?>").click(function() {// обрабатываем отправку формы
    if (confirm("Вы подтверждаете что хотите ЭТО сделать?")) {
        $.get( "controller/client/themes/bootstrap/img/loading.gif", function( data ) {
                $("#term").html("<img scr='controller/client/themes/bootstrap/img/loading.gif'>");        
         });

        $("#term").load("controller/client/view/devicescontrol/term.php?id="+<?php echo "$id";?>);
        };    
});
</script>  
<?php
};
