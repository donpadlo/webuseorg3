<?php

/* 
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$id=  _GET("id");
if ($id>0){
    $sql="select * from arduino_rele_config where id=$id";
    $result = $sqlcn->ExecuteSQL($sql);
    if ($result!='') {	
	while ($myrow = mysqli_fetch_array($result)){
	    $ip=$myrow["ip"];	    
	    $foot=explode(",",$myrow["foot"]);	    
	    $cont = file_get_contents("http://$ip/?command=1");	    
	    $cont=str_replace("<!DOCTYPE HTML>", "", $cont);	    	    	    
	    $cont=json_decode($cont);
	    //var_dump($cont);
	    echo "<h1>$ip</h1>";
	    $cnt=0;
	    echo '<div class="btn-group">';
	    foreach ($cont->pins as $value) {
		$comment="Пусто";
		if (isset($foot[$cnt])){
		    $comment=$foot[$cnt];		    
		};
		$pin=$value;		
		$status=$cont->pinsstatus[$cnt];
		$tmpc=$cnt+1;
		if ($status==0){
		  echo "<button onclick='ToggleArduino(\"$ip\",$cnt,1)' class='btn btn-success'>№ $tmpc<br/>$comment</button>";
		} else {
		  echo "<button onclick='ToggleArduino(\"$ip\",$cnt,0)' class='btn btn-danger'>№ $tmpc<br/>$comment</button>";
		};
		$cnt++;
	    };
	    echo '</div>';
	    ?>
<?php
	};
    };    
};
?>
<br/>
<br/>
<script>
  function ToggleArduino(ip,pin,status){
       if (confirm('Вы уверенны, что хотите сделать то что хотите?')){
	    $.get(route+'controller/server/arduino_rele/setstatus.php&ip='+ip+"&pin="+pin+"&status="+status, function( data ) {
		 $().toastmessage('showWarningToast', data);	
		 ShowArdulineRele($("#ardulinp_rele_list").val());  
	     });          

        };
  };
</script>    