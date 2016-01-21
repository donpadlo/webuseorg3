<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("inc/lbfunc.php");                    // загружаем функции LB

/*
	Роли:
		1 = 'Полный доступ'
		2 = 'Просмотр финансовых отчетов'
		3 = 'Просмотр'
		4 = 'Добавление'
		5 = 'Редактирование'
		6 = 'Удаление'
		7 = 'Отправка СМС'
		8 = 'Манипуляции с деньгами'
		9 = 'Редактирование карт'
*/

if ($user->TestRoles("1,7")==true){
    
?>
<div class="row-fluid">

        <?php 
            $period=false;
            $seconddate=false;
            $agent=false;
            $btrep=false;
            $period=false;
            $fill=false;
            include("controller/client/themes/bootstrap/lanbilling/reports/head.php");                       
        ?>

        <div class="well" id="report">
            <table id="list2"></table>
            <div id="pager2"></div>                    
            
        <div id="dialog-load" title="Загрузка списка телефонов и текста СМС">
          <p>                  
          <label>Вставьте список:</label>
            <textarea rows="8" class="span8" name="smstext" id="smstext"></textarea>
            <div id="message_send"></div>
        </div>
        <div id="dialog-confirm" title="Разослать СМС по списку?">
          <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>После нажатия кнопки "ДА" произойдет рассылка СМС по всем телефонам находящимся в таблице.</br><strong>Вы уверены?</strong></p>
        </div>            
            
        </div>            
</div>        

<script type="text/javascript" src="controller/client/js/smscenter/smsbylist.js"></script>
<?php
} else {
echo '<div class="alert alert-error">
  У вас нет доступа в данный раздел! Не назначены роли!
</div>';        
};
?>