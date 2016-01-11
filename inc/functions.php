<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

/**
 * Массив переданных скрипту параметров при загрузке его через index.php
 * Например, index.php?route=/script.php?name=value
 * $PARAMS['name']
 */
$PARAMS = array();

/**
 * Возвращает значение $_GET[$name] или $def
 * @param string $name
 * @param string $def
 * @return string
 */
function GetDef($name, $def = '') {
    global $_GET,$PARAMS;
	if (isset($_GET["$name"])) {
		return $_GET["$name"];
	} else if (isset($PARAMS[$name])) {
		return $PARAMS[$name];
	} else {
		return $def;
	}
}

/**
 * Возвращает значение $_POST[$name] или $def
 * @param string $name
 * @param string $def
 * @return string
 */
function PostDef($name, $def = '') {
        global $_POST,$PARAMS;
	return (isset($_POST[$name])) ? $_POST[$name] : $def;
}

/** Проверка, а есть ли содержимое $_GET[] и присвоение пустого значения или содержимого
 * @param type $name
 * @return string
 */
function _GET($name) {
	return (isset($_GET[$name])) ? $_GET[$name] : '';
}

/** Проверка, а есть ли содержимое $_POST[] и присвоение пустого значения или содержимого
 * @param type $name
 * @return string
 */
function _POST($name) {
	return (isset($_POST[$name])) ? $_POST[$name] : '';
}

/** на выходе - массив из папок в укзанной папке
 * @param type $dir
 * @return type
 */
function GetArrayFilesInDir($dir) {
	$includes_dir = opendir("$dir");
	$files = array();
	while (($inc_file = readdir($includes_dir)) != false) {
		if (($inc_file != '.') and ( $inc_file != '..')) {
			$files[] = $inc_file;
		}
	}
	closedir($includes_dir);
	sort($files);
	return $files;
}

// проверяем аутентификацию в AD 
function check_LDAP_user($username, $password, $ladpserver, $domain1, $domain2) { // результат true если в AD такой пользователь есть
	global $db1, $_POST, $_SESSION;
	$HDR_ERR = '';
	if ((!$password) or (!$username)) {
		$HDR_ERR = 'false';
	} else {
		$filter = "(&(objectClass=top)(sAMAccountName=".$username."))";
		$basedn = 'dc=$domain1,dc=$domain2';
		$dn = "$domain1\\$username";
		$ldapconn = ldap_connect("$ladpserver");
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		if (!$ldapconn) {
			$HDR_ERR .= ldap_error($ldapconn);
		} else {
			$bind = @ldap_bind($ldapconn, $dn, $password);
			if ($bind == '1') {
				$HDR_ERR = 'true';
			} else {
				$HDR_ERR = 'false';
			}
		}
	}
	return ($HDR_ERR);
}

/** Получить случайный идентификатор длинной $n
 * @param type $n
 * @return string
 */
function GetRandomId($n) { // результат - случайная строка из цифр длинной n
	$id = '';
	for ($i = 1; $i <= $n; $i++) {
		$id .= chr(rand(48, 56));
	}
	return $id;
}

function ClearMySqlString($link, $text) { // чистим текст от мусора, козявок, иньекций и т.п.
	$text = trim($text);  // обрубаем пробелы слева и справа
	$text = preg_replace("/[^\x20-\xFF]/", '', @strval($text));
	$text = mysqli_real_escape_string($link, $text);
	//      $text=htmlspecialchars($text,ENT_QUOTES);
	return $text;
}

// Преобразует дату типа dd.mm.2012 в формат MySQL 2012-01-01 00:00:00
function DateToMySQLDateTime2($dt) {
	$str_exp = explode('.', $dt);
	//$str_exp2 = explode(" ", $str_exp[2]);
	//$dtt=$str_exp2[0]."-".$str_exp[1]."-".$str_exp[0]." $str_exp2[1]:00";   
	if ((strpos($str_exp[2], ' ') === FALSE)) {
		$dtt = $str_exp[2].'-'.$str_exp[1].'-'.$str_exp[0];
	} else {
		//   echo "$str_exp[2]";
		$st2 = explode(' ', $str_exp[2]);
		$yy = trim($st2[0]);
		$dtt = $yy.'-'.$str_exp[1].'-'.$str_exp[0];
	}
	return $dtt;
}

