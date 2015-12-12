<?php

/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

include_once ("inc/lbfunc.php");                    // загружаем функции LB
if ($user->TestRoles("1,5")==true){
?>
<div class="container-fluid">
<div class="row-fluid">
  <div class="col-xs-4 col-md-4 col-sm-4">    
        <table id="list2"></table>
        <div id="pager2"></div>
        <div id="console"></div>
        <div id="pl"></div>
     </div>
     <div class="col-xs-4 col-md-4 col-sm-4">    
        <table id="frames"></table>
        <div id="pager_frames"></div>        
     </div>
     <div class="col-xs-4 col-md-4 col-sm-4">    
        <div id="frames_info"></div>
        Синтаксис:</br>
&lt;h1&gt;&lt;h2&gt;&lt;h3&gt; - размер текста<br/>&lt;br&gt;-перенос строки            <br/>&lt;c&gt;-центрирование строки<br/>&lt;red&gt;&lt;green&gt;&lt;white&gt;&lt;black&gt;&lt;yellow&gt;&lt;blue&gt; - цвет строки
     </div>
    
    
<script type="text/javascript" src="controller/client/js/astra/info.js"></script>
    
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