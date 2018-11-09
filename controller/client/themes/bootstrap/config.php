<?php
   /*
    * Данный код создан и распространяется по лицензии GPL v3
    * Разработчики:
    * Грибов Павел,
    * Сергей Солодягин (solodyagin@gmail.com)
    * (добавляйте себя если что-то делали)
    * http://грибовы.рф
    */
   
   // Запрещаем прямой вызов скрипта.
   defined('WUO_ROOT') or die('Доступ запрещён');
   
   if ($user->mode != 1) {
       die('<div class="alert alert-danger">У вас нет доступа в данный раздел!</div>');
   }
   ?>
<div class="container-fluid">
   <div class="row-fluid">
      <div class="alert alert-info">
         Ваша версия программы: <?php echo "$cfg->version"; ?><br>
         Актуальные версии ПО: <a
            href="https://github.com/donpadlo/webuseorg3/releases"
            target="_blank">github.com</a><br> Документация: <a
            href="http://грибовы.рф/wiki/doku.php" target="_blank">здесь</a>
      </div>
      <form class="form-horizontal" role="form"
         action="?content_page=config&config=save" method="post" name="form1"
         target="_self">
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title">Общие настройки</h3>
            </div>
            <div class="panel-body">
               <div class="form-group">
                  <label for="form_sitename" class="col-sm-2 control-label">Имя
                  сайта:</label>
                  <div class="col-sm-10">
                     <input type="text" class="form-control" name="form_sitename"
                        id="form_sitename" value="<?php echo $cfg->sitename; ?>"
                        placeholder="Название сайта...">
                  </div>
               </div>
               <div class="form-group">
                  <label for="form_sitename" class="col-sm-2 control-label">URL
                  сайта:</label>
                  <div class="col-sm-10">
                     <input type="text" class="form-control" name="urlsite" id="urlsite" value="<?php echo $cfg->urlsite; ?>" placeholder="http://где_мой_сайт" size="80">
                  </div>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title">Оформление</h3>
            </div>
            <div class="panel-body">
               <div class="form-group">
                  <label for="form_cfg_theme" class="col-sm-2 control-label">Текущая тема:</label>
                  <div class="col-sm-2">
                     <input type="text" class="form-control" name="form_cfg_theme" id="form_cfg_theme" readonly="readonly" value="<?php echo $cfg->theme; ?>">
                  </div>
                  <label for="form_cfg_theme_sl" class="col-sm-2 control-label">Выберите тему:</label>
                  <div class="col-sm-6">
                     <select class="form-control" name="form_cfg_theme_sl" id="form_cfg_theme_sl">
                     <?php
                        $arr_themes = GetArrayDir(WUO_ROOT . '/controller/client/themes');                        
                        for ($i = 0; $i < count($arr_themes); $i ++) {
                            $sl = ($arr_themes[$i] == $cfg->theme) ? 'selected' : '';
                            echo "<option $sl value=\"$arr_themes[$i]\">$arr_themes[$i]</option>";
                        }
                        ?>
                     </select>
                  </div>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title">Вход через Active Directory</h3>
            </div>
            <div class="panel-body">
               <div class="col-sm-12 checkbox">
                  <label>
                  <?php $ch = ($cfg->ad == '1') ? 'checked' : ''; ?>
                  <input type="checkbox" name="form_cfg_ad" value="1"
                     <?php echo $ch; ?>>Разрешить вход
                  </label>
               </div>
               <div class="col-sm-4">
                  <label for="form_cfg_ldap" class="control-label">Сервер LDAP:</label>
                  <input type="text" class="form-control" name="form_cfg_ldap"
                     id="form_cfg_ldap" value="<?php echo $cfg->ldap; ?>"
                     placeholder="ldaps://dc1.mydomain.tld">
               </div>
               <div class="col-sm-4">
                  <label for="form_cfg_domain1" class="control-label">Домен 1:</label>
                  <input type="text" class="form-control" name="form_cfg_domain1"
                     id="form_cfg_domain1" value="<?php echo $cfg->domain1; ?>"
                     placeholder="mydomain">
               </div>
               <div class="col-sm-4">
                  <label for="form_cfg_domain2" class="control-label">Домен 2:</label>
                  <input type="text" class="form-control" name="form_cfg_domain2"
                     id="form_cfg_domain2" value="<?php echo $cfg->domain2; ?>"
                     placeholder="tld">
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title">Вход по SSL сертификату</h3>
            </div>
            <div class="panel-body">
               <div class="col-sm-12 checkbox">
                  <label>
                  <?php $ch = ($cfg->from_ssl == '1') ? 'checked' : ''; ?>
                  <input type="checkbox" name="from_ssl" value="1"
                     <?php echo $ch; ?>>Разрешить вход
                  </label>
               </div>
               <div class="col-sm-4">
                  <label for="SSL_SERVER_M_SERIAL" class="control-label">SSL_SERVER_M_SERIAL:</label>
                  <input type="text" class="form-control" name="SSL_SERVER_M_SERIAL"
                     id="SSL_SERVER_M_SERIAL"
                     value="<?php echo $cfg->SSL_SERVER_M_SERIAL; ?>"
                     placeholder="9331CD91055C9Z6E">
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               <h3 class="panel-title">Уведомления</h3>
            </div>
            <div class="panel-body">
               <div class="col-sm-12 checkbox">
                  <label>
                  <?php $ch = ($cfg->sendemail == '1') ? 'checked' : ''; ?>
                  <input type="checkbox" name="form_sendemail" id="form_sendemail"
                     value="1" <?php echo $ch; ?>> Рассылать почтовые уведомления
                  </label>
               </div>
               <div class="col-sm-6">
                  <label for="form_smtphost" class="control-label">SMTP сервер:</label>
                  <input type="text" class="form-control" name="form_smtphost"
                     id="form_smtphost" value="<?php echo $cfg->smtphost; ?>">
                  <div class="checkbox">
                     <label>
                     <?php $ch = ($cfg->smtpauth == '1') ? 'checked' : ''; ?>
                     <input type="checkbox" name="form_smtpauth" id="form_smtpauth"
                        value="1" <?php echo $ch; ?>>Требуется аутенфикация SMTP
                     </label>
                  </div>
                  <label for="form_smtpusername" class="control-label">SMTP имя
                  пользователя:</label> <input type="text" class="form-control"
                     name="form_smtpusername" id="form_smtpusername"
                     value="<?php echo $cfg->smtpusername; ?>"> <label
                     for="form_smtppass" class="control-label">SMTP пароль
                  пользователя:</label> <input type="password" class="form-control"
                     name="form_smtppass" id="form_smtppass"
                     value="<?php echo $cfg->smtppass; ?>"> <label for="form_smtpport"
                     class="control-label">SMTP порт:</label> <input type="text"
                     class="form-control" name="form_smtpport" id="form_smtpport"
                     value="<?php echo $cfg->smtpport; ?>">
               </div>
               <div class="col-sm-6">
                  <label for="form_emailadmin" class="control-label">
                  От кого почта (From):
                  </label> 
                  <input type="text" class="form-control" name="form_emailadmin" id="form_emailadmin" value="<?php echo $cfg->emailadmin; ?>"> 
                  <label for="form_emailreplyto" class="control-label">
                  Куда посылать ответы (Reply-To):
                  </label> 
                  <input type="text" class="form-control" name="form_emailreplyto" id="form_emailreplyto" value="<?php echo $cfg->emailreplyto; ?>">
               </div>
            </div>
         </div>
         <div align="center">
            <button class="btn btn-primary" type="submit" name="Submit">Сохранить изменения</button>
         </div>
      </form>
      <div id="versionsc">
      </div> 
   </div>
</div>
<script>
$(document).ready(function() {   
    html="<h2>Версии ПО:</h3>";
    html=html+'<ul class="list-group">';
    html=html+'<li class="list-group-item">'+"Jquery :"+jQuery.fn.jquery+"</li><br/>";
    html=html+'<li class="list-group-item">'+"JqueryUI :"+$.ui.version+"</li><br/>";
    html=html+'<li class="list-group-item">'+"JqGrid:"+$.jgrid.version+"</li><br/>";
    html=html+'<li class="list-group-item">'+"MMenu:"+jQuery.mmenu.version+"</li><br/>";
    html=html+'<li class="list-group-item">'+"Bootstrap:"+$.fn.tooltip.Constructor.VERSION+"</li><br/>";
    html=html+'</ul>';
    $("#versionsc").html(html);
    
});

</script>    