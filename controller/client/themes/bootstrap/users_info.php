<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>
<div class="row-fluid">
    <div class="span12">        
        <div class="well form-inline">
            <input name="dt" id="dt" size=16 value="" readonly>
            <button type="submit" class="btn" id="viewwork" name="viewwork">Показать</button>
        </div>
        <div class="well" id="tableworkusers">
        </div>    
    </div>
</div>
<script>
    $("#dt").datepicker();
    $("#dt").datepicker( "option", "dateFormat", "dd.mm.yy");
    $("#dt").datepicker( "setDate" , "0");
    $("#viewwork").click(function(){
       $("#tableworkusers").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
       $("#tableworkusers").load("controller/client/view/works/worktable.php?dttt="+$("#dt").val());
       return false;
    });
$("#tableworkusers").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
$("#tableworkusers").load("controller/client/view/works/worktable.php?dttt="+$("#dt").val());
</script>    
<?php

?>