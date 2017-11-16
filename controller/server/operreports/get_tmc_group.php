<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
include_once ("config.php");
 // загружаем настройки и функции для работы с 1С
function GetData($idc, $txt)
{
    if (is_object($idc)) {
        
        try {
            $par = array(
                'kode' => $txt
            );
            // var_dump($par);
            $ret1c = $idc->GetListTMCGroup($par);
        } catch (SoapFault $e) {
            echo "АЩИБКА!!! </br>";
            var_dump($e);
        }
    } else {
        echo 'Не удалося подключиться к 1С<br>';
        var_dump($idc);
    }
    return $ret1c;
}

$idc = Connect1C("http://10.80.16.34/upp_rss/ws/ws1.1cws?wsdl");
$ret1c = GetData($idc, "00000000064");
// var_dump($ret1c);
$aa = $ret1c->return;
echo $aa;

?>
