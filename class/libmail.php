<?php

class lMail
{

    /*
     * определение переменных идет через VAR, для обеспечения работы в php старых версий
     * массивы адресов кому отправить
     * @var array
     */
    var $sendto = array();

    /*
     * @var array
     */
    var $acc = array();

    /*
     * @var array
     */
    var $abcc = array();

    /*
     * прикрепляемые файлы
     * @var array
     */
    var $aattach = array();

    /*
     * массив заголовков
     * @var array
     */
    var $xheaders = array();

    /*
     * приоритеты
     * @var array
     */
    var $priorities = array(
        '1 (Highest)',
        '2 (High)',
        '3 (Normal)',
        '4 (Low)',
        '5 (Lowest)'
    );

    /*
     * кодировка по умолчанию
     * @var string
     */
    var $charset = "windows-1251";

    var $ctencoding = "8bit";

    var $receipt = 0;

    var $text_html = "text/plain";
 // формат письма. по умолчанию текстовый
    var $smtp_on = false;
 // отправка через smtp. по умолчанию выключена
    var $names_email = array();
 // имена для email адресов, чтобы делать вид ("Антон" <te@gg.ru>)
    
    /*
     * конструктор тоже по старому объявлен для совместимости со старыми версиями php
     * пошел конструктор.
     * входящий параметр кодировка письма
     * внесено изменение
     */
    function Mail($charset = "")
    {
        $this->autoCheck(true);
        $this->boundary = "--" . md5(uniqid("myboundary"));
        
        if ($charset != "") {
            $this->charset = strtolower($charset);
            if ($this->charset == "us-ascii")
                $this->ctencoding = "7bit";
        }
    }

    /*
     *
     * включение выключение проверки валидности email
     * пример: autoCheck( true ) проверка влючена
     * по умолчанию проверка включена
     *
     *
     */
    function autoCheck($bool)
    {
        if ($bool)
            $this->checkAddress = true;
        else
            $this->checkAddress = false;
    }

    /*
     *
     * Тема письма
     * внесено изменения кодирования не латинских символов
     *
     */
    function Subject($subject)
    {
        $this->xheaders['Subject'] = "=?" . $this->charset . "?Q?" . str_replace("+", "_", str_replace("%", "=", urlencode(strtr($subject, "\r\n", "  ")))) . "?=";
    }

    /*
     *
     * от кого
     */
    function From($from)
    {
        if (! is_string($from)) {
            echo "ошибка, From должен быть строкой";
            exit();
        }
        $temp_mass = explode(';', $from); // разбиваем по разделителю для выделения имени
        if (count($temp_mass) == 2) { // если удалось разбить на два элемента
            $this->names_email['from'] = $temp_mass[0]; // имя первая часть
            $this->xheaders['From'] = $temp_mass[1]; // адрес вторая часть
        } else { // и если имя не определено
            $this->names_email['from'] = '';
            $this->xheaders['From'] = $from;
        }
    }

    /*
     * на какой адрес отвечать
     *
     */
    function ReplyTo($address)
    {
        if (! is_string($address))
            return false;
        
        $temp_mass = explode(';', $address); // разбиваем по разделителю для выделения имени
        
        if (count($temp_mass) == 2) { // если удалось разбить на два элемента
            $this->names_email['Reply-To'] = $temp_mass[0]; // имя первая часть
            $this->xheaders['Reply-To'] = $temp_mass[1]; // адрес вторая часть
        } else { // и если имя не определено
            $this->names_email['Reply-To'] = '';
            $this->xheaders['Reply-To'] = $address;
        }
    }

    /*
     * Добавление заголовка для получения уведомления о прочтении. обратный адрес берется из "From" (или из "ReplyTo" если указан)
     *
     */
    function Receipt()
    {
        $this->receipt = 1;
    }

