<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф


if ($user->mode==1){
?>
<div class="container-fluid">
<div class="row">    
    <div class="col-xs-12 col-md-12 col-sm-12">
            <div class="alert alert-info">
                Ваша версия программы: <?php echo "$cfg->version";?><br>
                Актуальные версии ПО: <a href="https://github.com/donpadlo/webuseorg3/releases" target="_blank">github.com</a><br>
                Документация: <a href="http://www.грибовы.рф/?page_id=1202" target="_blank">здесь</a>
                </br>
                <iframe src="https://money.yandex.ru/embed/donate.xml?account=410012866830556&amp;quickpay=donate&amp;payment-type-choice=on&amp;default-sum=50&amp;targets=%D0%A0%D0%B0%D0%B7%D0%B2%D0%B8%D1%82%D0%B8%D0%B5+%D0%BF%D1%80%D0%BE%D0%B5%D0%BA%D1%82%D0%B0&amp;target-visibility=on&amp;project-name=%D0%A3%D1%87%D0%B5%D1%82+%D0%BE%D1%80%D0%B3%D1%82%D0%B5%D1%85%D0%BD%D0%B8%D0%BA%D0%B8+%D0%B8+%D0%A2%D0%9C%D0%A6+%D0%B2+%D0%B1%D1%80%D0%B0%D1%83%D0%B7%D0%B5%D1%80%D0%B5&amp;project-site=http%3A%2F%2F%D0%B3%D1%80%D0%B8%D0%B1%D0%BE%D0%B2%D1%8B.%D1%80%D1%84%2F%3Fpage_id%3D1202&amp;button-text=01&amp;successURL=" width="523" height="134" frameborder="0" scrolling="no"></iframe>                
            </div>
            <form role="form" action="?content_page=config&config=save" method="post" name="form1" target="_self">                            
                <div class="form-group">
                    <label for="form_sitename">Имя сайта</label>    
                    <input name="form_sitename" type="text" id="form_sitename" value="<?php echo "$cfg->sitename";?>" class="form-control" placeholder="Название сайта..."><br>    
                    <label for="form_sitename">URL сайта</label>    
                    <input name="urlsite" type="text" id="urlsite" value="<?php echo "$cfg->urlsite";?>" class="form-control" placeholder="http://где_мой_сайт" size=80><br>    
                    <div class="row-fluid">
                     <div class="col-xs-4 col-md-4 col-sm-4">
                         <span class="help-block">Текущая тема</span>        
                         <input class="form-control" name="form_cfg_theme" type="text" id="form_cfg_theme" readonly="readonly" value="<?php echo "$cfg->theme";?>">
                     </div>
                     <div class="col-xs-8 col-md-8 col-sm-8">
                       <span class="help-block">Выберите</span>              
                       <select class="form-control" name="form_cfg_theme_sl" id="form_cfg_theme_sl">
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
             </hr>   
             <div class="row-fluid">
              <div class="col-xs-4 col-md-4 col-sm-4">
                  <span class="help-block">Сервер LDAP:</span>        
                  <input class="form-control" name="form_cfg_ldap" type="text" id="form_cfg_ldap" value="<?php echo "$cfg->ldap";?>">
                    <div class="checkbox">             
                      <label>  
                        <input type="checkbox" name="form_cfg_ad" value="1" <?php if ($cfg->ad=="1") {echo "checked";}?>>Вход через Active Directory                   
                      </label>
                    </div>                      
              </div>
              <div class="col-xs-4 col-md-4 col-sm-4">
                <span class="help-block">Домен 1:</span>
                <input class="form-control" name="form_cfg_domain1" type="text" id="form_cfg_domain1" value="<?php echo "$cfg->domain1";?>">
              </div>
              <div class="col-xs-4 col-md-4 col-sm-4">
                <span class="help-block">Домен 2:</span>
                <input class="form-control" name="form_cfg_domain2" type="text" id="form_cfg_domain2" value="<?php echo "$cfg->domain2";?>">
              </div>  
             </div>                  
            </hr>            
             <div class="row-fluid">    
              <div class="col-xs-4 col-md-4 col-sm-4">
                <span class="help-block">Сервер SMTP</span>
                <input class="form-control" name="form_smtphost" type="text" id="form_smtphost" value="<?php echo "$cfg->smtphost";?>">
                <span class="help-block">От кого почта:</span> 
                <input class="form-control" name="form_emailadmin" type="text" id="form_emailadmin" value="<?php echo "$cfg->emailadmin";?>">
                <span class="help-block">Куда шлем ответы:</span> 
                <input class="form-control" name="form_emailreplyto" type="text" id="form_emailreplyto" value="<?php echo "$cfg->emailreplyto";?>">        
              </div>  
              <div class="col-xs-4 col-md-4 col-sm-4">
                  <div class="checkbox">             
                    <label>    
                     <input type=checkbox name="form_smtpauth" id="form_smtpauth" value=1  <?php if ($cfg->smtpauth=="1") {echo "checked";}?>>Требуется аутенфикация SMTP
                    </label>
                  </div>    
                <span class="help-block">SMTP имя пользователя:</span>    
                <input class="form-control" name="form_smtpusername" type="text" id="form_smtpusername" value="<?php echo "$cfg->smtpusername";?>">
                <span class="help-block">SMTP пароль пользователя:</span>
                <input class="form-control" name="form_smtppass" type="password" id="form_smtppass" value="<?php echo "$cfg->smtppass";?>">      
              </div>  
              <div class="col-xs-4 col-md-4 col-sm-4">
               <span class="help-block">SMTP порт:</span>
               <input class="form-control" name="form_smtpport" type="text" id="form_smtpport" value="<?php echo "$cfg->smtpport";?>">      
                   <div class="checkbox">             
                        <label>  
                         <input type=checkbox name="form_sendemail" id="form_sendemail" value=1 <?php if ($cfg->sendemail=="1") {echo "checked";}?>> Рассылать уведомления
                       </label>       
                   </div>
              </div>    
             </div>  
            
            <div align=center>
                <input type="submit"  name="Submit" class="btn btn-primary" value="Сохранить изменения"></div>
            </form>          
    </div>                          
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