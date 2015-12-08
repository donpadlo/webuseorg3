<?php
// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

# Запрещаем прямой вызов скрипта.
defined('INCLUDED') or die('Restricted access');

if (isset($_GET['config']) == 'save') {
	$cfg->sitename = ClearMySqlString($sqlcn->idsqlconnection, $_POST['form_sitename']);
	$cfg->ad = (isset($_POST['form_cfg_ad'])) ? $_POST['form_cfg_ad'] : 0;
	$cfg->ldap = $_POST['form_cfg_ldap'];
	$cfg->domain1 = $_POST['form_cfg_domain1'];
	$cfg->domain2 = $_POST['form_cfg_domain2'];
	$cfg->theme = $_POST['form_cfg_theme_sl'];
	$cfg->emailadmin = $_POST['form_emailadmin'];
	$cfg->smtphost = $_POST['form_smtphost'];                                           // Сервер SMTP
	$cfg->smtpauth = (isset($_POST['form_smtpauth'])) ? $_POST['form_smtpauth'] : 0;    // Требуется утенфикация?
	$cfg->smtpport = $_POST['form_smtpport'];                                           // SMTP порт
	$cfg->smtpusername = $_POST['form_smtpusername'];                                   // SMTP имя пользователя для входа
	$cfg->smtppass = $_POST['form_smtppass'];                                           // SMTP пароль пользователя для входа
	$cfg->emailreplyto = $_POST['form_emailreplyto'];                                   // Куда слать ответы
	$cfg->urlsite = $_POST['urlsite'];                                                  // А где сайт лежит?
	$cfg->sendemail = (isset($_POST['form_sendemail'])) ? $_POST['form_sendemail'] : 0; // А вообще будем посылать почту?
	$res = $cfg->SetConfigToBase();
	if ($res == true) {
		$ok[] = 'Успешно сохранено!';
	} else {
		$err[] = 'Что-то пошло не так!';
	};
	$cfg->GetConfigFromBase();
}
