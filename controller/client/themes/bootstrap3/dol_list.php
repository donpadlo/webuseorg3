<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->mode==1){
?>
<div class="container-fluid">
<div class="row">            
<div class="col-xs-12 col-md-12 col-sm-12">

      <table id="list_post_users"></table>
      <div id="pager_post_users"></div>
</div>
</div>
</div>    
 <script type="text/javascript" src="controller/client/js/post_users.js"></script>
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