<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

if (($user->mode==1)){
$zb=new Tsql();    
$par=new Tcconfig();
echo '<div class="container-fluid">';
echo "<h3>Выберите свои подписки</h3>";
echo '<div class="row-fluid">';
echo '<div class="col-xs-6 col-md-6 col-sm-6">';
//проходим все сервера Zabbix
$sql="select * from zabbix_mod_cfg";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список серверов zabbix!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {    
 $id=$row["id"];   
 $sname=$row["sname"];
 $host=$row["host"];
 $username=$row["username"];
 $pass=$row["pass"];
 $basename=$row["basename"];   
 $zb->connect($host,$username,$pass,$basename);
   //получаес группы из заббикса
   echo "<strong>$sname</strong></br>";
   $sql="select * from groups";
   $result2 = $zb->ExecuteSQL($sql) or die("Не могу выбрать список групп zabbix!".mysqli_error($zb->idsqlconnection));
   while($row2 = mysqli_fetch_array($result2)) {
     $groupid=$row2["groupid"];
     $gname=$row2["name"];
     $nm="$id"."_"."$groupid";
     $rp=$par->GetByParam($user->id."_$nm");
     if ($rp==$nm){$ch="checked";} else {$ch="";};
     echo "</br><input type='checkbox' name=$nm value=$nm $ch> $gname</br>";
   };   
};
echo "</div>";
echo '<div class="col-xs-6 col-md-6 col-sm-6">';
echo '<label>Время срабатывания триггера</label>';
$zt=$par->GetByParam($user->id."_zabbix_time");
echo "<input type='text'placeholder='в минутах..' size='10' value='$zt'>";
//echo " <button type='submit' class='btn'>Сохранить</button>";
echo "</div>";
echo '</div>';
echo '</div>';
} else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}
?>
<script>
  $("input[type='checkbox']").change(function() {
        if ($(this).is(':checked')){rz=this.value} else {rz="";};
        $.get('controller/server/zabbix/setstatuschesk.php',{mode:"check",tag:this.value,value:rz}, function( data ) {
            if (data!=""){
                alert(data);
            };
        });      
    });    
  $("input[type='text']").change(function() {
        $.get('controller/server/zabbix/setstatuschesk.php',{mode:"txt",value:this.value}, function( data ) {
            if (data!=""){
                alert(data);
            };
        });      
    });    
    
</script>    