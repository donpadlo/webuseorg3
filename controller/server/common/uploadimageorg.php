<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
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


function cuttingimg($zoom,$fn,$sz){
    @mkdir("../../../photos/maps");
    $img=imagecreatefrompng("../../../photos/maps/".'0-0-0-'.$fn);  // получаем идентификатор загруженного изрбражения которое будем резать
    $info=getimagesize("../../../photos/maps/".'0-0-0-'.$fn);                          // получаем в массив информацию об изображении
    $w=$info[0];$h=$info[1];    // ширина и высота исходного изображения
    $sx=round($w/$sz,0);        // длинна куска изображения
    $sy=round($h/$sz,0);        // высота куска изображения
    $px=0;$py=0;                // координаты шага "реза"
    //echo "-- $sx,$sy -- $w/$h -- $img !";
    //print_r($info);
    for ($y = 0; $y <= $sz; $y++) {        
        for ($x = 0; $x <= $sz; $x++) {            
             $imgcropped=imagecreatetruecolor($sx,$sy);
             imagecopy($imgcropped,$img,0,0,$px,$py,$sx,$sy);
             imagepng($imgcropped,"../../../photos/maps/".$zoom."-".$y."-".$x."-".$fn);
             $px=$px+$sx;
             //echo "../../images/maps/".$y."-".$x."-".$fn;
            };
            $px=0;$py=$py+$sy;            
        };
//    return "ok";
};


$geteqid=$_POST['geteqid'];
$uploaddir = '../../../photos/maps/';

$userfile_name=strtoupper(basename($_FILES['filedata']['name']));
$len=strlen($userfile_name);
$ext_file=substr($userfile_name,$len-4,$len);

if ($ext_file==".PNG"){
    $tmp=GetRandomId(20);
    $userfile_name=$tmp.$ext_file;
    $uploadfile = $uploaddir.'0-0-0-'.$userfile_name;

    $sr=$_FILES['filedata']['tmp_name'];
    $dest=$uploadfile;
    $rs = array("fname" => "","msg" => "");
    $res=move_uploaded_file($sr,$dest);
    if ($res!=false){
         //echo "0-0-0-$userfile_name";
         $rs = array("fname" => "0-0-0-$userfile_name","msg" => "");
            if ($geteqid!=""){
             	$SQL = "UPDATE org SET picmap='$userfile_name' WHERE id='$geteqid'";
                $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу обновить фото!".mysqli_error($sqlcn->idsqlconnection));
                cuttingimg(1,$userfile_name,2);
                cuttingimg(2,$userfile_name,4);
                cuttingimg(3,$userfile_name,8);
            } else {$rs = array("fname" => "0-0-0-$userfile_name","msg" => "error org");};
     } else {$rs = array("fname" => "0-0-0-$userfile_name","msg" => "error file load");};
}  else {$rs = array("fname" => "0-0-0-$userfile_name","msg" => "Файл не формата png");};
echo  json_encode($rs);
?>