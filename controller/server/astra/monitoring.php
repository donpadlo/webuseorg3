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

include("../../../class/pchart/pData.class.php"); 
include("../../../class/pchart/pDraw.class.php"); 
include("../../../class/pchart/pImage.class.php"); 

function dvb($ch,$gr){
global $sqlcn,$astra_id;
$rz=false;
 $sql="select dvb_id from astra_log where astra_id=$astra_id and channel_id=$ch and group_id=$gr order by id desc limit 1";
// echo "$sql</br>";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц (2)!".mysqli_error($sqlcn->idsqlconnection));    
    while($row = mysqli_fetch_array($result)) {
      if ($row["dvb_id"]>0){$rz=true;};
    };
return $rz;  
};

$astra_id = GetDef('astra_id');

//перебираем все каналы на этой астре, с сортировкой по group_id и chanel_id

$fsh=0; //флаг шапки
$cnt=0;

$sql="select * from astra_chanels  where astra_id=$astra_id order by group_id,chanel_id";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц (1)!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
  $chanel_id=$row["chanel_id"];  
  $group_id=$row["group_id"];  
  $name=$row["name"];  

  $sql="select * from astra_log where astra_id=$astra_id and channel_id=$chanel_id and group_id=$group_id and dttm between (now()-interval 2 minute) and now() order by id desc limit 50";
  $result2 = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц (1)!".mysqli_error($sqlcn->idsqlconnection));
  $last=0;
  $ldt="";
  $onair=0;
  $ard=array();
  $snr=array();
  $acnt=array();
   
    $cnt=0;
    while($row2 = mysqli_fetch_array($result2)) {
        if ($row2["dvb_id"]>0){ 
            $ard[]=$row2["a_signal"];   
            $last=$row2["a_signal"];   
            $snr[]=$row2["snr"];   
            $onair=$row2["onair"];   
            $ldt=$row2["dttm"];   
            $acnt[]=$cnt;
            $cnt++;
        } else {
            $ard[]=$row2["bitrate"];   
            $last=$row2["bitrate"];   
            $ldt=$row2["dttm"];   
            $onair=$row2["onair"];   
            $acnt[]=$cnt;
            $cnt++;            
        };    
    };    
if (count($ard)==0){$ard[]=0;};

$myData = new pData();
$myData->addPoints($ard,"Serie1");
$myData->setSerieDescription("Serie1","Serie 1");
$myData->setSerieOnAxis("Serie1",0);
$serieSettings = array("R"=>151,"G"=>50,"B"=>0,"Alpha"=>150);
$myData->setPalette("Serie1",$serieSettings);


$myData->setAxisPosition(0,AXIS_POSITION_LEFT);
$myData->setAxisName(0,"1st axis");
$myData->setAxisUnit(0,"");

$myPicture = new pImage(100,55,$myData);
$myPicture->Antialias = TRUE;
$myPicture->setGraphArea(0,0,100,50);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"../../../fonts/pf_arma_five.ttf","FontSize"=>6));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT, "Mode"=>SCALE_MODE_FLOATING, "LabelingMethod"=>LABELING_ALL, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "DrawXLines"=>0, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "XMargin"=>0, "YMargin"=>0, "DrawYLines"=>NONE);
$myPicture->drawScale($Settings);

$myPicture->setFontProperties(array("FontName"=>"../../../fonts/calibri.ttf","FontSize"=>8));
$TextSettings = array("R"=>15,"G"=>15,"B"=>255,"Angle"=>0);
$Config = "";
if (dvb($chanel_id,$group_id)==true){
  if (($last<=1000)){$Settings = array("R"=>230, "G"=>56, "B"=>30);} else {  
  $Settings = array("R"=>41, "G"=>183, "B"=>31);};
  $myPicture->drawFilledRectangle(0,0,100,55,$Settings);      
  $myPicture->drawSplineChart($Config);  
  $myPicture->drawText(0,20,"Sig:".$last,$TextSettings);   
} else {
  if (($last<=1000) or ($onair==0)){$Settings = array("R"=>230, "G"=>56, "B"=>30);} else {  
  $Settings = array("R"=>76, "G"=>224, "B"=>175);};
  $myPicture->drawFilledRectangle(0,0,100,55,$Settings);          
  $myPicture->drawSplineChart($Config);  
  $myPicture->drawText(0,20,"Bit:".$last,$TextSettings);   
};

$myPicture->drawText(0,10,$name,$TextSettings); 
$myPicture->drawText(0,50,$ldt,$TextSettings); 

$rnd=GetRandomId(10);
$myPicture->render("../../../files/tmp".$chanel_id.$group_id.".png"); 
  
echo "<img src='/files/tmp".$chanel_id.$group_id.".png?".$rnd."'> ";

};

echo "<div class='alert alert-success'><ul>";
echo "<button onclick='openMonurl($astra_id)'>Обновить</button>";
echo "</ul></div>";

