<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

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
	$md=new Tmod; // обьявляем переменную для работы с классом модуля
	if ($md->IsActive("lanbilling")==1) {	
            $period=false;
            $seconddate=false;
            $agent=false;
            $btrep=false;
            $period=false;
            $fill=false;
            include("controller/client/themes/bootstrap/lanbilling/reports/head.php");                       
	};  
        ?>
<div class="container-fluid">
<div class="row-fluid">
  <div class="col-xs-12 col-md-12 col-sm-12">    
	    <div id="message_send"></div>
            <table id="list2"></table>
            <div id="pager2"></div>                                
	<div id="dialog-load" title="Загрузка списка телефонов и текста СМС">          
                <label for="smstext">Вставьте список:</label></br>
                <textarea rows="8" class="form-control" name="smstext" id="smstext"></textarea>                
		Скопируйте сюда список в формате: номер телефона;текст СМС
        </div>
        <div id="dialog-confirm" title="Разослать СМС по списку?">
          <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
          После нажатия кнопки "ДА" произойдет рассылка СМС по всем телефонам находящимся в таблице.
          </br><strong>Вы уверены?</strong>
        </div>                        
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