    /*
     * set the mail recipient
     * @param string $to email address, accept both a single address or an array of addresses
     *
     */
    function To($to)
    {
        
        // если это массив
        if (is_array($to)) {
            foreach ($to as $key => $value) { // перебираем массив и добавляем в массив для отправки через smtp
                
                $temp_mass = explode(';', $value); // разбиваем по разделителю для выделения имени
                
                if (count($temp_mass) == 2) { // если удалось разбить на два элемента
                    $this->smtpsendto[$temp_mass[1]] = $temp_mass[1]; // ключи и значения одинаковые, чтобы исключить дубли адресов
                    $this->names_email['To'][$temp_mass[1]] = $temp_mass[0]; // имя первая часть
                    $this->sendto[] = $temp_mass[1];
                } else { // и если имя не определено
                    $this->smtpsendto[$value] = $value; // ключи и значения одинаковые, чтобы исключить дубли адресов
                    $this->names_email['To'][$value] = ''; // имя первая часть
                    $this->sendto[] = $value;
                }
            }
        } else {
            $temp_mass = explode(';', $to); // разбиваем по разделителю для выделения имени
            
            if (count($temp_mass) == 2) { // если удалось разбить на два элемента
                
                $this->sendto[] = $temp_mass[1];
                $this->smtpsendto[$temp_mass[1]] = $temp_mass[1]; // ключи и значения одинаковые, чтобы исключить дубли адресов
                $this->names_email['To'][$temp_mass[1]] = $temp_mass[0]; // имя первая часть
            } else { // и если имя не определено
                
                $this->sendto[] = $to;
                $this->smtpsendto[$to] = $to; // ключи и значения одинаковые, чтобы исключить дубли адресов
                
                $this->names_email['To'][$to] = ''; // имя первая часть
            }
        }
        
        #if ($this->checkAddress == true)
         #   $this->CheckAdresses($this->sendto);
    }

    /*
     * Cc()
     * установка заголдовка CC ( открытая копия, все получатели будут видеть куда ушла копия )
     * $cc : email address(es), accept both array and string
     */
    function Cc($cc)
    {
        if (is_array($cc)) {
            $this->acc = $cc;
            
            foreach ($cc as $key => $value) { // перебираем массив и добавляем в массив для отправки через smtp
                $this->smtpsendto[$value] = $value; // ключи и значения одинаковые, чтобы исключить дубли адресов
            }
        } else {
            $this->acc[] = $cc;
            $this->smtpsendto[$cc] = $cc; // ключи и значения одинаковые, чтобы исключить дубли адресов
        }
        
        if ($this->checkAddress == true)
            $this->CheckAdresses($this->acc);
    }

    /*
     * Bcc()
     * скрытая копия. не будет помещать заголовок кому ушло письмо
     * $bcc : email address(es), accept both array and string
     */
    function Bcc($bcc)
    {
        if (is_array($bcc)) {
            $this->abcc = $bcc;
            foreach ($bcc as $key => $value) { // перебираем массив и добавляем в массив для отправки через smtp
                $this->smtpsendto[$value] = $value; // ключи и значения одинаковые, чтобы исключить дубли адресов
            }
        } else {
            $this->abcc[] = $bcc;
            $this->smtpsendto[$bcc] = $bcc; // ключи и значения одинаковые, чтобы исключить дубли адресов
        }
        
        if ($this->checkAddress == true)
            $this->CheckAdresses($this->abcc);
    }

    /*
     * Body( text [ text_html ] )
     * $text_html в каком формате будет письмо, в тексте или html. по умолчанию стоит текст
     */
    function Body($body, $text_html = "")
    {
        $this->body = $body;
        
        if ($text_html == "html")
            $this->text_html = "text/html";
    }

    /*
     * Organization( $org )
     * set the Organization header
     */
    function Organization($org)
    {
        if (trim($org != ""))
            $this->xheaders['Organization'] = $org;
    }

    /*
     * Priority( $priority )
     * set the mail priority
     * $priority : integer taken between 1 (highest) and 5 ( lowest )
     * ex: $mail->Priority(1) ; => Highest
     */
    function Priority($priority)
    {
        if (! intval($priority))
            return false;
        
        if (! isset($this->priorities[$priority - 1]))
            return false;
        
        $this->xheaders["X-Priority"] = $this->priorities[$priority - 1];
        
        return true;
    }

    /*
     * прикрепленные файлы
     *
     * @param string $filename : путь к файлу, который надо отправить
     * @param string $webi_filename : реальное имя файла. если вдруг вставляется файл временный, то его имя будет хрен пойми каким..
     * @param string $filetype : MIME-тип файла. по умолчанию 'application/x-unknown-content-type'
     * @param string $disposition : инструкция почтовому клиенту как отображать прикрепленный файл ("inline") как часть письма или ("attachment") как прикрепленный файл
     */
    function Attach($filename, $webi_filename = "", $filetype = "", $disposition = "inline")
    {
        // TODO : если типа файла не указан, ставим неизвестный тип
        if ($filetype == "")
            $filetype = "application/x-unknown-content-type";
        
        $this->aattach[] = $filename;
        $this->webi_filename[] = $webi_filename;
        $this->actype[] = $filetype;
        $this->adispo[] = $disposition;
    }

