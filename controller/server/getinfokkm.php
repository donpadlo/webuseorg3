<?php

$idkkm= _POST("idkkm");

$sql = "SELECT * FROM online_kkm where id='$idkkm'";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список настроек!" . mysqli_error($sqlcn->idsqlconnection));
$param=array();
$param["mode"]="getinfo";
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
    $param["version"] = $row['version'];
    $ppath= $row['ppath'];
};
$jsonparam= base64_encode(json_encode($param));
$command = "/usr/bin/env python3 $ppath $jsonparam 2>&1";
//echo "$command";
$pid = popen( $command,"r");
while( !feof( $pid ) )
{
 echo fread($pid, 256);
 flush();
 ob_flush();
 usleep(100000);
}
pclose($pid);

?>