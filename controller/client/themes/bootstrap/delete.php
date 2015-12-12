<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if ($user->mode==1){
?>
<div class="well">
    <button name=bdel id=bdel>Начать удаление</button></p>
    <div id="infoblock"></div>
    <hr>
</div>
<script>
       $("#bdel").click(function(){
       $("#infoblock").load("controller/server/delete/delete.php");
       return false;
    });
</script>

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