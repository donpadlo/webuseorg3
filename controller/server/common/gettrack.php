<?php

$userid=  _GET("userid");
$type=  _GET("type");
if ($type=="1"){
    $sql="select dt,longitude as longitude,latitude as latitude from geouserhist where longitude<>'' and userid=$userid and dt between DATE_SUB(NOW(), INTERVAL 1 DAY) and now() group by longitude,latitude order by id;";
} else {
    $sql="select  dt,Nlongitude as longitude,Nlatitude as latitude  from geouserhist where Nlongitude<>'' and userid=$userid and dt between DATE_SUB(NOW(), INTERVAL 1 DAY) and now() group by Nlongitude,Nlatitude order by id;";    
};
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать последние координаты пользователя!".mysqli_error($sqlcn->idsqlconnection));
$coors=array();
$cnt=0;
while($row = mysqli_fetch_array($result)) {
    if ($cnt==0){
     $coors[$cnt]["longitudeSTART"]=$row["longitude"];
     $coors[$cnt]["latitudeSTART"]=$row["latitude"];    
    } else {
    $coors[$cnt]["longitudeSTART"]=$oldlongitude;
    $coors[$cnt]["latitudeSTART"]=$oldlatitude;	
    };
 $coors[$cnt]["longitudeEND"]=$row["longitude"];
 $coors[$cnt]["latitudeEND"]=$row["latitude"];
 $oldlongitude=$row["longitude"];
 $oldlatitude=$row["latitude"];
            $ar1=array();
            $ar2=array();
            $ar3=array();
	    $ar1[]=$coors[$cnt]["latitudeSTART"];	    
            $ar1[]=$coors[$cnt]["longitudeSTART"];            
	    $ar2[]=$coors[$cnt]["latitudeEND"];            	    
            $ar2[]=$coors[$cnt]["longitudeEND"];            
            $ar3[]=$ar1;
            $ar3[]=$ar2;
 $coors[$cnt]["coords"]=$ar3;   	    
 $coors[$cnt]["dt"]=$row["dt"];
 $cnt++;
};
echo json_encode($coors);