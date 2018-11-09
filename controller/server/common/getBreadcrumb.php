<?php
include_once(WUO_ROOT.'/class/mod.php'); // Класс работы с модулями
include_once(WUO_ROOT.'/class/menu.php'); // Класс работы с меню
$gmenu = new Tmenu();
$gmenu->GetFromFiles(WUO_ROOT.'/inc/menu');

$url=  _GET("url");

//ищем url
$uid="";
foreach ($gmenu->arr_menu as $key=> $mlist) {    
    if ($mlist["path"]==$url) $uid=$mlist["uid"];    
};

$chain=array();
function GetParentMenu($uid){ 
 global $gmenu,$chain;
    $chain[]=$uid;
    foreach ($gmenu->arr_menu as $key=> $mlist) {    
	if (($mlist["uid"]==$uid) and $mlist["parents"]!="main"){GetParentMenu($mlist["parents"]);};    
    };
};
GetParentMenu($uid);

if ($uid!=""){
   echo "<li><a href=\"index.php\">Главная</a></li>"; 
   for ($index = count($chain); $index > 0; $index--) {      
      $uid=$chain[$index-1];
	foreach ($gmenu->arr_menu as $key=> $mlist) {    
	    if ($mlist["uid"]==$uid) {
		   echo "<li>".$mlist["name"]."</li>"; 		
		   if ($index==1){
		      $title=$mlist["comment"];		      
		      $ico=$mlist["ico"];		      
		      $path=$cfg->urlsite."/index.php?content_page=".$mlist["path"];		      
		     echo '<button onclick="AddToNavBarQuick(\''.$title.'\',\''.$path.'\');" title="Прибить страницу в быстрых ссылках" type="button" class="btn btn-default navbar-btn "><i class="fa fa-link"></i></button> ';
		   };
	    };    
	};
      
   };   
   echo "<script>current_page_ico='$ico';</script>";
};
?>