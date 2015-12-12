<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->mode==1){
?>
<div class="well">
<table id="list2"></table>
<div id="pager2"></div>

<table id="list10_d"></table>
<div id="pager10_d"></div>
<div id="add_edit"></div>
<script type="text/javascript" src="controller/client/js/libre_place.js"></script>

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