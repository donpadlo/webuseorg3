<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if (($user->mode==1) or ($user->mode==0)){
?>
<div class="well">   
    <table id="bp_xml"></table>
    <div id="bp_xml_footer"></div>
    <div id="bp_info" style="visibility:hidden">
        <ul class="nav nav-tabs" id="myTab">
         <li><a href="#tab1" data-toggle="tab">Информация</a></li>
         <li><a href="#tab2" data-toggle="tab">Прохождение</a></li>
         <li><a href="#tab3" data-toggle="tab">Схема БП</a></li>
        </ul>
  <div id="bp_info_view"></div>          
  <div id="bp_xml_info"></div>          
  </div>        

</div>
    
</div>
<link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/plumb.css">
<script type='text/javascript' src='js/jquery.jsPlumb-1.5.5-min.js'></script>

<script type="text/javascript" src="controller/client/js/bp_xml.js"></script>
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