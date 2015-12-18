<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
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
<div class="content">
<div class="row-fluid">
    <div class="span4">
        <span class="label label-info">Пользователь</span>
        <div class="well">
            <?php include_once("login.php");  // форма входа или профиль?>
        </div>    
        <span class="label label-info">Личное меню</span>
        <div class="well form-inline">
            <?php include_once("memenu.php");  // личное меню?>            
        </div>   
    </div>
    <div class="span4">
        <?php
        // Если новости "активны", то тогда показываем этот блок
        if ($mhome->IsActive("news")==1) {
        ?>
                    <span class="label label-info">Новости,обьявления</span>
                    <div class="well" id=newslist></div>    
                    <ul class="pager">
                        <li class="previous"><a href="#" id=newsprev name=newsprev>&larr; Назад</a></li>
                        <li class="next"><a href="#" id=newsnext name=newsnext>Вперед &rarr;</a></li>
                    </ul>      
                    <script type="text/javascript" src="controller/client/js/news_main.js"></script>                    
        <?php
        // Если задачи "активны", то тогда показываем этот блок
        if ($mhome->IsActive("tasks")==1) {
        ?>
        <span class="label label-info">Постановка задачи</span>
        <div class="well form-inline">
            <?php include_once("tasks.php");  // задачи ?>            
        </div>           
        <?php };?>
                    
        <?php
        };        
        if ($mhome->IsActive("usersfaze")==1) {
        ?>
                    <span class="label label-info">Состояние сотрудников</span>
                    <div class="well" id=usersfazelist></div>   
                    <script type="text/javascript" src="controller/client/js/usersfazelist.js"></script>                    
        <?php
        };                
        if ($mhome->IsActive("whoonline")==1) {
                           echo '<span class="label label-info">Кто онлайн</span>';
                            echo '<div class="well" id=usersfazelist>';
                            $crd=date("Y-m-d H:i:s");
                            $SQL = "SELECT unix_timestamp('$crd')-unix_timestamp(lastdt) as res,users_profile.fio as fio,users_profile.jpegphoto FROM users inner join users_profile on users_profile.usersid=users.id";
                            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список заходов пользователей!".mysqli_error($sqlcn->idsqlconnection));
                                    echo "<ul class=thumbnails>";                            
                            while($row = mysqli_fetch_array($result)) {                                
                                $res=$row["res"];                                
                                $fio=$row["fio"];                                
                                $jpegphoto=$row["jpegphoto"];                                
                                if ($res<10000){                                    

                                    echo "<li class=span2>";
                                    echo "<div class=thumbnail>";
                                    echo "<img src=photos/$jpegphoto>";
                                    echo "<p align=center>$fio</p>";
                                    echo "</div>";
                                    echo "</li>";

                                };                                
                            };
                            echo "</ul>";                                
                            echo "</div> ";
            
        };                
                    
        ?>                                        
    </div>
  <div class="span4">
        <?php
        // Если закрепленные новости "активны", то тогда показываем этот блок
        if ($mhome->IsActive("stiknews")==1) {
        ?>
      
      <span class="label label-info"><?php $stiker=GetStiker();echo $stiker["title"];?></span>
      <div class="well"><?php $stiker=GetStiker();echo $stiker["body"];?></div>
        <?php
        };
        if ($mhome->IsActive("lastmoved")==1) {
        ?>
                    <span class="label label-info">Последние перемещения ТМЦ</span>                    
                      <table id="tbl_move"></table><div id="mv_nav"></div>
                      <script type="text/javascript" src="controller/client/js/lastmoved.js"></script>                    
                      </br>
        <?php
        };
        ?>
<?php
if ($mhome->IsActive('commits-widget') == 1) {
    if ($user->mode==1){
?>
		<span class="label label-info">Разработка</span></br>
		<iframe src="http://tylerlh.github.com/github-latest-commits-widget/?username=donpadlo&repo=webuseorg3&limit=5" allowtransparency="true" frameborder="0" scrolling="no" width="400px" height="250px"></iframe>
<?php
};};
?>
  </div>
</div>
</div>   
<?php
unset($mhome);
?>