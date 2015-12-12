<?php
/* 
 * (с) 2014 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
?>
<!-- saved from url=(0014)about:internet -->
<!DOCTYPE html>
<html lang="ru-RU">
<head>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Учет ТМЦ в организации и другие плюшки">
    <meta name="author" content="(c) 2011-2014 by Gribov Pavel">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>    
    <title><?php echo "$cfg->sitename" ?></title>

    <meta name="generator" content="yarus" />
    <link href="favicon.ico" type="image/ico" rel="icon" />
    <link href="favicon.ico" type="image/ico" rel="shortcut icon" />    
    <link rel="stylesheet" type="text/css" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/bootstrap-responsive.min.css">         
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/prism.css">
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/chosen.css">
     
    <?php
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/ui.jqgrid.css'>";
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery-ui.min.css'>";
    ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->    
    <!-- <script src="js/jquery.js" type="text/javascript"></script>  -->
    <?php
    echo "<script type='text/javascript' src='js/jq2.js'></script>";
    echo "<script type='text/javascript' src='js/jquery-migrate-1.2.1.js'></script>";
    echo "<script type='text/javascript' src='controller/client/themes/$cfg->theme/js/jquery-ui.js'></script>";
?>    
    
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/dailog.css">
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/calendar.css">
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/dp.css">
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/alert.css">
    <link rel="stylesheet" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/main.css">  
    <?php
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/demo.css'>";
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery.mmenu.all.css'>";
    ?>


    <script type="text/javascript" src="js/bootstrap.min.js"></script>          

    <script src="js/Common.js" type="text/javascript"></script>     
    <script src="js/datepicker_lang_US.js" type="text/javascript"></script>     
    <script src="js/jquery.datepicker.js" type="text/javascript"></script>

    <script src="js/jquery.alert.js" type="text/javascript"></script>    
    <script src="js/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script>
    <script src="js/wdCalendar_lang_US.js" type="text/javascript"></script>  
    <script src="js/jquery.calendar.js" type="text/javascript"></script>     
    
    <?php
    echo "<script src='js/jquery.mmenu.min.js' type='text/javascript'></script>";
    echo "<script>defaultorgid=$cfg->defaultorgid;</script>";
    echo "<script>defaultuserid=$user->id;</script>";
    echo "<script>theme='$cfg->theme';</script>";
    ?>
</head>
<body>   
			<div class="header">
				<a href="#menu"></a>
				<?php echo "$cfg->sitename" ?>
			</div>      
