<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
// Грибов Павел,
// Сергей Солодягин (solodyagin@gmail.com)
// (добавляйте себя если что-то делали)
// http://грибовы.рф

/*
 * Назначение:
 *
 * если подключен модуль СМС, то смотрим какие агенты введены.
 * если есть основной агент, то загружаем его "прокладку" для взаимодействия.
 * "прокладка" должна содержать класс smsinfo со следующими вызовами:
 * sms=new SmsAgent
 * sms->sender='bla-bla'
 * sms->login='bla-bla'
 * sms->pass='bla-bla'
 * sms->smsdiff='bla-bla'
 * sms->agentname='bla-bla'
 * sms->login(login,pass)
 * sms->GetBalanse();
 * sms->sendsms(phone,txt)
 */
if (defined('WUO_ROOT') == false) {
    define('WUO_ROOT', dirname(__FILE__));
}

$md = new Tmod(); // обьявляем переменную для работы с классом модуля

if ($md->IsActive('smscenter') == 1) {
    $sql = "SELECT * FROM sms_center_config WHERE sel='Yes'";
    $result = $sqlcn->ExecuteSQL($sql) or die('Не могу прочитать настройки sms_center_config! ' . mysqli_error($sqlcn->idsqlconnection));
    while ($row = mysqli_fetch_array($result)) {
        $fileagent = $row['fileagent'];
        /*
         * @include_once("inc/$fileagent");
         * @include_once("../inc/$fileagent");
         * @include_once("../../inc/$fileagent");
         * @include_once("../../../inc/$fileagent");
         * @include_once("../../../../inc/$fileagent");
         * @include_once("../../../../../inc/$fileagent");
         * @include_once("../../../../../../inc/$fileagent");
         * @include_once("../../../../../../inc/$fileagent");
         */
        @include_once (WUO_ROOT . "/inc/$fileagent");
        @include_once (WUO_ROOT . "/../inc/$fileagent");
        @include_once (WUO_ROOT . "/class/cconfig.php");
        @include_once (WUO_ROOT . "/../class/cconfig.php");
    }
    unset($md);
}
