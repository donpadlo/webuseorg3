<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>
<div class="container-fluid">
<div class="row-fluid" style="padding-right: 0px;padding-left: 0px;">
  <div class="col-xs-12 col-md-6 col-sm-6" style="padding-right: 0px;padding-left: 0px;">
      <label>Организация:</label></br>
      <?php
	//делаем выборку организаций
	    $SQL = "SELECT * FROM org WHERE active=1 ORDER BY binary(name)";
	    $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список организаций!".mysqli_error($sqlcn->idsqlconnection));
	    $sts="<select class='chosen-select' multiple name=sogrsname id=sorgsname>";	    
	    while($row = mysqli_fetch_array($result)) {
		 $vln=$row['name'];
		 $vlid=$row['id'];
		 $sts=$sts."<option value=$vlid>$vln</option>";
		};
	    $sts=$sts."</select>";
	    echo $sts;      
      ?>
      <label>Помещение:</label></br>
      <div id="id_for_place"></div>
      <label>Человек:</label></br>
      <div id="id_for_user"></div>
  </div>    
  <div  class="col-xs-12 col-md-6 col-sm-6" >
            <label>Период отчета:</label></br>
            <input name="dtstart" id="dtstart" size=16 value="" readonly>
            <input name="dtend" id="dtend" size=16 value="" readonly>            
	    </br>
	    <label>ТМЦ:</label></br>
	    <div id="id_for_tmc"></div>
  </div>    
    <button type="submit" class="form-control" id="viewwork" name="viewwork">Сформировать отчет</button>                        
    <button type="submit" class="form-control" id="prnt" name="prnt">Распечатать отчет</button>                            
</div>        
</div>
<div class="container-fluid">
    <div class="row-fluid" style="padding-right: 0px;padding-left: 0px;" id="if_for_report">
    </div>        
</div>
<script type="text/javascript" src="controller/client/js/report_move_tmc.js"></script>