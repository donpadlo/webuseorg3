<?php
if (($user->mode==0) or ($user->mode==1)){
?>
<div class="well">
Группы устройств:</br>
<select name="devid" id="devid">
          echo "<option value=-1>Не выбрано</option>";
<?php
	$SQL = "SELECT id,dgname,dcomment FROM devgroups";
	$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список устройств!".mysqli_error($sqlcn->idsqlconnection));
        while($row = mysqli_fetch_array($result)) {
          $id=$row["id"];
          $dgname=$row["dgname"];
          echo "<option value=$id>$dgname</option>";
        };
?>
</select>
</br>
<span class="label label-info">Список устройств</span>
<div class="well" id="listcomm" name="listcomm"></div>
<span class="label label-info">Вывод терминала</span>
<div class="well" id="term" name="term"></div>
</div>
<script type="text/javascript" src="controller/client/js/devicescontrol/devicesexecute.js"></script>
<?php
}
 else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}