<?php

/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */


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

$astra_id=$_GET['astra_id'];

$sql="select id,name,comment,path,ftppass,ftplogin,INET_NTOA(ip) as ip from astra_servers where id='$astra_id'";
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу выбрать список серверов astra_servers!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
  $path=$row["path"];   
  $ftplogin=$row["ftplogin"];   
  $ftppass=$row["ftppass"];   
  $ip=$row["ip"];   
};
$zz="wget http://$ip/get_info.php";
echo "$zz</br>";
$res=`$zz`;
echo "$res";

/*
  $connect = ftp_connect($ip);
  if(!$connect){
    die("Ошибка соединения");
  } else {
    echo("Соединение установлено</br>");  
  };

  
  if (ftp_login($connect, $ftplogin, $ftppass)==true){
      echo("Вход по именем $ftplogin -ok</br>");  

        // включение пассивного режима
      ftp_pasv($connect, true);
      
      
      // загрузка файла 
      if (ftp_put($connect, "$path/informer.ts", "$path/$astra_id/informer.ts", FTP_BINARY)) {
       echo "$path/$astra_id/informer.ts успешно загружен на сервер</br>";
      } else {
       echo "Не удалось загрузить $path/$astra_id/informer.ts на сервер</br>";
      }

      // закрытие соединения
      ftp_close($connect);
      
      
  }else {
      die("Ошибка входа");
  };
  
 */ 