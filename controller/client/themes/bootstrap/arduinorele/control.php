<?php

/* 
 * (с) 2011-2017 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
?>
<div class="container-fluid">
    <div class="row-fluid">
      <div class="col-xs-12 col-md-12 col-sm-12">  
<?php	  
$result = $sqlcn->ExecuteSQL("SELECT * FROM arduino_rele_config order by id  asc");
if ($result!='') {
    echo '<select name="ardulinp_rele_list" id="ardulinp_rele_list">';
    while ($myrow = mysqli_fetch_array($result)){
      $comment=$myrow["comment"];                
      $ulist=  explode(",", $myrow["roles"]);      
      if (in_array($user->id,$ulist)){
	$id=$myrow["id"];                
	echo "<option value=$id>$comment</option>";                      
      };
    };
    echo '</select>';
};              
?>
	<div id="ardu_view">
	</div>    
      </div>
    </div>
</div>    
	  
<script>
    function ShowArdulineRele(id){	
	$("#ardu_view").load(route+"controller/client/view/arduino_rele/show_arduino_rele.php&id="+id);
    };    
    // обрабатываем выбор ардулины	 
    $("#ardulinp_rele_list").change(function() {
	ShowArdulineRele($("#ardulinp_rele_list").val());	
    });
    ShowArdulineRele($("#ardulinp_rele_list").val());	
</script>	    