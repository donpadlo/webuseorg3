<?php

$idkkm= _POST("idkkm");
$ipaddress= _POST("ipaddress");
$ipport= _POST("ipport");
$model= _POST("model");
$accesspass= _POST("accesspass");
$userpass= _POST("userpass");
$protocol= _POST("protocol");
$logfilename= _POST("logfilename");
$libpath= _POST("libpath");
$version= _POST("version");
$ppath= _POST("ppath");
$kassir= _POST("kassir");
$innk= _POST("innk");
$eorphone= _POST("eorphone");
$testmode= _POST("testmode");

if ($testmode=="false"){$testmode=0;} else {$testmode=1;};

$sql="update online_kkm set ipaddress='$ipaddress',ipport='$ipport',model='$model',accesspass='$accesspass',userpass='$userpass',protocol='$protocol',logfilename='$logfilename',libpath='$libpath', version='$version',testmode='$testmode',ppath='$ppath',kassir='$kassir',innk='$innk',eorphone='$eorphone' where id=$idkkm";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список настроек кассы!" . mysqli_error($sqlcn->idsqlconnection));

echo "Настройки сохранены!";