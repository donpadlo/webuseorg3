<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if (($user->mode==1) or ($user->mode==0)){
?>
<div class="well">   
    <table id="workmen"></table>
    <div id="workmen_footer"></div>
    <div id="pg_add_edit"></div>
    <div class="row-fluid">
                <div class="span2">
                    <div id=photoid></div>
                </div>
                <div class="span10">
                    <table id="tbl_rep"></table>
                    <div id="rp_nav"></div>
                </div>
            </div>        
</div>
    
</div>
<form method="post" action="inc/csvExport.php">
    <input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
</form>
<script type="text/javascript" src="controller/client/js/workmen.js"></script>
<?php
}
 else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}

?>