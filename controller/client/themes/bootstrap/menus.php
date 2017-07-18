<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф
?>
<script>
	$(function() {
		$('#menu').mmenu({
			extensions: ['effect-zoom-menu', 'effect-zoom-panels', 'pageshadow', 'iconbar'],
			header: true,
			searchfield: false,
			counters: true,
			dragOpen: true,
			navbar: {title: 'Меню', panelTitle: 'Меню'},
			onClick: {
				setSelected: true,
				close: false
			}
		});
		mmenuapi = $("#menu").data( "mmenu" );
	});
	 
</script>            
<nav id="menu">	                        
<?php
$current_page_ico="";
$sel_url=array();
function PutMenu($par) {
	global $gmenu, $cfg, $content_page,$current_page_ico,$sel_url;
	echo '<ul>';
	$list = $gmenu->GetList($par);
	foreach ($list as $key => $pmenu) {
		$nm = $pmenu['name'];
		$path = $pmenu['path'];
		$uid = $pmenu['uid'];
		$comment = $pmenu['comment'];
		$ico=$pmenu['ico'];
		$ajax=$pmenu['ajax'];
		//$url = ($path == '') ? 'javascript:void(0)' : "index.php?content_page=$path";		
		if ($path==""){
		    $url='javascript:void(0)';		    
		} else {
		    $url="index.php?content_page=$path";
		};
		if ($content_page == $path){
		    $current_page_ico=$pmenu['ico'];		    
		    $sel=' class="Selected"';
		} else {
		    $sel='';
		};
		echo "<li$sel>";
		if ($path=="") {
		    echo "<span title='$comment'>$ico $nm</span>";		    
		} else {
		    if ($ajax==false){
		     echo "<a title='$comment' href=\"$url\">$ico $nm</a>";		    
		    } else {
		     echo "<a title='$comment' href=\"javascript:void(0)\" onclick='GetAjaxPage(\"$path\")'>$ico $nm</a>";		    
		    };
		    if ($sel!=''){$sel_url["path"]=$path;$sel_url["uid"]=$uid;$sel_url["parents"]=$pmenu['parents'];$sel_url["name"]=$nm;};
		};
		if (count($gmenu->GetList($uid)) > 0) {
			PutMenu($uid);
		}
		echo '</li>';
	}
	echo '</ul>';
}
PutMenu('main');
unset($mm);
?>
</nav>
<script>current_page_ico=<?php echo "\"$current_page_ico\";"?></script>
<?php
function GetParentMenu($cur_parents){
 global $gmenu;
    $ex=array();
    foreach ($gmenu->arr_menu as $value) {
    if (isset($value["uid"])==true){	
	if (($value["path"]=="") and ($value["uid"]=="$cur_parents")){	  
	    $ex["parents"]=$value["parents"];
	    $ex["name"]=$value["name"];
	    $ex["comment"]=$value["comment"];	    
	};
    };
  };  
  return $ex;
};
    //парсим "хлебные крошки"
    if ($sel_url["name"]!="Главная"){
	$cfg->navbar=array();
	$cfg->navbar[]=$sel_url["name"];    
	$cur_parents=$sel_url["parents"];
	$flag=0;
	while ($flag==0){
	    $tmp=GetParentMenu($cur_parents);
	    if (isset($tmp["parents"])==true){	    
	      $cfg->navbar[]=$tmp["name"];
	      $cur_parents=$tmp["parents"];
	    } else {
		$flag=1;
	    };
	};
	$cfg->navbar[]="<a href=index.php>Главная</a>";    
    };
    //
?>