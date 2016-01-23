<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->mode==1){
?>
<div class="container-fluid">
<div class="row">            
<div class="col-xs-12 col-md-12 col-sm-12">
      <table id="list_chat_users"></table>
      <div id="pager_chat_users"></div>
      <p>Через ;,можно указать каким пользователям будет переадресован вопрос заданный на каком-то сайте. Для получения вопросов, нужно разместить виджет на этом сайте.</p>
</div>
</div>
</div>    
 <script type="text/javascript" src="controller/client/js/chat_users_config.js"></script>
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