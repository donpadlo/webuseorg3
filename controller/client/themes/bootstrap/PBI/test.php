<?php

include_once ("class/telnet.php");
echo "<br/>--connect<br/>";
$tt=new Telnet("172.28.100.2",23,10,":");
echo "<br/>--login<br/>";
$zxc=$tt->exec("root");
$tt->setPrompt("/>");
$zxc=$tt->exec("12345");
//$tt->login("root", "12345");
#echo "<br/>--exec<br/>";
$tt->setPrompt("</HTML>");
$zxc=$tt->exec("./home/httpd/cgi-bin/network.cgi");

echo($zxc);

$tt->disconnect();

?>