// Преобразует дату MySQL 2012-01-01 00:00:00 в dd.mm.2012 00:00:00
function MySQLDateTimeToDateTime($dt) {
	$str1 = explode('-', $dt);
	$str2 = explode(' ', $str1[2]);
	$dtt = $str2[0].'.'.$str1[1].'.'.$str1[0].' '.$str2[1];
	return $dtt;
}

// Преобразует дату MySQL 2012-01-01 00:00:00 в dd.mm.2012 00:00:00
function MySQLDateToDate($dt) {
	$str1 = explode('-', $dt);
	$dtt = $str1[2].'.'.$str1[1].'.'.$str1[0];
	return $dtt;
}

// Преобразует дату MySQL 2012-01-01 00:00:00 в dd.mm.2012
function MySQLDateTimeToDateTimeNoTime($dt) {
	$str1 = explode('-', $dt);
	$str2 = explode(' ', $str1[2]);
	$dtt = $str2[0].'.'.$str1[1].'.'.$str1[0];
	return $dtt;
}

// Получаем последнюю "закрепленную" новость
function GetStiker() {
	global $sqlcn;
	$stik['body'] = '';
	$stik['title'] = '';
	$result = $sqlcn->ExecuteSQL('SELECT * FROM news WHERE stiker=1 ORDER BY id LIMIT 1')
			or die('Неверный запрос GetStiker: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$stik['body'] = $myrow['body'];
		$stik['title'] = $myrow['title'];
	}
	return $stik;
}

// Получаем последнюю "закрепленную" новость
function GetPostOrgByid($id) {
	global $sqlcn;
	$result = $sqlcn->ExecuteSQL("SELECT * FROM post_users WHERE id='$id'")
			or die('Неверный запрос GetPostOrgByid: '.mysqli_error($sqlcn->idsqlconnection));
	$nm = '';
	while ($myrow = mysqli_fetch_array($result)) {
		$nm = $myrow['post'];
	}
	return $nm;
}

function GetArrayOrgs() { // Возврат - массив активных организаций  
	global $sqlcn;
	$cnt = 0;
	$mOrgs = array();
	$result = $sqlcn->ExecuteSQL('SELECT * FROM org WHERE active=1 ORDER BY id ASC')
			or die('Неверный запрос GetArrayOrgs: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$mOrgs[$cnt]['id'] = $myrow['id'];
		$mOrgs[$cnt]['name'] = $myrow['name'];
		$mOrgs[$cnt]['picnmap'] = $myrow['picmap'];
		$mOrgs[$cnt]['active'] = $myrow['active'];
		$cnt++;
	}
	return $mOrgs;
}

function GetArrayKnt() { // Возврат - массив активных организаций  
	global $sqlcn;
	$cnt = 0;
	$mOrgs = array();
	$result = $sqlcn->ExecuteSQL("SELECT * FROM knt WHERE active=1 ORDER BY name")
			or die('Неверный запрос GetArrayKnt: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$mOrgs[$cnt]['id'] = $myrow['id'];
		$mOrgs[$cnt]['name'] = $myrow['name'];
		$mOrgs[$cnt]['active'] = $myrow['active'];
		$cnt++;
	}
	return $mOrgs;
}