    /*
     *
     * Собираем письмо
     *
     *
     */
    function BuildMail()
    {
        $this->headers = "";
        
        // создание заголовка TO.
        // добавление имен к адресам
        foreach ($this->sendto as $key => $value) {
            
            if (strlen($this->names_email['To'][$value]))
                $temp_mass[] = "=?" . $this->charset . "?Q?" . str_replace("+", "_", str_replace("%", "=", urlencode(strtr($this->names_email['To'][$value], "\r\n", "  ")))) . "?= <" . $value . ">";
            else
                $temp_mass[] = $value;
        }
        
        $this->xheaders['To'] = implode(", ", $temp_mass); // этот заголовок будет не нужен при отправке через mail()
        
        if (count($this->acc) > 0)
            $this->xheaders['CC'] = implode(", ", $this->acc);
        
        if (count($this->abcc) > 0)
            $this->xheaders['BCC'] = implode(", ", $this->abcc); // этот заголовок будет не нужен при отправке через smtp
        
        if ($this->receipt) {
            if (isset($this->xheaders["Reply-To"]))
                $this->xheaders["Disposition-Notification-To"] = $this->xheaders["Reply-To"];
            else
                $this->xheaders["Disposition-Notification-To"] = $this->xheaders['From'];
        }
        
        if ($this->charset != "") {
            $this->xheaders["Mime-Version"] = "1.0";
            $this->xheaders["Content-Type"] = $this->text_html . "; charset=$this->charset";
            $this->xheaders["Content-Transfer-Encoding"] = $this->ctencoding;
        }
        
        $this->xheaders["X-Mailer"] = "WWW.antonlife.ucoz.ru php-mail-V 1.8";
        
        // вставаляем файлы
        if (count($this->aattach) > 0) {
            $this->_build_attachement();
        } else {
            $this->fullBody = $this->body;
        }
        
        // создание заголовков если отправка идет через smtp
        if ($this->smtp_on) {
            
            // разбиваем (FROM - от кого) на юзера и домен. домен понадобится в заголовке
            $user_domen = explode('@', $this->xheaders['From']);
            
            // $this->headers = "Date: " . date("D, j M Y 10:i:s") . "\r\n";
            // $this->headers .= "Message-ID: <" . rand() . "." . date("Ymj10is") . "@" . $user_domen[1] . ">\r\n";
            
            reset($this->xheaders);
            //while (list ($hdr, $value) = each($this->xheaders)) {
	    foreach ($this->xheaders as $hdr=>$value){
                if ($hdr == "From" and strlen($this->names_email['from']))
                    $this->headers .= $hdr . ": =?" . $this->charset . "?Q?" . str_replace("+", "_", str_replace("%", "=", urlencode(strtr($this->names_email['from'], "\r\n", "  ")))) . "?= <" . $value . ">\r\n";
                elseif ($hdr == "Reply-To" and strlen($this->names_email['Reply-To']))
                    $this->headers .= $hdr . ": =?" . $this->charset . "?Q?" . str_replace("+", "_", str_replace("%", "=", urlencode(strtr($this->names_email['Reply-To'], "\r\n", "  ")))) . "?= <" . $value . ">\r\n";
                elseif ($hdr != "BCC")
                    $this->headers .= $hdr . ": " . $value . "\r\n"; // пропускаем заголовок для отправки скрытой копии
            }
        }        // создание заголовоков, если отправка идет через mail()
        else {
            reset($this->xheaders);
            while (list ($hdr, $value) = each($this->xheaders)) {
                if ($hdr == "From" and strlen($this->names_email['from']))
                    $this->headers .= $hdr . ": =?" . $this->charset . "?Q?" . str_replace("+", "_", str_replace("%", "=", urlencode(strtr($this->names_email['from'], "\r\n", "  ")))) . "?= <" . $value . ">\r\n";
                elseif ($hdr == "Reply-To" and strlen($this->names_email['Reply-To']))
                    $this->headers .= $hdr . ": =?" . $this->charset . "?Q?" . str_replace("+", "_", str_replace("%", "=", urlencode(strtr($this->names_email['Reply-To'], "\r\n", "  ")))) . "?= <" . $value . ">\r\n";
                elseif ($hdr != "Subject" and $hdr != "To")
                    $this->headers .= "$hdr: $value\n"; // пропускаем заголовки кому и тему... они вставятся сами
            }
        }
    }

