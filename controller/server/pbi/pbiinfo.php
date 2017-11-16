<?php
include_once ("class/telnet.php");

$id = _GET('id');
$page = _GET('page');

if ($page == "") {
    $page = "1";
}
;

// получаем информацию о станции
$sql = "select * from pbi where id=$id";
// echo "$sql<br/>";
$result = $sqlcn->ExecuteSQL($sql) or die("Не могу выбрать список pbi!" . mysqli_error($sqlcn->idsqlconnection));
while ($row = mysqli_fetch_array($result)) {
    $ip = $row["ip"];
    $login = $row["login"];
    $pass = $row["pass"];
}
;
echo "<hr>";
echo "<button class=\"btn btn-info\" onclick='reloadme(1,$id)'>Status</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(2,$id)'>Version</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(3,$id)'>System</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(4,$id)'>Input</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(5,$id)'>Output</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(6,$id)'>Network</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(7,$id)'>DVB</button>";
echo "<button class=\"btn btn-info\" onclick='reloadme(8,$id)'>IPTV</button>";
echo "<button class=\"btn btn-danger\" onclick='reloadme(9,$id)'>Перезагрузить</button>";
echo "<hr>";

$tt = new Telnet($ip, 23, 10, ":");
$zxc = $tt->exec($login);
$tt->setPrompt("/>");
$zxc = $tt->exec($pass);
$tt->setPrompt("</HTML>");

if ($page == "1") {
    $zxc = $tt->exec("./home/httpd/cgi-bin/status.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "2") {
    $tt->setPrompt("</body>");
    $zxc = $tt->exec("./home/httpd/cgi-bin/version.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "3") {
    $zxc = $tt->exec("./home/httpd/cgi-bin/system.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "4") {
    $zxc = $tt->exec("./home/httpd/cgi-bin/input.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "5") {
    $zxc = $tt->exec("./home/httpd/cgi-bin/output.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "6") {
    $zxc = $tt->exec("./home/httpd/cgi-bin/network.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "7") {
    $tt->setPrompt("</body>");
    $zxc = $tt->exec("./home/httpd/cgi-bin/ip_dvb.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "8") {
    $zxc = $tt->exec("./home/httpd/cgi-bin/ip_iptv.cgi");
    $zxc = str_replace("Content-type: text/html", "", $zxc);
    $zxc = str_replace('self.setInterval("reload()", 20000)', "", $zxc);
    $zxc = str_replace('<th width="180px" valign="top" class="menu" scope="col">', '<th width="180px" valign="top" class="menu" scope="col" style="display:none">', $zxc);
    echo ($zxc);
}
;
if ($page == "9") {
    $zxc = $tt->exec("reboot");
    echo "-- устройство отправлено в перезагрузку. Подождите несколько минут и снова выберите устройство.";
}
;

$tt->disconnect();
?>
<script>
    function reloadme(tp,id){
	if (tp==9) {
	    if (confirm('Вы уверенны, что хотите сделать то что хотите?')){	    
		$("#pbiinfo").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
		$("#pbiinfo").load(route+'controller/server/pbi/pbiinfo.php&id='+id+"&page="+tp);			
	    };
	} else {
		$("#pbiinfo").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
		$("#pbiinfo").load(route+'controller/server/pbi/pbiinfo.php&id='+id+"&page="+tp);	
	};
    };
</script>
