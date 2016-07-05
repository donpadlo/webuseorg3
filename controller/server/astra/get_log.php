<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$url=$_GET["url"];
echo "$url";
$fh = fopen($url, 'r');

$code = '';
while(!feof($fh)) $code .= fread($fh, 1024);
fclose($fh);
$code=  str_replace("</br>", "\n", $code);
echo "<textarea name='ttx' id='ttx' class='form-control' rows=20>";
echo "$code";
echo "</textarea>";
?>

<script>
jQuery.fn.putCursorAtEnd = function() {
  return this.each(function() {
    $(this).focus()
    if (this.setSelectionRange) {
      var len = $(this).val().length * 2;
      this.setSelectionRange(len, len);   
    } else {
      $(this).val($(this).val());      
    }
    this.scrollTop = 999999;
  });

};
    $("#ttx").putCursorAtEnd(); 
 </script>       