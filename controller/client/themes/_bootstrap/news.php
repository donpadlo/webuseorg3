<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if ($user->mode==1){
?>
<div class="container-fluid">
<div class="row-fluid">       
    <table id="list2"></table>
    <div id="pager2"></div>
    <div id="pg_add_edit"></div>    
</div>
</div>    
<script type="text/javascript" src="controller/client/js/news.js"></script>
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