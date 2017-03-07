<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

// по какой организации
$orgid=GetDef('orgid');
$splaces=GetDef('splaces');
$speoples=GetDef('speoples');
$stmc=GetDef('stmc');

$dtstart=_GET("dtstart");
$dtend=_GET("dtend");

$str_exp = explode(".", $dtstart);
if (count($str_exp)==0){$str_exp = explode("/", $dtstart);};  
$dtstart=$str_exp[2]."-".$str_exp[1]."-".$str_exp[0]." 00:00:00";

$str_exp = explode(".", $dtend);
if (count($str_exp)==0){$str_exp = explode("/", $dtend);};  
$dtend=$str_exp[2]."-".$str_exp[1]."-".$str_exp[0]." 23:59:59";


//заполняю шапку для отчета!
echo "<dl>";

echo "<dt>Период:</dt><dd> с $dtstart по $dtend</dd>";

$orgname="";
$sql = "SELECT * FROM org WHERE active=1 and id in ($orgid) ORDER BY binary(name)";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список организаций!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
    $orgname=$orgname.$row["name"].",";
};
echo "<dt>Организация(и):</dt><dd>$orgname</dd>";

$placesname="";
$sql = "SELECT * FROM places WHERE active=1 and id in ($splaces) ORDER BY binary(name)";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список помещений!!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
    $placesname=$placesname.$row["name"].",";
};
if ($placesname==""){$placesname="Не выбрано";};
echo "<dt>Помещение(я):</dt><dd>$placesname</dd>";

$peoplesnames="";
$sql = "SELECT users.id, users.login, users_profile.fio FROM users INNER JOIN users_profile ON users.id = users_profile.usersid WHERE users.active=1 and users.id in ($speoples) ORDER BY users.login";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список ответственных!!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
    $peoplesnames=$peoplesnames.$row["fio"].",";
};
if ($peoplesnames==""){$peoplesnames="Не выбрано";};
echo "<dt>Ответственные:</dt><dd>$peoplesnames</dd>";

$tmcnames="";
$sql = "SELECT * from nome WHERE nome.active=1 and id in ($stmc)";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список ТМЦ!!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
    $tmcnames=$tmcnames.$row["name"].",";
};
if ($tmcnames==""){$tmcnames="Не выбрано";};
echo "<dt>ТМЦ:</dt><dd>$tmcnames</dd>";


echo "</dl>";
?>
<table class="table table-hover table-condensed">
<thead>
    <tr class="success">      
      <th>№</th>	
      <th>Номенклатура</th>
      <th>Начало</th>
      <th>Поступило</th>
      <th>Ушло</th>
      <th>Конец</th>
    </tr>     
  </thead>

<?php

$where=" equipment.active=1 ";
if ($orgid!="null"){
    $where=$where." and register.orgid in ($orgid) ";
};
if ($splaces!="null"){
    $where=$where." and register.placesid in ($splaces) ";
};
if ($speoples!="null"){
   $where=$where." and register.usersid in ($speoples) "; 
};
if ($stmc!="null"){
   $where=$where." and equipment.nomeid in ($stmc) ";
};

