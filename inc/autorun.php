<?php

// запускаем поочередно все скрипты из папки autorun
$mfiles=GetArrayFilesInDir("autorun");
foreach ($mfiles as &$fname) {
    include_once("autorun/$fname");
}
unset($fname);

?>