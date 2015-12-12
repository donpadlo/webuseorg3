<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

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
    <div id="add_edit"></div>
</div>
</div>
 </div>
<script type="text/javascript" src="controller/client/js/cables/cables.js"></script>
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