//листаем список ТМЦ
$cnt=0;
$sql="select nome.name as nname,register.*,equipment.nomeid from register inner join equipment on equipment.id=register.eqid inner join nome on nome.id=equipment.nomeid where $where  group by equipment.nomeid";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список ТМЦ!!".mysqli_error($sqlcn->idsqlconnection));
//echo "$sql";
$itcntstart=0;
$itcntend=0;
while($row = mysqli_fetch_array($result)) {
  //  если на момент начала остаток не ноль, то вывожу в таблицу номенклатуру..
  $rnomeid=$row["nomeid"];  
  $rnname=$row["nname"];  
  $reqid=$row["eqid"];  

  $cntstart=CountOnDate($dtstart,$orgid,$splaces,$speoples,$rnomeid);  
  $itcntstart=$itcntstart+$cntstart;
  $cntend=CountOnDate($dtend,$orgid,$splaces,$speoples,$rnomeid);  
  $itcntend=$itcntend+$cntend;
  $movecount=MoveCountBetween($dtstart,$dtend,$orgid,$splaces,$speoples,$rnomeid);
  $addcount=GetCountBetween($dtstart,$dtend,$orgid,$splaces,$speoples,$rnomeid,1);
  $addcountnull=GetCountBetween($dtstart,$dtend,$orgid,$splaces,$speoples,$rnomeid,0);
  $subcount=GetCountBetween($dtstart,$dtend,$orgid,$splaces,$speoples,$rnomeid,-1);
  
  $addarr=GetBetweenArr($dtstart,$dtend,$orgid,$splaces,$speoples,$rnomeid,1);
  $subarr=GetBetweenArr($dtstart,$dtend,$orgid,$splaces,$speoples,$rnomeid,-1);
  if (($cntstart>0) or ($cntend>0) or ($movecount>0)){
    $cnt++;  
    echo "<tr>";  
    echo "<td>$cnt</td>";
    echo "<td>$rnname</td>";
    echo "<td>$cntstart</td>";    
    echo "<td>$addcount";
    if ($addcount!=0){	
	echo "</br>Из них: ";
    };
    //ищу оприходования за этот период
     if ($addcountnull!=0){	
	echo " оприходовано: $addcountnull";
     };
     if (count($addarr)>0){
	 $cc=count($addarr);	 
	 echo "<button type='button' class='btn' data-toggle='collapse' data-target='#clin$cnt'>перемещено $cc</button>\n";
	 echo '<div id="clin'.$cnt.'" class="collapse">'."\n";
	 echo '<table class="table table-hover table-condensed">
		<thead>
		    <tr>      
		      <th>id</th>
		      <th>Откуда</th>
		      <th>Дата</th>
		      <th>Комментарий</th>
		    </tr>     
		  </thead>';	 
	 foreach ($addarr as $value) {
	     echo "<tr>";
	     echo "<td>".$value["id"]."</td>";
	     echo "<td>".$value["placename"]."</td>";
	     echo "<td>".$value["dt"]."</td>";
	     echo "<td>".$value["comment"]."</td>";
	     echo "</tr>";
	 }; 
	 echo "</table>";
	 echo "</div>";
     };
    echo "</td>";
    echo "<td>\n";
	 echo "<button type='button' class='btn' data-toggle='collapse' data-target='#cl$cnt'>$subcount</button>\n";
	    if ($subcount>0){
		echo '<div id="cl'.$cnt.'" class="collapse">'."\n";
		echo '<table class="table table-hover table-condensed">
		       <thead>
			   <tr>      
			     <th>id</th>
			     <th>Куда</th>
			     <th>Дата</th>
			     <th>Комментарий</th>
			   </tr>     
			 </thead>';	 
		foreach ($subarr as $value) {
		    echo "<tr>";
		    echo "<td>".$value["id"]."</td>";
		    echo "<td>".$value["placename"]."</td>";
		    echo "<td>".$value["dt"]."</td>";
		    echo "<td>".$value["comment"]."</td>";
		    echo "</tr>";
		}; 
		echo "</table>\n";
		echo "</div>\n";
	    };
    echo "</td>";
    echo "<td>$cntend</td>";  
    echo "</tr>";  
  };
};
echo "<tr class='success'>";
    echo "<td></td>";
    echo "<td>ИТОГО:</td>";
    echo "<td>$itcntstart</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td>$itcntend</td>";
echo "</tr>";
?>
</table>

