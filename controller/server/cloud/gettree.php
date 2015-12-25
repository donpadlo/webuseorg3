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

$foldername=GetDef('foldername'); 

function GetTree($key){
    global $sqlcn;
    $sql="select * from cloud_dirs where parent=$key";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу прочитать папку!!".mysqli_error($sqlcn->idsqlconnection));
    $cnt=mysqli_num_rows($result);    
    if ($cnt!=0){
    $pz=0;
    while($row = mysqli_fetch_array($result)) {    
        $name=$row["name"];
        $key=$row["id"];
        echo "{";
            echo "\"title\": \"$name\",\"isFolder\": true,\"key\": \"$key\",\"children\": [";
            GetTree($key);
        echo "]}";    
        $pz++;if ($pz<$cnt){echo ",";};
    };
    };
};

//читаю корневые папки
$sql="select * from cloud_dirs where parent=0";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу прочитать папку!!".mysqli_error($sqlcn->idsqlconnection));
$cnt=mysqli_num_rows($result);
echo "[";
$pz=0;
while($row = mysqli_fetch_array($result)) {    
    $name=$row["name"];
    $key=$row["id"];
    echo "{";
        echo "\"title\": \"$name\",\"isFolder\": true,\"key\": \"$key\",\"children\": [";
        GetTree($key);
    echo "]}";    
    $pz++;if ($pz<$cnt){echo ",";};
};
echo "]";    

?>



  



