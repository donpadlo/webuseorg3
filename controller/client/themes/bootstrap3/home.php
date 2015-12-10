<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

  $morgs=GetArrayOrgs();    // список активный организаций
  $mhome=new Tmod;            // обьявляем переменную для работы с классом модуля
  $mhome->Register("news", "Модуль новостей", "Грибов Павел"); 
  $mhome->Register("stiknews", "Закрепленные новости", "Грибов Павел"); 
  $mhome->Register("lastmoved", "Последние перемещения ТМЦ", "Грибов Павел"); 
  $mhome->Register("usersfaze", "Где сотрудник?", "Грибов Павел"); 
  $mhome->Register("whoonline", "Кто на сайте?", "Грибов Павел"); 
  $mhome->Register('commits-widget', 'Виджет разработки на github.com на главной странице', 'Солодягин Сергей');
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-xs-12 col-md-4 col-sm-4">
        <div class="panel panel-primary">
            <div class="panel-heading">Пользователь</div>
            <div class="panel-body">
             <?php include_once("login.php");  // форма входа или профиль?>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">Личное меню</div>
            <div class="panel-body">
             <?php include_once("memenu.php");  // личное меню?>            
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4 col-sm-4">
        <?php
        // Если новости "активны", то тогда показываем этот блок
        if ($mhome->IsActive("news")==1) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Новости,обьявления</div>
            <div class="panel-body">
                    <div class="well" id=newslist></div>    
                    <ul class="pager">
                        <li class="previous"><a href="#" id=newsprev name=newsprev>&larr; Назад</a></li>
                        <li class="next"><a href="#" id=newsnext name=newsnext>Вперед &rarr;</a></li>
                    </ul>      
                    <script type="text/javascript" src="controller/client/js/news_main.js"></script>                    
            </div>
        </div>
        <?php
        // Если задачи "активны", то тогда показываем этот блок
        if ($mhome->IsActive("tasks")==1) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Постановка задачи</div>
            <div class="panel-body">
                <?php include_once("tasks.php");  // задачи ?>            
            </div>
        </div>
        <?php };?>
                    
        <?php
        };        
        if ($mhome->IsActive("usersfaze")==1) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Состояние сотрудников</div>
            <div class="panel-body">
                    <div class="well" id=usersfazelist></div>   
                    <script type="text/javascript" src="controller/client/js/usersfazelist.js"></script>                    
            </div>
        </div>
        <?php
        };                
        if ($mhome->IsActive("whoonline")==1) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Кто онлайн</div>
            <div class="panel-body">
                <?php include_once("whoonline.php");  // задачи ?>            
            </div>
        </div>
        <?php 
         };
        ?>
    </div>
  <div class="col-xs-12 col-md-4 col-sm-4">
        <?php
        // Если закрепленные новости "активны", то тогда показываем этот блок
        if ($mhome->IsActive("stiknews")==1) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><?php $stiker=GetStiker();echo $stiker["title"];?></div>
            <div class="panel-body">
                <?php $stiker=GetStiker();echo $stiker["body"];?>
            </div>
        </div>      
        <?php
        };
        if ($mhome->IsActive("lastmoved")==1) {
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">Последние перемещения ТМЦ</div>
            <div class="panel-body">
                      <table id="tbl_move"></table><div id="mv_nav"></div>
                      <script type="text/javascript" src="controller/client/js/lastmoved.js"></script>                    
            </div>
        </div>
            <?php
        };
        if ($mhome->IsActive('commits-widget') == 1) {
            if ($user->mode==1){
            ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">Разработка</div>
                    <div class="panel-body">
                    <iframe src="http://tylerlh.github.com/github-latest-commits-widget/?username=donpadlo&repo=webuseorg3&limit=5" allowtransparency="true" frameborder="0" scrolling="no" width="100%" height="250px"></iframe>            
                    </div>
                </div>
            <?php
            };        
        };
        ?>
  </div>
</div>
</div>   
<?php
unset($mhome);  
?>