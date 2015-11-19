<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->TestRoles("1,4,5,6")==true){
?>
<div class="well">  
<table id="list2"></table>
<div id="pager2"></div>
<div id="add_edit"></div>
</div>
<script type="text/javascript" src="controller/client/js/libre_nome.js"></script>
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