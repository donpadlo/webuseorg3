<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

header('Content-type: application/vnd.ms-excel');
if (isset($_GET["csv"])==true){
    header("Content-Disposition: attachment; filename=file.csv");
} else {
    header("Content-Disposition: attachment; filename=file.xls");
};
header("Pragma: no-cache");

$buffer = $_POST['csvBuffer'];

try{
    echo $buffer;
}catch(Exception $e){

}
?>