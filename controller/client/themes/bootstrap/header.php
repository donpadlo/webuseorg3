<?php



// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
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
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/demo.css'>";
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery.mmenu.all.css'>";
    ?>
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->    
    <?php
    echo "<script type='text/javascript' src='js/jquery.min.js'></script>";
    echo "<script type='text/javascript' src='js/jquery-migrate-1.2.1.js'></script>";
    echo "<script type='text/javascript' src='controller/client/themes/$cfg->theme/js/jquery-ui.js'></script>";
    echo "<script type='text/javascript' src='js/i18n/grid.locale-ru.js'></script>";    
    echo "<script type='text/javascript' src='js/jquery.jqGrid.min.js'></script>";
    echo "<script type='text/javascript' src='js/jquery.form.js'></script>";    
  echo "<script src='js/chosen.jquery.js' type='text/javascript'></script>";
  echo "<script src='js/jquery.mmenu.min.all.js' type='text/javascript'></script>";
  echo "<script src='js/docsupport/prism.js' type='text/javascript' charset='utf-8'></script>";
    
    ?>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>    
    <script>
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }        

    if (jQuery.browser.msie==true) { if ((jQuery.browser.version == '6.0')||(jQuery.browser.version == '7.0')||(jQuery.browser.version == '8.0'))  {
        alert('Внимание! Ваш браузер устарел. Обновите IE  до версии 9.0 и выше. Корректная работа приложения не возможна');
    }}       
    if (jQuery.support.boxModel==false) {
        alert('Внимание! Ваш браузер устарел. Корректная работа приложения не возможна');
        }
    </script>

    <?php echo "<script>defaultorgid=$cfg->defaultorgid;</script>";?>
    <?php echo "<script>defaultuserid=$user->id;</script>";?>
    <?php echo "<script>theme='$cfg->theme';</script>";?>
</head>
<body>   
<?php if ($printable==false){?>    
<div class="header">
        <a href="#menu"></a>             
 </div>
<?php       
    if (count($cfg->quickmenu)!=0){
        echo '<div id="blob" data-placement="bottom" class="quickmenu" rel="popover">';  
        $mm="";
        for ($i=0;$i<count($cfg->quickmenu);$i++){            
            $mm=$mm.$cfg->quickmenu[$i]."</br>";
        };
        echo '<strong>'.$cfg->sitename.'</strong><span class="caret"></span></div>';
        echo '<script>$("#blob").popover({title:"Быстрые ссылки",delay: { "show": 500, "hide": 100 },html:true,content:"'.$mm.'"});</script>';
    };
};   
?>                                                                
                        
