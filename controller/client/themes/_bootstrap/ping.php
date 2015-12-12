<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if (($user->mode==1) or ($user->mode==0)){
?>
<div class="container-fluid">
<div class="row-fluid">   
    <input id="test_ping" class="btn btn-primary" name="test_ping" value="Проверить">
    <div id="ping_add">
</div>
</div>    
    
</div>
<script type="text/javascript" src="controller/client/js/ping.js"></script>   
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