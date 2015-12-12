<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

if ($user->id==""){
?>
<form class="form-horizontal" action="index.php?content_page=home&login_step=enter" method="post" name="form1" target="_self">
  <input type="text" class="input-small" id="enter_user_login" name="enter_user_login" placeholder="Логин">
  <input type="password" class="input-small" id="enter_user_pass" name="enter_user_pass" placeholder="Пароль">
  <button type="submit" class="btn btn-primary">Войти</button>  
</form>
<?php
} else
{
?>
   <div class="row-fluid">
     <div class="span4">
         <ul class="thumbnails">
          <li class="span12">
            <a href="#" class="thumbnail">
                <img src="photos/<?php echo "$user->jpegphoto";?>" alt="">
            </a>
           </li> 
        </ul>
     </div>
 <div class="span8">
 <ul>
    <li><?php echo "<strong>Логин:</strong> $user->login";?></li>
    <li><?php echo "<strong>Email:</strong> <a href='mailto:$user->email'>$user->email</a>";?></li>
    <li><?php echo "<strong>Имя:</strong> $user->fio";?></li>
    <li><?php echo "<strong>Сотовый тел.:</strong> $user->telephonenumber";?></li>
    <li><?php echo "<strong>Стац.тел.:</strong> $user->homephone";?></li>
    <li><?php echo "<strong>Права:</strong> ";if ($user->mode==0){echo "Пользователь";} else {echo "Администратор";};?></li>
 </ul>
</div>     
</div>
 <form class="form-horizontal" action="index.php?content_page=home&login_step=logout" method="post" name="form1" target="_self">
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-primary">Выйти из <?php echo "$user->login";?></button>
    </div>
  </div>     
 </form> 
 <?php
}
?>