function mailq($to, $subject, $content, $attach = false) {
	global $cfg;
	sendMailAttachment($to, $cfg->smtpusername, $subject, $content);
	//echo "!$cfg->emailadmin;	// от кого будем посылать почту<br>";
	//echo "!$cfg->smtphost;		// сервер SMTP<br>";
	//echo "!$cfg->smtpauth;		// требуется утенфикация?<br>";
	//echo "!$cfg->smtpport;		// SMTP порт<br>";
	//echo "!$cfg->smtpusername;	// SMTP имя пользователя для входа<br>";
	//echo "!$cfg->smtppass;		// SMTP пароль пользователя для входа<br>";
	//echo "!$cfg->emailreplyto;	// куда слать ответы<br>";
	//echo "!$cfg->sendemail;			<br>";		
	/* $mail = new PHPMailer(true);		
	  $mail->IsSMTP();
	  $mail->Host       = $cfg->smtphost;
	  $mail->SMTPDebug  = 0;
	  $mail->Encoding = '8bit';
	  $mail->CharSet = 'utf-8';
	  $mail->SMTPAuth   = $cfg->smtpauth;
	  $mail->Port       = $cfg->smtpport;
	  $mail->Username   = $cfg->smtpusername;
	  $mail->Password   = $cfg->smtppass;
	  $mail->AddReplyTo($cfg->emailadmin, $cfg->smtpusername);
	  $mail->AddAddress($to);                //кому письмо
	  $mail->SetFrom($cfg->emailadmin,  $cfg->smtpusername); //от кого (желательно указывать свой реальный e-mail на используемом SMTP сервере
	  $mail->AddReplyTo($cfg->emailadmin,  $cfg->smtpusername);
	  $mail->Subject = htmlspecialchars($subject);
	  //$mail->header="Content-type: text/html; Charset=UTF-8";
	  $mail->MsgHTML($content);
	  if($attach)  $mail->AddAttachment($attach);
	  $mail->Send();
	 */
}

// заносит письмо в очередь для отправки
function smtpmail($to, $subject, $content, $attach = false) {
	global $sqlcn;
	$sql = "INSERT INTO mailq (id,`from`,`to`,`title`,btxt) VALUES (null,'','$to','$subject','$content')";
	$sqlcn->ExecuteSQL($sql)
			or die("Не записать  очередь сообщений".mysqli_error($sqlcn->idsqlconnection));
}

function DoubleLogin($login) { // проверяем есть ли дубли логинов в базе. Результат - количество логинов
	global $sqlcn;
	$cnt = 0;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(id) as cnt FROM users WHERE login='$login'")
			or die('Неверный запрос DoubleLogin: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$cnt = $myrow['cnt'];
	}
	return $cnt;
}

function DoubleEmail($email) { // проверяем есть ли дубли логинов в базе. Результат - количество логинов
	global $sqlcn;
	$cnt = 0;
	$result = $sqlcn->ExecuteSQL("SELECT COUNT(id) as cnt FROM users WHERE email='$email'")
			or die('Неверный запрос DoubleEmail: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$cnt = $myrow['cnt'];
	}
	return $cnt;
}

function GetUserIdByPostId($id) {
	global $sqlcn;
	$result = $sqlcn->ExecuteSQL("SELECT * FROM post_users WHERE id='$id'")
			or die('Неверный запрос GetUserIdByPostId: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$uid = $myrow['userid'];
	}
	return $uid;
}

function ReUpdateRepairEq() {
	global $sqlcn;
	// листаем весь список ТМЦ
	$result = $sqlcn->ExecuteSQL('SELECT * FROM equipment')
			or die('Неверный запрос ReUpdateRepairEq: '.mysqli_error($sqlcn->idsqlconnection));
	while ($myrow = mysqli_fetch_array($result)) {
		$uid = $myrow['id'];
		// Для каждого ТМЦ проверяем "что у нас с ремонтами"
		$result2 = $sqlcn->ExecuteSQL("SELECT * FROM repair WHERE eqid='$uid' ORDER BY id DESC LIMIT 1")
				or die('Неверный запрос ReUpdateRepairEq: '.mysqli_error($sqlcn->idsqlconnection));
		$rs = 0;
		while ($myrow2 = mysqli_fetch_array($result2)) {
			$rs = $myrow2['status'];
		}
		$sqlcn->ExecuteSQL("UPDATE equipment SET repair='$rs' WHERE id='$uid'")
				or die('Неверный запрос ReUpdateRepairEq: '.mysqli_error($sqlcn->idsqlconnection));
	}
}