<?php
function GetBetweenArr($dtstart,$dtend,$orgid,$splaces,$speoples,$stmc,$type){
  global $sqlcn;
    $res=array();
    $where="1";
    if ($orgid!="null"){
	$where=$where." and register.orgid in ($orgid) ";
    };
    if ($splaces!="null"){
	$where=$where." and register.placesid in ($splaces) ";
    };
    if ($stmc!="null"){
       $where=$where." and equipment.nomeid in ($stmc) ";
    };    
    if ($speoples!="null"){
       $where=$where." and register.usersid in ($speoples) "; 
    };
  $cnt=0;
  if ($type==1){
    $sql="select equipment.id,nome.name as namenome,places.name as placename,move.comment,move.dt from register inner join equipment on equipment.id=register.eqid inner join move on move.id=register.moveid inner join places on places.id=move.placesidfrom inner join nome on  nome.id=equipment.nomeid  where equipment.active=1  and register.dt between '$dtstart 00:00:00' and '$dtend 23:59:59' and register.cnt=$type and $where ";
  } else {
    $sql="select equipment.id,nome.name as namenome,places.name as placename,move.comment,move.dt from register inner join equipment on equipment.id=register.eqid inner join move on move.id=register.moveid inner join places on places.id=move.placesidto inner join nome on  nome.id=equipment.nomeid  where equipment.active=1  and register.dt between '$dtstart 00:00:00' and '$dtend 23:59:59' and register.cnt=$type and $where ";      
  };
    //echo "$sql<br/>";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать остаток!!".mysqli_error($sqlcn->idsqlconnection));
    $cnt=0;
    while($row = mysqli_fetch_array($result)) {
	$cnt++;
	$res[$cnt]["id"]=$row["id"];
	$res[$cnt]["namenome"]=$row["namenome"];
	$res[$cnt]["placename"]=$row["placename"];
	$res[$cnt]["comment"]=$row["comment"];
	$res[$cnt]["dt"]=$row["dt"];
    };  
    return $res;
    
};

function GetCountBetween($dtstart,$dtend,$orgid,$splaces,$speoples,$stmc,$type){
  global $sqlcn;
    $where="1";
    if ($orgid!="null"){
	$where=$where." and register.orgid in ($orgid) ";
    };
    if ($stmc!="null"){
       $where=$where." and equipment.nomeid in ($stmc) ";
    };    
    
    if ($splaces!="null"){
	$where=$where." and register.placesid in ($splaces) ";
    };    
    if ($speoples!="null"){
       $where=$where." and register.usersid in ($speoples) "; 
    };
  $cnt=0;
  if ($type!=0){
    $sql="select count(cnt) as cnt from register inner join equipment on equipment.id=register.eqid where equipment.active=1  and equipment.nomeid in ($stmc) and dt between '$dtstart' and '$dtend' and register.cnt=$type and $where group by equipment.nomeid";
  } else {
    $sql="select count(cnt) as cnt from register inner join equipment on equipment.id=register.eqid where equipment.active=1  and equipment.nomeid in ($stmc) and dt between '$dtstart' and '$dtend' and register.moveid is null and $where group by equipment.nomeid";      
  };
    //echo "$sql<br/>";
  $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать остаток!!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
	$cnt=$row["cnt"];
    };  
    return $cnt;
    
};
function MoveCountBetween($dtstart,$dtend,$orgid,$splaces,$speoples,$stmc){
  global $sqlcn;
    $where="1";    
    if ($orgid!="null"){
	$where=$where." and register.orgid in ($orgid) ";
    };
    if ($splaces!="null"){
	$where=$where." and register.placesid in ($splaces) ";
    };
    if ($stmc!="null"){
       $where=$where." and equipment.nomeid in ($stmc) ";
    };    
    
    if ($speoples!="null"){
       $where=$where." and register.usersid in ($speoples) "; 
    };
  $cnt=0;
  $sql="select count(cnt) as cnt from register inner join equipment on equipment.id=register.eqid where equipment.active=1  and equipment.nomeid in ($stmc) and dt between '$dtstart' and '$dtend' and $where group by equipment.nomeid";
  //  echo "$sql<br/>";
  $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать остаток!!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
	$cnt=$row["cnt"];
    };  
    return $cnt;
    
};
function CountOnDate($dtstart,$orgid,$splaces,$speoples,$stmc){
  global $sqlcn;
    $where="1";
    if ($orgid!="null"){
	$where=$where." and register.orgid in ($orgid) ";
    };
    if ($splaces!="null"){
	$where=$where." and register.placesid in ($splaces) ";
    };
    if ($speoples!="null"){
       $where=$where." and register.usersid in ($speoples) "; 
    };

  $cnt=0;
  $sql="select sum(cnt) as cnt from register inner join equipment on equipment.id=register.eqid where equipment.active=1  and equipment.nomeid in ($stmc) and dt<='$dtstart' and $where group by equipment.nomeid";
  //  echo "$sql<br/>";
  $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать остаток!!".mysqli_error($sqlcn->idsqlconnection));
    while($row = mysqli_fetch_array($result)) {
	$cnt=$row["cnt"];
    };  
    return $cnt;
};
?>
