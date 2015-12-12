<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>
<div class="row-fluid">
    <div class="span12">        
        <div class="well form-inline">
            <button type="submit" class="btn" id="viewwork" name="viewwork">Построить отчет</button>
        </div>        
        <table id="tbl_rep"></table>
        <div id="pg_nav"></div>
        <div id="pg_add_edit"></div>        
    </div>
</div>
<form method="post" action="inc/csvExport.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
</form>
<script type="text/javascript" src="controller/client/js/operreports/sert.js"></script>