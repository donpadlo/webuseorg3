<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф


if ($user->mode==1){
    include_once("class/groups.class.php");
?>
<script type="text/javascript" src="controller/client/js/smscenter/functions.js"></script>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="col-xs-4 col-md-4 col-sm-4">    
            <ul  class="nav nav-pills">
                <li><a href="#" onclick="showUsers();">Пользователи</a></li>
                <li><a  href="#" onclick="showGroups();">Группы</a></li>  
            </ul>
            <div id="catmenu"></div>
        </div>
        <div class="col-xs-4 col-md-4 col-sm-4" id="content">&nbsp;</div>
        <div class="col-xs-4 col-md-4 col-sm-4" id="window"></div>
    </div>
</div>    
<script>showUsers();</script>
<?php
} else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}
