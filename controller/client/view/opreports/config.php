<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
function Connect1C($htp_str)
{
    if (! function_exists('is_soap_fault')) {
        print 'Не настроен web сервер. Не найден модуль php-soap.';
        return false;
    }
    try {
        $Клиент1С = new SoapClient($htp_str, array(
            'login' => 'Administrator',
            'password' => 'padlopavel',
            'soap_version' => SOAP_1_2,
            'cache_wsdl' => WSDL_CACHE_NONE, // WSDL_CACHE_MEMORY, //, WSDL_CACHE_NONE, WSDL_CACHE_DISK or WSDL_CACHE_BOTH
            'exceptions' => true,
            'trace' => 1
        ));
    } catch (SoapFault $e) {
        trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
        var_dump($e);
    }
    // echo 'Раз<br>';
    if (is_soap_fault($Клиент1С)) {
        trigger_error('Ошибка подключения или внутренняя ошибка сервера. Не удалось связаться с базой 1С.', E_ERROR);
        return false;
    }
    return $Клиент1С;
}

?>