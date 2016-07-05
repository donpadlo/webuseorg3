<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф
$id=_GET("id");
$sql="select * from astra_mon where id=$id";
//echo "$sql";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать ссылку!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {
    $url=$row["url"];
    $name=$row["name"];
    //echo "-$url-";
    $zx="/usr/local/bin/wget $url/control/ --post-data='{\"cmd\":\"load\"}' -O /tmp/astra.json";
    `$zx`;
    $zx="/usr/local/bin/wget $url/control/ --post-data='{\"cmd\":\"get-bitrate\"}' -O /tmp/astra_bit.json";
    `$zx`;
};
?>
<div class="container-fluid">
<h1><?php echo "$name" ?></h1>    
<div class="row-fluid">
<div class="col-xs-6 col-md-6 col-sm-6">    
  <table class="table table-hover table-condensed">
  <thead>	
	<tr>
	    <th>#</th>
	    <th>Название</th>
	    <th>Битрейд</th>
	</tr> 
  </thead>	
<?php    
$json = file_get_contents('/tmp/astra.json');
$json_bit = file_get_contents('/tmp/astra_bit.json');
  $json_bit=json_decode($json_bit);
if ($json==FALSE){
  echo "<a target=\"_blank\"href=\"$url\" class=\"btn btn-primary btn active\" role=\"button\">Панель управления</a>";      
} else {
    $obj = json_decode($json);
    $cnt=0;$cnt_kan=0;
    foreach ($obj as $key => $kk) {
     $cnt++;
     if (count($kk)>1){
	foreach ($kk as $key => $kan) {	
	    if (isset($kan->input[0])){
		//var_dump($kan);
		$name=$kan->name;
		if (strpos($name,"noview")===false){
		$id_kan=$kan->id;
	//	$input=$kan->input[0];
	//	$output=$kan->output[0];
		$cnt_kan++;
		//echo "<a href=\"\" class=\"btn btn-default btn active\" role=\"button\">$name</a>";
		    $bitrate=0;
		    $cl="";
		    if (isset($json_bit->data)){
			foreach ($json_bit->data as $value) {			
			    if ($value->channel_id==$id_kan){
				$bitrate=$value->bitrate;
				if ($bitrate<1000){$cl="class='danger'";};
			    };

			};		    
		    };  
	
?>
    <tr <?php echo "$cl";?>>	
	    <td><?php echo "$cnt_kan";?></td>
	    <td><?php echo "$name";?></td>
	    <td><div id="<?php echo "$id_kan";?>"><?php echo "$bitrate";?></div></td>    
    </tr>	    
<?php    
	    };
	   };
     };

	    };
    };
};
?>
</table>   
 <table class="table table-hover table-condensed">
  <thead>	
	<tr>
	    <th>#</th>
	    <th>Файл</th>
	</tr> 
  </thead>	
<?php
    $zz=$id."100000";
    $sql="select * from files_contract where idcontract=$zz";
    //echo "$sql";
    $result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список страниц!".mysqli_error($sqlcn->idsqlconnection));
    $cnt=0;
    while($row = mysqli_fetch_array($result)) {
	$cnt++;
	$filename=$row["filename"];
	$userfreandlyfilename=$row["userfreandlyfilename"];
	echo "<tr>";
	 echo "<td>$cnt</td>";
	 echo "<td><a target='_blank' href=files/$filename>$userfreandlyfilename</a></td>";
	echo "</tr>";
    };
?>
  </table>    
</div>
<?php
    echo "<div class=\"col-xs-6 col-md-6 col-sm-6\">";
if ($json==FALSE){    
    
} else { 
  echo "<div id=mm name=mm></div>";  
  if ($user->TestRoles("1")==true){
    echo "<a target=\"_blank\"href=\"$url/#/stream/\" class=\"btn btn-primary btn active\" role=\"button\">Панель управления</a></br>";
  };
  echo "<div onclick='Reload(\"$id\")' class=\"btn btn-danger btn active\" role=\"button\">Перезагрузить</div>";
};
    echo "</div>";
?>
</div>
</div>
<div align=center onclick='ListCmn()' class="btn btn-info btn active" role="button">Общий список</div>
<script>
function ListCmn(){    
                showcurrent($("#astrabase").val());
                openMonurl($("#astrabase").val());
};    
function Reload(id){
	if (confirm("Вы убеждены что хотите перезагрузить Астру?")) {
	      $("#mm").load(route+"controller/server/astra/restart.php&astra_id="+id);
	      $().toastmessage('showWarningToast', 'Астра перезагружена..!'); 
	};
};
</script>