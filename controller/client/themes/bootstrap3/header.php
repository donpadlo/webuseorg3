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
    <meta name="author" content="(c) 2011-2016 by Gribov Pavel">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>    
    <title><?php echo "$cfg->sitename" ?></title>

    <meta name="generator" content="yarus" />
    <link href="favicon.ico" type="image/ico" rel="icon" />
    <link href="favicon.ico" type="image/ico" rel="shortcut icon" />    
    <link rel="stylesheet" type="text/css" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/bootstrap.min.css">
    <!--<link rel="stylesheet" type="text/css" href="controller/client/themes/<?php echo "$cfg->theme"; ?>/css/bootstrap-theme.min.css"> -->        
     
    <?php
    //echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/ui.jqgrid.css'>";
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery-ui.min.css'>";    
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/jquery.mmenu.all.css'>";
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/mmenu.css'>";
    echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/ui.jqgrid-bootstrap.css'>";
    echo "<link rel='stylesheet' href='controller/client/themes/$cfg->theme/css/chosen.css'>";
    echo "<script type='text/javascript' src='controller/client/themes/$cfg->theme/js/jquery-1.11.0.min.js'></script>"; 
    echo "<script type='text/javascript' src='controller/client/themes/$cfg->theme/js/jquery-ui.js'></script>";
    echo "<script type='text/javascript' src='controller/client/themes/$cfg->theme/js/i18n/grid.locale-ru.js'></script>";    
    echo "<script type='text/javascript' src='controller/client/themes/$cfg->theme/js/jquery.jqGrid.min.js'></script>";
    echo "<script src='js/chosen.jquery.js' type='text/javascript'></script>";
    echo "<script src='js/jquery.mmenu.min.all.js' type='text/javascript'></script>";
    
    ?>
    <script type="text/javascript" src="controller/client/themes/<?php echo "$cfg->theme";?>/js/bootstrap.min.js"></script>    
    <script type='text/javascript' src='js/jquery.form.js'>
    <?php 
    echo "<script>defaultorgid=$cfg->defaultorgid;</script>";
    if ($user->id!="") echo "<script>defaultuserid=$user->id;</script>";
    echo "<script>theme='$cfg->theme';</script>";
    ?>    
    <script>
                    $.jgrid.defaults.width = 780;
                    $.jgrid.defaults.responsive = true;		
                    $.jgrid.defaults.styleUI = 'Bootstrap';
                    $.jgrid.styleUI.Bootstrap.base.headerTable = "table table-bordered table-condensed";
                    $.jgrid.styleUI.Bootstrap.base.rowTable = "table table-bordered table-condensed";
                    $.jgrid.styleUI.Bootstrap.base.footerTable = "table table-bordered table-condensed";
                    $.jgrid.styleUI.Bootstrap.base.pagerTable = "table table-condensed";                    
                    var config = {
                      '.chosen-select'           : {},
                      '.chosen-select-deselect'  : {allow_single_deselect:true},
                      '.chosen-select-no-single' : {disable_search_threshold:10},
                      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
                      '.chosen-select-width'     : {width:"95%"}
                    }                            
    </script>    
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