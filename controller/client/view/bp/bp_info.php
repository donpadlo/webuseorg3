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

$bpid=GetDef("bpid");

$bp=new Tbp;
$bp->GetById($bpid);
$u=new Tusers;
$u->GetById($bp->userid);
?>
    <div class="row-fluid">
        <div class="col-xs-2 col-md-2 col-sm-2">  
        <div class="thumbnail">          
            <a href="#" class="thumbnail">
                <img src="photos/<?php echo "$u->jpegphoto";?>" alt="">
            </a>
        </div>
        </div>    
        <div class="col-xs-10 col-md-10 col-sm-10">  
            <div class="alert alert-success">
            <h4><?php echo "$bp->title";?></h4>
            <?php echo "<strong>Пояснение: </strong>$bp->bodytxt";?>
            <br>
            <strong>Текущий статус: </strong>
            <?php
             if ($bp->status==0){echo '<span class="label label-info">Создано</span>';};
             if ($bp->status==1){echo '<span class="label label-warning">В работе</span>';};
             if ($bp->status==2){echo '<span class="label label-success">Утверждено</span>';};
             if ($bp->status==3){echo '<span class="label label-important">Отменено</span>';};
             if ($bp->status==4){echo '<span class="label label-important">Отправлено на доработку</span>';};
            ?>
            </div>            
        </div>            
    </div>    
