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
include_once("../../../inc/login.php");		// соеденяемся с БД, получаем $mysql_base_id
	

if (isset($_GET["step"])) {$step=$_GET["step"];} else {$step="";};
if (isset($_GET["eqid"])) {$eqid=$_GET["eqid"];} else {$eqid="";};
if (isset($_POST["oper"])) {$oper= $_POST['oper'];} else {$oper="";};
if (isset($_POST["id"])) {$id = $_POST['id'];} else {$id="";};
$responce=new stdClass();
if ($id!=""){$eqid=$id;};

// Выполняем токма если юзер зашел!
if ($user->TestRoles("1,4,5,6")==true){
  if ($step=='add'){
	$dtpost=DateToMySQLDateTime2($_POST["dtpost"]." 00:00:00");
	if ($dtpost==""){$err[]="Не выбрана дата!";};   
	$dt=DateToMySQLDateTime2($_POST["dt"]." 00:00:00");
	if ($dt==""){$err[]="Не выбрана дата!";};   
	$kntid=$_POST["kntid"];
	if ($kntid==""){$err[]="Не выбран контрагент!";};   
        if (isset($_POST["cst"]))        {$cst=$_POST["cst"];}           else {$cst="";};
	if (isset($_POST["status"]))     {$status=$_POST["status"];}     else {$status="";};	
      	if (isset($_POST["comment"]))    {$comment=$_POST["comment"];}   else {$comment="";};	
        if (count($err)==0){
            $sql="INSERT INTO repair (id,dt,kntid,eqid,cost,comment,dtend,status)
            VALUES (NULL,'$dtpost','$kntid','$eqid','$cst','$comment','$dt','1')";                                      
            $result = $sqlcn->ExecuteSQL($sql);                
            if ($result==''){die('Не смог добавить ремонт!: ' . mysqli_error($sqlcn->idsqlconnection));}   
            if ($status!=0){
                $sql="UPDATE equipment SET repair='$status' WHERE id='$eqid'";            
                $result = $sqlcn->ExecuteSQL($sql);                
                if ($result==''){die('Не смог обновить запись о ремонте!: ' . mysqli_error($sqlcn->idsqlconnection));}               
            };
        };
      
  };  
    if ($step=='list'){          
            $page = $_GET['page']; // get the requested page
            if ($page==0){$page=1;};
            $limit = $_GET['rows']; // get how many rows we want to have into the grid
            $sidx = $_GET['sidx']; // get index row - i.e. user click to sort
            $sord = $_GET['sord']; // get the direction
            if (isset($_POST["oper"]))     {$oper= $_POST['oper'];} else {$oper="";};
            if (isset($_GET["id"]))     {$id = $_GET['id'];  } else {$id="";};
            
            if ($id!=""){$where=" WHERE reqid='$id' ";} else {$where="";}
            
            if(!$sidx) $sidx =1;
            $result = $sqlcn->ExecuteSQL("SELECT COUNT(*) AS count FROM repair");
            $row = mysqli_fetch_array($result);
            $count = $row['count'];

            if( $count >0 ) {$total_pages = ceil($count/$limit);} else {$total_pages = 0;};
            if ($page > $total_pages) $page=$total_pages;

            $start = $limit*$page - $limit;
            $SQL = "SELECT rp2.reqid as reqid,rp2.rstatus as rstatus,rp2.rpid as rpid,knt.id as kntid,knt.name as namekont,rp2.kntid,rp2.dt,rp2.cost,rp2.comment,rp2.dtend,rp2.nomeid,rp2.name as namenome FROM knt INNER JOIN(
                    SELECT rp.reqid as reqid,rp.rstatus as rstatus,rp.rpid as rpid,nome.name,rp.kntid,rp.dt,rp.cost,rp.comment,rp.dtend,rp.nomeid FROM nome INNER JOIN 
                    (SELECT repair.eqid as reqid,repair.status as rstatus,repair.id as rpid,repair.kntid,repair.dt,repair.cost,repair.comment,repair.dtend,equipment.nomeid FROM repair INNER JOIN  equipment ON repair.eqid= equipment.id) AS rp ON rp.nomeid=nome.id) AS rp2 ON rp2.kntid=knt.id ".$where." ORDER BY $sidx $sord LIMIT $start , $limit";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список контрагентов!".mysqli_error($sqlcn->idsqlconnection));

            $responce->page = $page;
            $responce->total = $total_pages;
            $responce->records = $count;
            $i=0;
            while($row = mysqli_fetch_array($result)) {
                $dtz=$row['dt'];
                //echo "$dtz";
                $responce->rows[$i]['id']=$row['rpid'];
                if ($row['rstatus']=='1'){$row['rstatus']="Ремонт";} else {$row['rstatus']="Сделано";}                
                $responce->rows[$i]['cell']=array($row['rpid'],$row['namekont'],$row['namenome'],MySQLDateToDate($row['dt']),MySQLDateToDate($row['dtend']),$row['cost'],$row['comment'],$row['rstatus']);
                $i++;
                }
            echo json_encode($responce);            
    };  
       if ($step=='edit'){
           //echo "!$oper!!";
         if ($oper=='edit'){
        	$dt=DateToMySQLDateTime2($_POST["dt"]." 00:00:00");
                $dtend=DateToMySQLDateTime2($_POST["dtend"]." 00:00:00");
                $cost=$_POST['cost'];
                $comment=$_POST['comment'];
                $rstatus=$_POST['rstatus'];
                    $SQL = "UPDATE repair SET dt='$dt',dtend='$dtend',cost='$cost',comment='$comment',status='$rstatus' WHERE id='$eqid'";
                    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не смог обновить статус ремонта!".mysqli_error($sqlcn->idsqlconnection));
                    ReUpdateRepairEq();
         };
         if ($oper=='del'){
            //echo "!$oper!$eqid!"; 
            $SQL="SELECT * FROM repair WHERE id='$eqid'";
            $result = $sqlcn->ExecuteSQL($SQL)  or die("Не получилось выбрать список ремонтов!".mysqli_error($sqlcn->idsqlconnection));
            while($row = mysqli_fetch_array($result)) {
              $status=$row['status'];
            };
            //echo "!$oper!$eqid!$status!"; 
            if ($status!='1'){
            $SQL = "delete FROM repair WHERE id='$eqid'";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не смог обновить статус ремонта!".mysqli_error($sqlcn->idsqlconnection));};
            ReUpdateRepairEq();
         };
         
            
        };
};

if ($step!="list")
{
 if (count($err)==0) {echo "ok";} else {
  echo '<script>$("#messenger").addClass("alert alert-error");</script>';
        for ($i = 0; $i <= count($err); $i++) {echo "$err[$i]<br>";};  };
};
?>
