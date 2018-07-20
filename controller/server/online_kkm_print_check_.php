<?php

$check_id= _POST("check_id");
$mode= _POST("mode");
$param=array();
$param["mode"]=$mode;


//по номеру чека, узнаем idkkm, email и всё остальное для проведения платежа
$sql="select * from online_payments where id=$check_id and status<>1";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список настроек!" . mysqli_error($sqlcn->idsqlconnection));
$idkkm="";
while ($row = mysqli_fetch_array($result)) {
    $idkkm=$row["kassaid"];
    $param["numcheck"]=$row["numcheck"];
    $param["docdate"]=$row["docdate"];
    $param["summdoc"]=$row["summdoc"];
    $param["dognum"]=$row["dognum"];
    $param["eorphone"]=$row["eorphone"];
    //$param["goods"]=  json_decode($row["goodsjson"]);    
    //var_dump(json_decode($row["goodsjson"]));
};
if ($idkkm!=""){
    // по idkkm узнаем информацию для соединения
    $sql = "SELECT * FROM online_kkm where id='$idkkm'";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список настроек!" . mysqli_error($sqlcn->idsqlconnection));
    while ($row = mysqli_fetch_array($result)) {
	$param["ipaddress"] = $row['ipaddress'];
	$param["ipport"] = $row['ipport'];
	$param["model"] = $row['model'];
	$param["accesspass"] = $row['accesspass'];
	$param["userpass"] = $row['userpass'];
	$param["protocol"] = $row['protocol'];
	$param["logfilename"] = $row['logfilename'];
	$param["testmode"] = $row['testmode'];
	$param["libpath"] = $row['libpath'];
	$param["kassir"] = $row['kassir'];
	$param["innk"] = $row['innk'];
	$param["eorphone"] = $row['eorphone'];	
	$ppath= $row['ppath'];
    };
    $jsonparam= base64_encode(json_encode($param));
    $com="/usr/bin/env python3 $ppath $jsonparam 2>&1";    
    echo $com;
    $res = `$com`;
    echo $res;  
} else {
  echo "Чек уже пробит!";  
};


?>