    // включение отправки через smtp используя сокеты
    // после запуска этой функции отправка через smtp включена
    // для отправки через защищенное соединение сервер нужно указывать с добавлением "ssl://" например так "ssl://smtp.gmail.com"
    function smtp_on($smtp_serv, $login, $pass, $port = 25, $timeout = 5)
    {
        $this->smtp_on = true; // включаем отправку через smtp
        
        $this->smtp_serv = $smtp_serv;
        $this->smtp_login = $login;
        $this->smtp_pass = $pass;
        $this->smtp_port = $port;
        $this->smtp_timeout = $timeout;
    }

    function get_data($smtp_conn)
    {
        $data = "";
        while ($str = fgets($smtp_conn, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) == " ") {
                break;
            }
        }
        return $data;
    }

    /*
     * отправка письма
     *
     */
    function Send()
    {
        $this->BuildMail();
        $this->strTo = implode(", ", $this->sendto);
        
        // если отправка без использования smtp
        if (! $this->smtp_on) {
            $res = @mail($this->strTo, $this->xheaders['Subject'], $this->fullBody, $this->headers);
        } else { // если через smtp
            
            if (! $this->smtp_serv or ! $this->smtp_login or ! $this->smtp_pass or ! $this->smtp_port)
                return false; // если нет хотя бы одного из основных данных для коннекта, выходим с ошибкой
                              
            // разбиваем (FROM - от кого) на юзера и домен. юзер понадобится в приветсвии с сервом
            $user_domen = explode('@', $this->xheaders['From']);
            
            $this->smtp_log = '';
            $smtp_conn = fsockopen($this->smtp_serv, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);
            if (! $smtp_conn) {
                $this->smtp_log .= "соединение с сервером не прошло\n\n";
                fclose($smtp_conn);
                return;
            }
            
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            
            fputs($smtp_conn, "EHLO " . $user_domen[0] . "\r\n");
            $this->smtp_log .= "Я: EHLO " . $user_domen[0] . "\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            $code = substr($data, 0, 3); // получаем код ответа
            
            if ($code != 250) {
                $this->smtp_log .= "ошибка приветсвия EHLO \n";
                fclose($smtp_conn);
                return;
            }
            
            fputs($smtp_conn, "AUTH LOGIN\r\n");
            $this->smtp_log .= "Я: AUTH LOGIN\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            $code = substr($data, 0, 3);
            
            if ($code != 334) {
                $this->smtp_log .= "сервер не разрешил начать авторизацию \n";
                fclose($smtp_conn);
                return;
            }
            
            fputs($smtp_conn, base64_encode($this->smtp_login) . "\r\n");
            $this->smtp_log .= "Я: " . base64_encode($this->smtp_login) . "\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            
            $code = substr($data, 0, 3);
            if ($code != 334) {
                $this->smtp_log .= "ошибка доступа к такому юзеру\n";
                fclose($smtp_conn);
                return;
            }
            
            fputs($smtp_conn, base64_encode($this->smtp_pass) . "\r\n");
            $this->smtp_log .= "Я: " . base64_encode($this->smtp_pass) . "\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            
            $code = substr($data, 0, 3);
            if ($code != 235) {
                $this->smtp_log .= "не правильный пароль\n";
                fclose($smtp_conn);
                return;
            }
            
            fputs($smtp_conn, "MAIL FROM:<" . $this->xheaders['From'] . "> SIZE=" . strlen($this->headers . "\r\n" . $this->fullBody) . "\r\n");
            $this->smtp_log .= "Я: MAIL FROM:<" . $this->xheaders['From'] . "> SIZE=" . strlen($this->headers . "\r\n" . $this->fullBody) . "\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            
            $code = substr($data, 0, 3);
            if ($code != 250) {
                $this->smtp_log .= "сервер отказал в команде MAIL FROM\n";
                fclose($smtp_conn);
                return;
            }
            
            foreach ($this->smtpsendto as $keywebi => $valuewebi) {
                fputs($smtp_conn, "RCPT TO:<" . $valuewebi . ">\r\n");
                $this->smtp_log .= "Я: RCPT TO:<" . $valuewebi . ">\n";
                $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
                $code = substr($data, 0, 3);
                if ($code != 250 and $code != 251) {
                    $this->smtp_log .= "Сервер не принял команду RCPT TO\n";
                    fclose($smtp_conn);
                    return;
                }
            }
            
            fputs($smtp_conn, "DATA\r\n");
            $this->smtp_log .= "Я: DATA\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            
            $code = substr($data, 0, 3);
            if ($code != 354) {
                $this->smtp_log .= "сервер не принял DATA\n";
                fclose($smtp_conn);
                return;
            }
            
            fputs($smtp_conn, $this->headers . "\r\n" . $this->fullBody . "\r\n.\r\n");
            $this->smtp_log .= "Я: " . $this->headers . "\r\n" . $this->fullBody . "\r\n.\r\n";
            
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            
            $code = substr($data, 0, 3);
            if ($code != 250) {
                $this->smtp_log .= "ошибка отправки письма\n";
                fclose($smtp_conn);
                return;
            }
            
            fputs($smtp_conn, "QUIT\r\n");
            $this->smtp_log .= "QUIT\r\n";
            $this->smtp_log .= $data = $this->get_data($smtp_conn) . "\n";
            fclose($smtp_conn);
        }
    }

    /*
     * показывает что было отправлено
     *
     */
    function Get()
    {
        if (isset($this->smtp_log)) {
            if ($this->smtp_log) {
                return $this->smtp_log; // если есть лог отправки smtp выведем его
            }
        }
        
        $this->BuildMail();
        $mail = $this->headers . "\n\n";
        $mail .= $this->fullBody;
        return $mail;
    }

    /*
     * проверка мыла
     * возвращает true или false
     */
    function ValidEmail($address)
    {
        
        // если существует современная функция фильтрации данных, то проверять будем этой функцией. появилась в php 5.2
        if (function_exists('filter_list')) {
            $valid_email = filter_var($address, FILTER_VALIDATE_EMAIL);
            if ($valid_email !== false)
                return true;
            else
                return false;
        } else { // а если php еще старой версии, то проверка валидности пойдет старым способом
            if (ereg(".*<(.+)>", $address, $regs)) {
                $address = $regs[1];
            }
            if (ereg("^[^@  ]+@([a-zA-Z0-9\-]+\.)+([a-zA-Z0-9\-]{2}|net|com|gov|mil|org|edu|int)\$", $address))
                return true;
            else
                return false;
        }
    }

    /*
     *
     * проверка массива адресов
     *
     *
     */
    function CheckAdresses($aad)
    {
        for ($i = 0; $i < count($aad); $i ++) {
            if (! $this->ValidEmail($aad[$i])) {
                echo "ошибка : не верный email " . $aad[$i];
                exit();
            }
        }
    }

    /*
     * сборка файлов для отправки
     */
    function _build_attachement()
    {
        $this->xheaders["Content-Type"] = "multipart/mixed;\n boundary=\"$this->boundary\"";
        
        $this->fullBody = "This is a multi-part message in MIME format.\n--$this->boundary\n";
        $this->fullBody .= "Content-Type: " . $this->text_html . "; charset=$this->charset\nContent-Transfer-Encoding: $this->ctencoding\n\n" . $this->body . "\n";
        
        $sep = chr(13) . chr(10);
        
        $ata = array();
        $k = 0;
        
        // перебираем файлы
        for ($i = 0; $i < count($this->aattach); $i ++) {
            
            $filename = $this->aattach[$i];
            
            $webi_filename = $this->webi_filename[$i]; // имя файла, которое может приходить в класс, и имеет другое имя файла
            if (strlen($webi_filename))
                $basename = basename($webi_filename); // если есть другое имя файла, то оно будет таким
            else
                $basename = basename($filename); // а если нет другого имени файла, то имя будет выдернуто из самого загружаемого файла
            
            $ctype = $this->actype[$i]; // content-type
            $disposition = $this->adispo[$i];
            
            if (! file_exists($filename)) {
                echo "ошибка прикрепления файла : файл $filename не существует";
                exit();
            }
            $subhdr = "--$this->boundary\nContent-type: $ctype;\n name=\"$basename\"\nContent-Transfer-Encoding: base64\nContent-Disposition: $disposition;\n  filename=\"$basename\"\n";
            $ata[$k ++] = $subhdr;
            // non encoded line length
            $linesz = filesize($filename) + 1;
            $fp = fopen($filename, 'r');
            $ata[$k ++] = chunk_split(base64_encode(fread($fp, $linesz)));
            fclose($fp);
        }
        $this->fullBody .= implode($sep, $ata);
    }
}

// class Mail
?>
