<?php
/*
 * Данный код создан и распространяется по лицензии GPL v3
 * Разработчики:
 *   Грибов Павел,
 *   (добавляйте себя если что-то делали)
 * http://грибовы.рф
 */

// Запрещаем прямой вызов скрипта.
defined('WUO_ROOT') or die('Доступ запрещён');

if ($user->mode != 1) {   
	die('<div class="alert alert-danger">У вас нет доступа в данный раздел!</div>');
};

//создаем таблицу
$sql="CREATE TABLE `schedule` ( `id` INT NOT NULL AUTO_INCREMENT , `dtstart` DATETIME NOT NULL , `dtend` DATETIME NOT NULL , `title` VARCHAR(255) NOT NULL , `comment` TEXT NOT NULL , `sms` TINYINT NOT NULL , `mail` TINYINT NOT NULL , `view` TINYINT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$result = $sqlcn->ExecuteSQL($sql);

echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery.datetimepicker.css'/>";
?>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<div class="container-fluid">
	<div class="row-fluid">
	    <div class="col-xs-12 col-md-12 col-sm-12">    
			<div id="curinfo"></div>
			<table id="list2"></table>
			<div id="pager2"></div>
	    </div>
	</div>
</div>    
<div id="schedule-dialog" title="Добавить/Изменить расписание">
    <div class="form-group">     
		    <label>Заголовок запроса</label>                
		    <input class="form-control" name="schedule-title" id="schedule-title">                        	
		    <label>Работы ведутся</label><br/>			
		    c: <input name="schedule_dtstart" id="schedule_dtstart" size=16 value="" > по: <input name="schedule_dtend" id="schedule_dtend" size=16 value=""><br/>
		    <div class="checkbox">
			<label>
			      <input type="checkbox" id="schedule-sms"> Запретить отправлять СМС и создавать уведомления
			</label>
			<label>
			      <input type="checkbox" id="schedule-mail"> Запретить отправлять/принимать почту
			</label>
			<label>
			      <input type="checkbox" id="schedule-message"> Показывать пользователям сообщение
			</label>
			
		      </div>		    
		    <label>Комментарий к работам:</label>		    
		    <textarea class="form-control" rows="3"  name="schedule-comment" id="schedule-comment"></textarea>                            		    
    </div>    
</div>
<script type="text/javascript" src="controller/client/js/schedule.js"></script>