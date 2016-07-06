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


//выводим строку
function PutStr($ss){
    global $pozy,$stepy,$stepx,$image,$fontfile,$iW;    
    $white=imagecolorallocate($image, 255, 255, 255);
    $red=imagecolorallocate($image, 255, 0, 0);
    $green=imagecolorallocate($image, 0, 255, 0);
    $blue=imagecolorallocate($image, 2, 54, 255);
    $yellow=imagecolorallocate($image, 234, 221, 16);
    $black=imagecolorallocate($image, 0, 0, 0);
    $color = $white;
    if (strpos($ss,"<green>")===FALSE){} else {$ss=str_replace("<green>", "", $ss);$color=$green;};     
    if (strpos($ss,"<red>")===FALSE){} else {$ss=str_replace("<red>", "", $ss);$color=$red;};     
    if (strpos($ss,"<blue>")===FALSE){} else {$ss=str_replace("<blue>", "", $ss);$color=$blue;};     
    if (strpos($ss,"<yellow>")===FALSE){} else {$ss=str_replace("<yellow>", "", $ss);$color=$yellow;};     
    if (strpos($ss,"<white>")===FALSE){} else {$ss=str_replace("<white>", "", $ss);$color=$white;};         
    if (strpos($ss,"<black>")===FALSE){} else {$ss=str_replace("<black>", "", $ss);$color=$black;};         
    $shadow = imagecolorallocate($image, 0, 0, 0);    
    $tsize=40;
    if (strpos($ss,"<h1>")===FALSE){} else {
        $ss=str_replace("<h1>", "", $ss);     
        $tsize=60;
    };
    if (strpos($ss,"<h2>")===FALSE){} else {
        $ss=str_replace("<h2>", "", $ss);     
        $tsize=40;
    };    
    if (strpos($ss,"<h3>")===FALSE){} else {
        $ss=str_replace("<h3>", "", $ss);     
        $tsize=20;
    };
    $ts=imagettfbbox($tsize, 0, $fontfile, $ss); //размеры текста
    $stepy=$ts[1]-$ts[7];    
    $pozy=$pozy+$stepy;
        
    if (strpos($ss,"<c>")===FALSE){
        $ts2=imagettfbbox($tsize, 0, $fontfile, "A"); //размеры текста
        $x=$ts2[2]-$ts2[0];        
    } 
    else {
        $ss=str_replace("<c>", "", $ss);        
        $wtxt=$ts[2]-$ts[0];
        $x=round(($iW-$wtxt)/2,0)+$stepy;        
    };    
    $ss=str_replace("<br>","", $ss);     
    imagettftext($image, $tsize, 0, $x+1,$pozy+1,$shadow, $fontfile, $ss);
    imagettftext($image, $tsize, 0, $x,$pozy,$color, $fontfile, $ss);
};

$astra_id=$_GET['astra_id'];

$sql="select * from astra_servers where id='$astra_id'";
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу выбрать список серверов astra_servers!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
  $path=$row["path"];   
};

//создаю все пути на всякий случай
    @mkdir($path."$astra_id");
    @mkdir($path."$astra_id/pic");
$muz_file="";
//создаю кадры
$rez=`rm $path/$astra_id/pic/* -f`;    
$sql="select * from astra_info where astra_id='$astra_id'";    
$result = $sqlcn->ExecuteSQL( $sql ) or die("Не могу выбрать список фреймов!".mysqli_error($sqlcn->idsqlconnection));
$n=0;
while($row = mysqli_fetch_array($result)) {
  $pic_file=$row["pic_file"];
  if ($row["muz_file"]<>""){$muz_file=$row["muz_file"];}
  $tbody=$row["tbody"];
  $tframe=$row["tframe"]*25;
  for ($i=0;$i<=$tframe;$i++){
      $image = imagecreatefromjpeg("../../../photos/$pic_file");
      $fontfile="../../../fonts/arial.ttf";
      $isize=getimagesize("../../../photos/$pic_file");
           
      //размещаем строки текста
      $ar_str=  explode("\n", $tbody);
      $iW=$isize[0];$iH=$isize[1];
      $stepy=round($iH/30,0);
      $stepx=round($iW/60,0);
      $pozy=0;
      for ($s=0;$s<count($ar_str);$s++){
        $ss=$ar_str[$s];
        PutStr($ss);        
      };
      imagejpeg($image, "$path/$astra_id/pic/bk$n.jpg", 75);
      imagedestroy($image);      
      $n++;
  };
  
};
 
//формируем ролик
$rez=`rm $path/$astra_id/informer.ts`;
$rez=`rm $path/$astra_id/informer_nosound.ts`;
$shell="/usr/local/bin/ffmpeg";
$fn="informer_nosound.ts";
if ($muz_file==""){$fn="informer.ts";};

$com="$shell -f image2 -i $path/$astra_id/pic/bk%d.jpg -an -aspect 16:9 -qscale 2 -g 100 -metadata service_provider='provider' -metadata service_name='informer' $path/$astra_id/$fn";
echo "$com</br>";
$rez=shell_exec($com);
echo "$rez</br>";

if ($muz_file!=""){
//добавляем звук
    $com="$shell -i ../../../photos/$muz_file -i $path/$astra_id/informer_nosound.ts -metadata service_provider='provider' -metadata service_name='informer' $path/$astra_id/informer.ts";
    echo "$com</br>";
    $rez=shell_exec($com);   
};

//echo "$rez";

echo "Видеофайл сформирован. Забрать можно сдесь: $path/$astra_id/informer.ts</br>";
//убираю кадры
//$rez=`rm $path/$astra_id/pic/* -f`;    
?>
<script>
$("#pl").html("<img src=controller/server/astra/pic.php?astra_id="+<?php echo "$astra_id";?>+"&r="+getRandomInt(0,100)+" >");
</script>