function real_date_diff($date1, $date2 = NULL) {
	$diff = array();

	//Если вторая дата не задана принимаем ее как текущую
	if (!$date2) {
		$cd = getdate();
		$date2 = $cd['year'].'-'.$cd['mon'].'-'.$cd['mday'].' '.$cd['hours'].':'.$cd['minutes'].':'.$cd['seconds'];
	}

	//Преобразуем даты в массив
	$pattern = '/(\d+)-(\d+)-(\d+)(\s+(\d+):(\d+):(\d+))?/';
	preg_match($pattern, $date1, $matches);
	$d1 = array((int) $matches[1], (int) $matches[2], (int) $matches[3], (int) $matches[5], (int) $matches[6], (int) $matches[7]);
	preg_match($pattern, $date2, $matches);
	$d2 = array((int) $matches[1], (int) $matches[2], (int) $matches[3], (int) $matches[5], (int) $matches[6], (int) $matches[7]);

	//Если вторая дата меньше чем первая, меняем их местами
	for ($i = 0; $i < count($d2); $i++) {
		if ($d2[$i] > $d1[$i])
			break;
		if ($d2[$i] < $d1[$i]) {
			$t = $d1;
			$d1 = $d2;
			$d2 = $t;
			break;
		}
	}

	//Вычисляем разность между датами (как в столбик)
	$md1 = array(31, $d1[0] % 4 || (!($d1[0] % 100) && $d1[0] % 400) ? 28 : 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$md2 = array(31, $d2[0] % 4 || (!($d2[0] % 100) && $d2[0] % 400) ? 28 : 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	$min_v = array(NULL, 1, 1, 0, 0, 0);
	$max_v = array(NULL, 12, $d2[1] == 1 ? $md2[11] : $md2[$d2[1] - 2], 23, 59, 59);
	for ($i = 5; $i >= 0; $i--) {
		if ($d2[$i] < $min_v[$i]) {
			$d2[$i - 1] --;
			$d2[$i] = $max_v[$i];
		}
		$diff[$i] = $d2[$i] - $d1[$i];
		if ($diff[$i] < 0) {
			$d2[$i - 1] --;
			$i == 2 ? $diff[$i] += $md1[$d1[1] - 1] : $diff[$i] += $max_v[$i] - $min_v[$i] + 1;
		}
	}

	//Возвращаем результат
	return $diff;
}

function sendMailAttachment($mailTo, $from, $subject, $message, $file = false) {
	$ffn = basename($file);
	$separator = "---"; // разделитель в письме
	// Заголовки для письма
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: $from\nReply-To: $from\r\n"; // задаем от кого письмо
	//  $headers .= "Content-Type: text/html; charset=utf-8\r\n";   
	$headers .= "Content-Type: multipart/mixed; boundary=\"$separator\""; // в заголовке указываем разделитель
	// если письмо с вложением
	if ($file) {
		$bodyMail = "--$separator\n"; // начало тела письма, выводим разделитель
		$bodyMail .= "Content-type: text/html; charset='utf-8'\n"; // кодировка письма
		$bodyMail .= "Content-Transfer-Encoding: quoted-printable"; // задаем конвертацию письма
		$bodyMail .= "Content-Disposition: attachment; filename=?utf-8?B?".base64_encode(basename($file))."?=\n\n"; // задаем название файла
		$bodyMail .= $message."\n"; // добавляем текст письма
		$bodyMail .= "--$separator\n";
		$fileRead = fopen($file, "r"); // открываем файл
		$contentFile = fread($fileRead, filesize($file)); // считываем его до конца
		fclose($fileRead); // закрываем файл
		$bodyMail .= "Content-Type: application/octet-stream; name=\"$ffn\"\n";
		$bodyMail .= "Content-Transfer-Encoding: base64\n"; // кодировка файла
		$bodyMail .= "Content-Disposition: attachment; filename=\"$ffn\"\n\n";
		$bodyMail .= chunk_split(base64_encode($contentFile))."\n"; // кодируем и прикрепляем файл
		$bodyMail .= "--".$separator."--\n";
		// письмо без вложения
	} else {
		$bodyMail = "--$separator\n"; // начало тела письма, выводим разделитель
		$bodyMail .= "Content-type: text/html; charset='utf-8'\n"; // кодировка письма
		$bodyMail = $bodyMail.$message."\n";
	}
	$result = mail($mailTo, $subject, $bodyMail, $headers); // отправка письма
	return $result;
}

function generate_password($number) {
	$arr = array('a', 'b', 'c', 'd', 'e', 'f',
		'g', 'h', 'i', 'j', 'k', 'l',
		'm', 'n', 'o', 'p', 'r', 's',
		't', 'u', 'v', 'x', 'y', 'z',
		'A', 'B', 'C', 'D', 'E', 'F',
		'G', 'H', 'I', 'J', 'K', 'L',
		'M', 'N', 'O', 'P', 'R', 'S',
		'T', 'U', 'V', 'X', 'Y', 'Z',
		'1', '2', '3', '4', '5', '6',
		'7', '8', '9', '0');
	// Генерируем пароль
	$pass = '';
	for ($i = 0; $i < $number; $i++) {
		// Вычисляем случайный индекс массива
		$index = rand(0, count($arr) - 1);
		$pass .= $arr[$index];
	}
	return $pass;
}

function getLastDayOfMonth($dateInISO8601) {
	// Проверяем дату на корректность
	$date = explode('-', $dateInISO8601);
	if (!checkdate($date[1], $date[2], $date[0]))
		return false;

	$start = new DateTime($dateInISO8601);
	$end = new DateTime($dateInISO8601);
	$end->add(new DateInterval('P2M'));
	$interval = new DateInterval('P1D');
	$daterange = new DatePeriod($start, $interval, $end);

	$prev = $start;
	// Проходимся по периодам, если номер месяца
	// предыдущего периода не совпадает с текущим номером месяца
	// то возвращаем последний день предыдущего месяца
	foreach ($daterange as $date) {
		if ($prev->format('m') != $date->format('m'))
			return (int) $prev->format('d');

		$prev = $date;
	}

	return false;
}

function generateEAN($number) {
	$code = '480'.str_pad($number, 9, '0');
	$weightflag = true;
	$sum = 0;
	// Weight for a digit in the checksum is 3, 1, 3.. starting from the last digit. 
	// loop backwards to make the loop length-agnostic. The same basic functionality 
	// will work for codes of different lengths.
	for ($i = strlen($code) - 1; $i >= 0; $i--) {
		$sum += (int) $code[$i] * ($weightflag ? 3 : 1);
		$weightflag = !$weightflag;
	}
	$code .= (10 - ($sum % 10)) % 10;
	return $code;
}

function get_duration_dates($date_from, $date_till) {
	$date_from = explode('-', $date_from);
	$date_till = explode('-', $date_till);

	$time_from = @mktime(0, 0, 0, $date_from[1], $date_from[2], $date_from[0]);
	$time_till = @mktime(0, 0, 0, $date_till[1], $date_till[2], $date_till[0]);

	$diff = ($time_till - $time_from) / 60 / 60 / 24;
	//$diff = date('d', $diff); - как делал))

	return $diff;
}

function generateSalt() {
	$salt = '';
	$length = rand(5, 10); // длина соли (от 5 до 10 сомволов)
	for ($i = 0; $i < $length; $i++) {
		$salt .= chr(rand(33, 126)); // символ из ASCII-table
	}
	return $salt;
}

function jsonExit($data) {
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($data);
	exit;
}
