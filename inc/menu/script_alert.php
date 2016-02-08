<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("scriptalert", "Мониторинг выполнения скриптов", "Грибов Павел"); 
if ($md->IsActive("scriptalert")==1) {
    $sql="CREATE TABLE IF NOT EXISTS `script_run_monitoring` (
  `id` int(11) NOT NULL,
  `script_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `group_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `alert_max_count` int(11) NOT NULL,
  `alert_max_time` int(11) NOT NULL,
  `lastupdatedt` datetime NOT NULL,
  `current_alert_count` int(11) NOT NULL,
  `sms_txt` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `active` tinyint(4) NOT NULL,
  `sms_group_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8_bin;";
 $result = $sqlcn->ExecuteSQL($sql);    
 $this->Add("config", "<i class='fa fa-bell-o fa-fw'> </i>Мониторинг скриптов", "Мониторинг выполнения скриптов", 0, "config/scriptalert","scriptalert");                    
};
unset($md);