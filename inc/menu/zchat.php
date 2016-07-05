<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчки: Грибов Павел, (добавляйте себя если что-то делали))
// http://грибовы.рф
global $sqlcn;
$md=new Tmod; // обьявляем переменную для работы с классом модуля
$md->Register("chat", "Чат поддержки/Общий чат", "Грибов Павел"); 
if ($md->IsActive("chat")==1) {
 $sql="CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Уникальный идентификатор',
  `from_id` int(11) NOT NULL COMMENT 'От кого',
  `to_id` int(11) NOT NULL COMMENT 'Кому',
  `dt` datetime NOT NULL COMMENT 'Дата и время',
  `txt` text COLLATE utf8_bin NOT NULL COMMENT 'текст',
  `readly` tinyint(4) NOT NULL COMMENT 'прочитано? 1 -нет',
  `session` int(11) NOT NULL COMMENT 'Сессия',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;";   
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="CREATE TABLE IF NOT EXISTS `chat_users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB";
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="ALTER TABLE `chat_users` ADD PRIMARY KEY (`id`);";
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="ALTER TABLE `chat_users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="ALTER TABLE `chat_users` ADD `userid` INT NOT NULL AFTER `name`;";
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="ALTER TABLE `chat_users` ADD `lastmessage` DATETIME NOT NULL AFTER `userid`";
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="ALTER TABLE `chat_users` ADD `online` INT NOT NULL AFTER `lastmessage`";
 $result = $sqlcn->ExecuteSQL($sql);
 $sql="ALTER TABLE `chat_users` ADD `lastping` DATETIME NOT NULL AFTER `online`;";
 $result = $sqlcn->ExecuteSQL($sql);
 $this->Add("config","<i class='fa fa-paw fa-fw'> </i>Настройка Chat","Чат поддержки",3,"config/chat_config","chat_config");        
};
unset($md);