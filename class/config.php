<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tconfig {

	var $mysql_host, $mysql_user, $mysql_pass, $mysql_base; // настойки соединения с БД
	var $base_id;
	var $sitename;   // название сайта
	var $ad;   // использовать для аутенфикации Active Directory 0-нет 1-да
	var $domain1 = 'khortitsa';   // домен AD первого уровня (например khortitsa)
	var $domain2;   // домен AD второго уровня (например com)
	var $ldap;   // сервер ldap
	var $usercanregistrate; // может ли пользователь регистрироваться на сайте сам 1-да
	var $useraddfromad;  // добавлять ли учетку автоматически при заходе пользователь через AD. 1-да
	var $theme;  // тема по умолчанию
	var $emailadmin; // от кого будем посылать почту
	var $smtphost;  // сервер SMTP
	var $smtpauth;  // требуется утенфикация?
	var $smtpport, $smtpusername, $smtppass;  // SMTP порт,пользователь,пароль пользователя для входа
	var $emailreplyto; // куда слать ответы
	var $sendemail;  // а вообще будем посылать почту?
	var $version;  // версия платформы	
	var $defaultorgid;   // организация "по умолчанию". Выбирается или по кукисам или первая из списка организаций
	var $urlsite;  // где находится сайт http://
	var $navbar = array(); // навигационная последовательность 
	var $quickmenu = array(); // "быстрое меню"
        var $style="Bootstrap";   //стиль грида по умолчанию  
	var $fontsize="12px";   //стиль грида по умолчанию  

	function GetConfigFromBase() {
		global $sqlcn;
		$result = $sqlcn->ExecuteSQL('SELECT * FROM config')
				or die('Неверный запрос Tconfig.GetConfigFromBase: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->ad = $myrow['ad'];
			$this->domain1 = $myrow['domain1'];
			$this->domain2 = $myrow['domain2'];
			$this->sitename = htmlspecialchars($myrow['sitename'], ENT_QUOTES);
			$this->usercanregistrate = $myrow['usercanregistrate'];
			$this->useraddfromad = $myrow['useraddfromad'];
			$this->ldap = $myrow['ldap'];
			$this->theme = $myrow['theme'];
			$this->emailadmin = htmlspecialchars($myrow['emailadmin']);
			$this->smtphost = $myrow['smtphost'];  // сервер SMTP
			$this->smtpauth = $myrow['smtpauth'];  // требуется утенфикация?
			$this->smtpport = $myrow['smtpport'];  // SMTP порт
			$this->smtpusername = stripslashes($myrow['smtpusername']); // SMTP имя пользователя для входа
			$this->smtppass = stripslashes($myrow['smtppass']);  // SMTP пароль пользователя для входа
			$this->emailreplyto = stripslashes($myrow['emailreplyto']); // куда слать ответы
			$this->sendemail = $myrow['sendemail'];  // а вообще будем посылать почту?
			$this->version = $myrow['version'];
			$this->urlsite = $myrow['urlsite'];

			if (isset($_COOKIE['stl'])) {
                         $this->style=$_COOKIE['stl'];
                        } else {$this->style="Bootstrap";};

			if (isset($_COOKIE['fontsize'])) {
                         $this->fontsize=$_COOKIE['fontsize'];
                        } else {$this->fontsize="12px";};
			
			if (isset($_COOKIE['defaultorgid'])) {
				$this->defaultorgid = $_COOKIE['defaultorgid'];
			} else {
				$result2 = $sqlcn->ExecuteSQL('SELECT * FROM org WHERE active=1 ORDER BY id ASC LIMIT 1');
				while ($myrow = mysqli_fetch_array($result2)) {
					$this->defaultorgid = $myrow['id'];
				}
			}
		}
	}

	function SetConfigToBase() {
		global $sqlcn;
		$sql = "UPDATE config SET ad='$this->ad',
		domain1='$this->domain1',domain2='$this->domain2',sitename='$this->sitename',theme='$this->theme',
		usercanregistrate='1',ldap='$this->ldap',emailadmin='$this->emailadmin',
		smtphost='$this->smtphost',smtpauth='$this->smtpauth',smtpport='$this->smtpport',smtpusername='$this->smtpusername',
		smtppass='$this->smtppass',emailreplyto='$this->emailreplyto',sendemail='$this->sendemail',urlsite='$this->urlsite'";
		$sqlcn->ExecuteSQL($sql)
				or die('Неверный запрос Tconfig.SetToBase: '.mysqli_error($sqlcn->idsqlconnection));
		return true;
	}

}
