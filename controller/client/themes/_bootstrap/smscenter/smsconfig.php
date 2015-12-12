<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф


if ($user->mode==1){
?>

<div class="row-fluid">
<div class="well">  
<table id="list2"></table>
<div id="pager2"></div>
<script type="text/javascript" src="controller/client/js/smscenter/smscenter.js"></script>
<?php
    $sms=new SmsAgent;
    $sms->Login();
    $bal=$sms->getBalance();
    $agnt=$sms->agentname;
?>
    <h4>Текущий агент: <?php echo "$agnt";?> <h4>            
    <h4>Баланс по агенту: <?php echo "$bal";?> <h4>
</div>  
</div>
<?php
} else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}
