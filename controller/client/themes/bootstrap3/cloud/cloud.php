<?php

/* 
 * (с) 2014 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

// Роли:  
//            1="Полный доступ"
//            2="Просмотр финансовых отчетов"
//            3="Просмотр количественных отчетов"
//            4="Добавление"
//            5="Редактирование"
//            6="Удаление"

if ($user->TestRoles("1,3")==true){
echo "<link rel='stylesheet' type='text/css' href='controller/client/themes/$cfg->theme/css/upload.css'>";    
?>
<link href="js/skin/ui.dynatree.css" rel="stylesheet" type="text/css">
<script src="js/jquery.dynatree.js" type="text/javascript"></script>
<script src="js/FileAPI/FileAPI.min.js"></script>
<script src="js/FileAPI/FileAPI.exif.js"></script>
<script src="js/jquery.fileapi.js"></script>
<script src="js/jcrop/jquery.Jcrop.min.js"></script>
<script src="js/statics/jquery.modal.js"></script>

<div class="container-fluid">
<div class="row-fluid">       
        <div class="col-xs-4 col-md-4 col-sm-4"> 
            <div id="tree"></div>
            <div class="form-inline">
            <p>
            <input name="foldername" id="foldername" type="text" placeholder="имя папки">            
            <?php if ($user->TestRoles("1,4")==true){ ?><button name="newfolder" id="newfolder" class="btn btn-small btn-success" type="button">Новая папка</button><?php }; ?>
            <?php if ($user->TestRoles("1,6")==true){ ?><button name="delfolder" id="delfolder"class="btn btn-small btn-danger" type="button">Удалить</button><?php }; ?>            
            </p>
            <?php 
            if ($user->TestRoles("1,4")==true){
            ?>                
            <div align="center" id="simple-btn" class="btn btn-primary js-fileapi-wrapper" style="text-align: center;visibility:hidden">
                <div class="js-browse" align="center">
                    <span class="btn-txt">Загрузить файл</span>
                    <input type="file" name="filedata">
                </div>
                <div class="js-upload" style="display: none">
                <div class="progress progress-success"><div class="js-progress bar"></div></div>
                <span align="center" class="btn-txt">Загружаю (<span class="js-size"></span>)</span>
                </div>
            </div>             
            <?php }; ?>
            </div>
        </div>
        <div class="col-xs-8 col-md-8 col-sm-8"> 
            <table id="cloud_files"></table>
            <div id="cloud_files_pager"></div>                    
        </div>        
    
</div>
</div>    
<script type="text/javascript" src="controller/client/js/cloud.js"></script>
<?php
} else {
echo '<div class="alert alert-error">
  У вас нет доступа в данный раздел! Не назначены роли!
</div>';
    
};
?>