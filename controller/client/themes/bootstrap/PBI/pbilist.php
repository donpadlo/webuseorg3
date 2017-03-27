<?php

//создаем таблицы для модуля
$sql="CREATE TABLE pbi ( `id` INT NOT NULL AUTO_INCREMENT , `groupname` VARCHAR(255) NOT NULL , `name` VARCHAR(255) NOT NULL , `comment` TEXT NOT NULL , `login` VARCHAR(50) NOT NULL , `pass` VARCHAR(50) NOT NULL , `ip` VARCHAR(30) NOT NULL , `forusers` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
$result = $sqlcn->ExecuteSQL($sql);                   
$sql="ALTER TABLE `pbi` CHANGE `login` `login` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
$result = $sqlcn->ExecuteSQL($sql);  
$sql="ALTER TABLE `pbi` CHANGE `groupname` `groupname` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
$result = $sqlcn->ExecuteSQL($sql);  
$sql="ALTER TABLE `pbi` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
$result = $sqlcn->ExecuteSQL($sql);  
$sql="ALTER TABLE `pbi` CHANGE `comment` `comment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;";
$result = $sqlcn->ExecuteSQL($sql);  

//
?>

<div class="container-fluid">
    <div class="row-fluid">
      <div class="col-xs-12 col-md-12 col-sm-12">    
	<table id="list2"></table>
	<div id="pager2"></div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="col-xs-12 col-md-12 col-sm-12">    
	<div id="pbiinfo"></div>
      </div>
    </div>
    
</div>    
<script type="text/javascript" src="controller/client/js/pbi/pbilist.js"></script>
