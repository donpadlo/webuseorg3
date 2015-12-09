<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф


if ($user->mode==1){
?>
<div class="row-fluid">
<div class="alert alert-info">
	Ваша версия программы: <?php echo "$cfg->version";?><br>
	Актуальные версии ПО: <a href="https://github.com/donpadlo/webuseorg3/releases" target="_blank">github.com</a><br>
	Документация: <a href="http://www.грибовы.рф/?page_id=1202" target="_blank">здесь</a>
</div>
<form action="?content_page=config&config=save" method="post" name="form1" target="_self">
<div class="well">        
 <input name="form_sitename" type="text" id="form_sitename" value="<?php echo "$cfg->sitename";?>" class="span12" placeholder="Название сайта..." size=80><br>    
 <input name="urlsite" type="text" id="urlsite" value="<?php echo "$cfg->urlsite";?>" class="span12" placeholder="http://где_мой_сайт" size=80><br>    
 <label class="checkbox">
   <input type="checkbox" name="form_cfg_ad" value="1" <?php if ($cfg->ad=="1") {echo "checked";}?>>Вход через Active Directory
 </label>                    
 <div class="row-fluid">
  <div class="span4">
      <span class="help-block">Сервер LDAP:</span>        
      <input class="span4" name="form_cfg_ldap" type="text" id="form_cfg_ldap" value="<?php echo "$cfg->ldap";?>">
  </div>
  <div class="span4">
    <span class="help-block">Домен 1:</span>
    <input class="span4" name="form_cfg_domain1" type="text" id="form_cfg_domain1" value="<?php echo "$cfg->domain1";?>">
  </div>
  <div class="span4">
    <span class="help-block">Домен 2:</span>
    <input name="form_cfg_domain2" type="text" id="form_cfg_domain2" value="<?php echo "$cfg->domain2";?>">
  </div>  
 </div>  
</div>  
<div class="well">        
 <div class="row-fluid">
  <div class="span4">
      <span class="help-block">Текущая тема</span>        
      <input class="span4" name="form_cfg_theme" type="text" id="form_cfg_theme" readonly="readonly" value="<?php echo "$cfg->theme";?>">
  </div>
  <div class="span8">
    <span class="help-block">Выберите</span>              
    <select name="form_cfg_theme_sl" id="form_cfg_theme_sl">
        <?php
        $arr_themes=GetArrayFilesInDir("controller/client/themes");
        for ($i=0;$i<count($arr_themes);$i++){
            echo "<option value='$arr_themes[$i]'>$arr_themes[$i]</option>";
        };        
    ?>
    </select>    
  </div>
 </div>      
</div>         
<div class="well">          
 <div class="row-fluid">    
  <div class="span4">
    <span class="help-block">Сервер SMTP</span>
    <input name="form_smtphost" type="text" id="form_smtphost" value="<?php echo "$cfg->smtphost";?>">
    <span class="help-block">От кого почта:</span> 
    <input name="form_emailadmin" type="text" id="form_emailadmin" value="<?php echo "$cfg->emailadmin";?>">
    <span class="help-block">Куда шлем ответы:</span> 
    <input name="form_emailreplyto" type="text" id="form_emailreplyto" value="<?php echo "$cfg->emailreplyto";?>">        
  </div>  
  <div class="span4">
      <label class="checkbox">    
       <input type=checkbox name="form_smtpauth" id="form_smtpauth" value=1  <?php if ($cfg->smtpauth=="1") {echo "checked";}?>>Требуется аутенфикация SMTP
      </label>
    <span class="help-block">SMTP имя пользователя:</span>    
    <input name="form_smtpusername" type="text" id="form_smtpusername" value="<?php echo "$cfg->smtpusername";?>">
    <span class="help-block">SMTP пароль пользователя:</span>
    <input name="form_smtppass" type="password" id="form_smtppass" value="<?php echo "$cfg->smtppass";?>">      
  </div>  
  <div class="span4">
   <span class="help-block">SMTP порт:</span>
   <input name="form_smtpport" type="text" id="form_smtpport" value="<?php echo "$cfg->smtpport";?>">      
   <label class="checkbox">  
    <input type=checkbox name="form_sendemail" id="form_sendemail" value=1 <?php if ($cfg->sendemail=="1") {echo "checked";}?>> Рассылать уведомления на почту
  </lavel>       
  </div>    
 </div>  
</div>    
<div align=center><input type="submit"  name="Submit" class="btn btn-primary" value="Сохранить изменения"></div>
</form>                        
</div>
    
</div>
<?php
} else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}
