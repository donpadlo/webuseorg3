<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

define('INCLUDED', true);
define('ROOT', dirname(__FILE__));
?>
<!-- saved from url=(0014)about:internet -->
<!DOCTYPE html>
<html lang="ru">
<head>    
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Учет ТМЦ в организации и другие плюшки">
    <meta name="author" content="(c) 2011-2016 by Gribov Pavel">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>    
    <title>Учет ТМЦ в организции без фанатизма</title>
    <meta name="generator" content="yarus" />
    <meta name="keywords" content="учет тмц" />
    <link href="favicon.ico" type="image/ico" rel="icon" />
    <link href="favicon.ico" type="image/ico" rel="shortcut icon" />    
    <link rel="stylesheet" type="text/css" href="controller/client/themes/bootstrap3/css/bootstrap-theme.min.css">
    <link rel="stylesheet" type="text/css" href="controller/client/themes/bootstrap3/css/bootstrap.min.css">         
    <script type="text/javascript" src="controller/client/themes/bootstrap3/js/jquery-1.11.0.min.js"></script>        
    <script type="text/javascript" src="controller/client/themes/bootstrap3/js/bootstrap.min.js"></script>    
    <script type='text/javascript' src='js/jquery.form.js'></script>    
</head>
<body>   
<script>    
 $(function(){
        var field = new Array("host", "basename", "baseusername","orgname","login","pass");//поля обязательные
        $("form").submit(function() {// обрабатываем отправку формы            
            var error=0; // индекс ошибки
            $("form").find(":input").each(function() {// проверяем каждое поле в форме
                for(var i=0;i<field.length;i++){ // если поле присутствует в списке обязательных
                    if($(this).attr("name")==field[i]){ //проверяем поле формы на пустоту
                        if(!$(this).val()){// если в поле пустое
                            $(this).css('border', 'red 1px solid');// устанавливаем рамку красного цвета
                            error=1;// определяем индекс ошибки
                        }
                        else{
                            $(this).css('border', 'gray 1px solid');// устанавливаем рамку обычного цвета
                        }

                    }
                }
           })
            if(error==0){ // если ошибок нет то отправляем данные
                return true;
            }
            else {
            if(error==1) var err_text = '<div class="alert alert-danger">Не все обязательные поля заполнены!</div>';
            $("#messenger").addClass("alert alert-error");
            $("#messenger").html(err_text);
            $("#messenger").fadeIn("slow");
            return false; //если в форме встретились ошибки , не  позволяем отослать данные на сервер.
            }
        });
            $('#myform').ajaxForm(function(msg) {                 
                if (msg=="ok"){
                    $('#prim').html('<div class="alert alert-info">Внимание!</br>Инсталляция прошла успешно. Вам необходимо:</br> 1) Переименовать файл config.php.dist в config.php</br>2) Открыть его и отредактировать параметры доступа к БД!</br>3) Удалить файл install.php</div>'); 
                } else {
                    $('#messenger').html(msg);                     
                };
                
            });                 
    });            
</script>    
<div class="container-fluid">
  <div class="row">
      <div class="col-xs-6 col-md-4 col-sm-4">
          <div id='messenger'></div>
      </div>
    <div class="col-xs-6 col-md-4 col-sm-4">        
        <div class="panel panel-primary">
            <div class="panel-heading">Инсталляция "Учет ТМЦ"</div>
            <div class="panel-body" id='prim'>
            <form role="form" name='myform' id='myform' action="/inc/install.php?action=install" method="post" target="_self">
              <div class="form-group">
                <label for="host">Сервер MySQL</label>
                <input type="host" class="form-control" name="host" id="host" placeholder="localhost" value="localhost">
                <label for="basename">Имя базы</label>
                <input type="basename" class="form-control" name="basename" id="basename" placeholder="webuser" value="webuser">
                <label for="baseusername">Имя пользователя базы</label>
                <input type="baseusername" class="form-control" name="baseusername" id="baseusername" placeholder="Введите имя пользователя mysql" value="root">
                <label for="passbase">Пароль пользователя</label>
                <input type="password" class="form-control" name="passbase" id="passbase" placeholder="Введите пароль" value="">            
              </div>
              <div class="form-group">
                <label for="orgname">Название организации</label>
                <input type="orgname" class="form-control" name="orgname" id="orgname" placeholder="Введите название организации" value="ООО Рога и Копыта">
                <label for="login">Логин администратора</label>
                <input type="login" class="form-control" name="login" id="login" placeholder="Введите логин администратора" value="admin">
                <label for="pass">Пароль администратора</label>
                <input type="password" class="form-control" name="pass" id="pass" placeholder="Пароль администратора" value="">            
              </div>            
                 <button type="submit" class="btn btn-default">Начать инсталляцию</button>
            </form>    
            </div>
        </div>
    </div>
    <div class="col-xs-6 col-md-4 col-sm-4"></div>
  </div>
</div>
</body>
</html>