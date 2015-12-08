<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>

		<script type="text/javascript">
			$(function() {
				$('#menu').mmenu({
					extensions	: [ "effect-zoom-menu", "effect-zoom-panels", 'pageshadow',"iconbar" ],
					header		: true,
					searchfield	: true,
					counters	: true,
                                        dragOpen        : true,
                                        navbar          : {title:"Меню",panelTitle:"Меню"},
                                        onClick : {
                                          setSelected : true,
                                          close : false                                          
                                        },
				});
			});

		</script>            
			<nav id="menu">
	
                        <!--<li><a href="index.php"><img src="controller/client/themes/<?php echo "$cfg->theme"; ?>/ico/house.png"> Главная</a></li> -->
<?php
/*$mfiles=GetArrayFilesInDir("modules/menu");
//var_dump($mfiles);
foreach ($mfiles as &$fname) {
   //if (strripos($fname,".php")!=FALSE)
   if (is_file("modules/menu/$fname")==TRUE)            
   {
    include_once("modules/menu/$fname");    
    }
}
unset($fname);
 * 
 */

function PutMenu($par){
 global $gmenu,$cfg,$content_page;
    echo "<ul>";
    $list=$gmenu->GetList($par);    
    foreach ($list as $key => $pmenu) {
      $nm=$pmenu["name"];  
      $path=$pmenu["path"];
      $uid=$pmenu["uid"];
        if ($path==""){$url='href="#"';} else {$url="href=index.php?content_page=$path";};
        if ($content_page==$path){$sel="class='Selected'";} else {$sel="";};            
          echo "<li $sel>";
          echo "<a $url> $nm</a>";
           if (count($gmenu->GetList($uid))>0){
               PutMenu($uid);               
           }
          echo "</li>";  
    };
 echo "</ul>";
};

PutMenu("main");
//var_dump($list);
unset($mm);
?>
                                    
	
			</nav>
           
                
                
