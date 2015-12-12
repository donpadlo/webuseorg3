<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->TestRoles("1,4,5,6")==true){
?>
<div class="container-fluid">
<div class="row">            
<div class="col-xs-12 col-md-12 col-sm-12">
<table id="list2"></table>
<div id="pager2"></div>
<table id="list10_d"></table>
<div id="pager10_d"></div>
 </div>
 </div>
 </div>    
 <script type="text/javascript" src="controller/client/js/libre_group.js"></script>
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