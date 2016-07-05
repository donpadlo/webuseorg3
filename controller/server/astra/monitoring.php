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


$astra_id = GetDef('astra_id');
?>    
    <table class="table table-hover table-condensed">
  <thead>	
	<tr>
	    <th>#</th>
	    <th>Название</th>
	    <th>Транспондер</th>
	    <th>Url</th>
	</tr> 
  </thead>    
<?php  
$sql="select * from astra_mon  where astra_id=$astra_id";
//echo "$sql";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц (1)!".mysqli_error($sqlcn->idsqlconnection));
$cnt_kan=0;$cnt=0;
while($row = mysqli_fetch_array($result)) {
    $id=$row["id"];
    $url=$row["url"];
    $tname=$row["name"];
  if ((strpos($url, ":")>0) and (strripos($url,":")>4)) {      
	$cnt++;
	$zx="/usr/local/bin/wget $url/control/ --post-data='{\"cmd\":\"load\"}' -O /tmp/astra".$cnt.".json";	
	`$zx`;
	$json = file_get_contents("/tmp/astra".$cnt.".json");
	$obj = json_decode($json);	
	if ($obj!=NULL){
	    foreach ($obj as $key => $kk) {
		$cnt++;
		if (count($kk)>1){
		    foreach ($kk as $key => $kan) {		    
			if (isset($kan->input[0])){
			    $name=$kan->name;
			    $id_kan=$kan->id;
			    if (strpos($name,"noview")===false){
			    //$input=$kan->input[0];
			    //$output=$kan->output[0];
			    $cnt_kan++;
				echo "<tr>";
				echo "<td>$cnt_kan</td>";
				echo "<td>$name</td>";
				echo "<td>$tname</td>";
				echo "<td>";
				if ($user->TestRoles("1")==true){
				    echo "<a target=\"_blank\"href=\"$url\" class=\"btn btn-primary btn active\" role=\"button\">Панель управления</a>";      
				};
				echo "<div onclick='Reload(\"$id\")' class=\"btn btn-danger btn active\" role=\"button\">Перезагрузить</div></td>";
				echo "</tr>";

			};
			};
		    };
		}; 
	    };
	};      
  };	  
};
echo "</table>";
echo "<div id=mm name=mm></div>";  
?>  
<script>
function Reload(id){
	if (confirm("Вы убеждены что хотите перезагрузить Астру?")) {
	      $("#mm").load(route+"controller/server/astra/restart.php&astra_id="+id);
	      $().toastmessage('showWarningToast', 'Астра перезагружена..!'); 
	};
};
</script>