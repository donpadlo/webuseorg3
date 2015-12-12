<?php

/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

include_once ("inc/lbfunc.php");                    // загружаем функции LB
if ($user->mode==1){
?>
<div class="container-fluid">
<div class="row-fluid">
<div class="col-xs-12 col-md-12 col-sm-12">    
<table id="list2"></table>
<div id="pager2"></div>
<table id="list3"></table>
<div id="pager3"></div>
<table id="list4"></table>
<div id="pager4"></div>

<script type="text/javascript" src="controller/client/js/astra/config.js"></script>
</div>
</div>
</div>
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