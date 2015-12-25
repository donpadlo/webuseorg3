<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../../class/employees.php");		// загружаем классы работы с профилем пользователя
include_once("../../../../class/bp.php");		// загружаем классы работы c "Бизнес процессами"


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся

 $step=GetDef('mode');
 $randomid=GetRandomId(60);
 $mybpid=GetDef('mybpid');
 $result = $sqlcn->ExecuteSQL("SELECT * FROM bp_xml_userlist WHERE id='$mybpid'");
 while ($myrow = mysqli_fetch_array($result)){
  $bpid=$myrow['bpid'];   
  $accept=$myrow['accept'];   
  $cancel=$myrow['cancel'];   
  $thinking=$myrow['thinking'];   
  $yes=$myrow['yes'];   
  $no=$myrow['no'];   
  $one=$myrow['one'];   
  $two=$myrow['two'];   
  $three=$myrow['three'];   
  $four=$myrow['four'];   
  
 };
 if ($bpid!=""){
     $bp1=new Tbp;
     $bp1->GetById($bpid);
     $title=$bp1->title;
     $bodytxt=$bp1->bodytxt;
     $status=$bp1->status; 
     $bpshema=$bp1->xml; 
     $tnode=$bp1->GetTitleNode($bp1->node);
 };
 
?> 
   <div class="row-fluid">
        <div class="span6">            
            <legend>Описание БП</legend>
            <?php echo "<h3>$title</h3>";?>
            <p class="lead">    
            <?php echo "$bodytxt";?>
            </p> 
        </div>
        <div class="span6">                  
            <form  id="myForm" class="well" ENCTYPE="multipart/form-data" action="index.php?content_page=mybp&bp_xml_id=<?php echo "$mybpid"; ?>" method="post" name="form1" target="_self">
            <legend>Ваша текущая задача:</legend>
            <p class="lead">    
            <div class="alert alert-success">
                <?php echo "<pre class='alert alert-success'>$tnode</pre>";?>
            </div>    
            </p>    
            <legend>Ваш комментарий к действию:</legend>
            <textarea class="span12" name=comment></textarea>
            
            <div align=center>
               <?php 
                if ($accept!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom1" name="sub" value="Согласовать"> ';};
                if ($cancel!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom2" name="sub" value="Отвергнуть"> ';};
                if ($thinking!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom3"  name="sub" value="Доработать"> ';};                
                if ($yes!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom4" name="sub" value="Да"> ';};                
                if ($no!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom5" name="sub" value="Нет"> ';};                
                if ($one!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom6" name="sub" value="1)"> ';};                
                if ($two!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom7" name="sub" value="2)"> ';};                
                if ($three!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom8" name="sub" value="3)"> ';};                
                if ($four!=-2) {echo '<input type="submit" class="btn btn-primary" id="nom9" name="sub" value="4)"> ';};                                
               ?>
            </div>      
            </form>
        </div>
    </div>  