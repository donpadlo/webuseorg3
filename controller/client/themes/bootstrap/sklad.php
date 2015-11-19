<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>
<div class="row-fluid">
    <div class="span12">        
        <div class="well form-inline">
            <button type="submit" class="btn" id="viewwork" name="viewwork">Обновить</button>
        </div>
        <div class="well" id="tablesklad">
        </div>    
    </div>
</div>
<script>
    $("#viewwork").click(function(){
       $("#tablesklad").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
       $("#tablesklad").load("controller/client/view/opreports/tmc.php");
       return false;
    });
$("#tablesklad").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
$("#tablesklad").load("controller/client/view/opreports/tmc.php");
</script>    
<?php

?>