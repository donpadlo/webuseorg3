<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
?>
<div class="row-fluid">
	<div class="span12">
		<div class="well form-inline">
			<select name="tmc_groups" id="tmc_groups">
			</select>
			<button type="submit" class="btn" id="viewwork" name="viewwork">Построить
				отчет</button>
		</div>
		<div class="well" id="tablesklad"></div>
	</div>
</div>
<script>
    $("#viewwork").click(function(){
       if ($("#tmc_groups").val()!="")
       {
        $("#tablesklad").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
        $("#tablesklad").load("controller/client/view/opreports/kmp.php?nome_group="+$("#tmc_groups").val());
    } else alert("Не выбрана категория ТМЦ!");
       return false;
    });
$("#tmc_groups").load("controller/client/view/opreports/get_tmc_group.php");    
</script>
<?php

?>