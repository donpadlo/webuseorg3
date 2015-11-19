<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if (isset($_GET["config"])=="save"){
      $cfg->sitename            =ClearMySqlString($sqlcn->idsqlconnection,$_POST["form_sitename"]);
      if (isset($_POST["form_cfg_ad"])){
      $cfg->ad                  =$_POST["form_cfg_ad"];} else {$cfg->ad=0;};
      $cfg->ldap                =$_POST["form_cfg_ldap"];
      $cfg->domain1             =$_POST["form_cfg_domain1"];
      $cfg->domain2             =$_POST["form_cfg_domain2"];
      $cfg->theme               =$_POST["form_cfg_theme"];
      $cfg->emailadmin          =$_POST["form_emailadmin"];
      $cfg->smtphost            =$_POST["form_smtphost"];		// сервер SMTP
      if (isset($_POST["form_smtpauth"])){
      $cfg->smtpauth            =$_POST["form_smtpauth"];		// требуется утенфикация?
        } else {$cfg->smtpauth=0;}
      $cfg->smtpport            =$_POST["form_smtpport"];		// SMTP порт
      $cfg->smtpusername        =$_POST["form_smtpusername"];           // SMTP имя пользователя для входа
      $cfg->smtppass            =$_POST["form_smtppass"];		// SMTP пароль пользователя для входа
      $cfg->emailreplyto        =$_POST["form_emailreplyto"];           // куда слать ответы
      $cfg->urlsite             =$_POST["urlsite"];                     // а где сайт лежит?
      if (isset($_POST["form_sendemail"])){
      $cfg->sendemail           =$_POST["form_sendemail"];		// а вообще будем посылать почту?
      } else {$cfg->sendemail=0;};
      $res=$cfg->SetConfigToBase();
      if ($res==true) {$ok[]="Успешно сохранено!";} else {$err[]="Что-то пошло не так!";};
      $cfg->GetConfigFromBase();
}
