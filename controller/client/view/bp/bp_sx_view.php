<?php
// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

include_once ("../../../../config.php");                    // загружаем первоначальные настройки

// загружаем классы

include_once("../../../../class/sql.php");               // загружаем классы работы с БД
include_once("../../../../class/config.php");		// загружаем классы настроек
include_once("../../../../class/users.php");		// загружаем классы работы с пользователями
include_once("../../../../class/employees.php");		// загружаем классы работы с профилем пользователя
include_once("../../../../class/bp.php");		// загружаем классы работы c "Бизнес процессами"


// загружаем все что нужно для работы движка

include_once("../../../../inc/connect.php");		// соеденяемся с БД, получаем $mysql_base_id
include_once("../../../../inc/config.php");              // подгружаем настройки из БД, получаем заполненый класс $cfg
include_once("../../../../inc/functions.php");		// загружаем функции
include_once("../../../../inc/login.php");		// логинимся

$bpid=GetDef("bpid");
function plumb($t1,$t2,$a1,$a2,$title,$color){
echo 'jsPlumb.connect({
    source:"'.$t1.'",
    target:"'.$t2.'",
    connector:["Flowchart", { curviness:70 }],
			   	anchors:["'.$a1.'", "'.$a2.'"], 
			   	paintStyle:{ 
					lineWidth:3,
					strokeStyle:"'.$color.'",
					outlineWidth:1,
					outlineColor:"#666"
				},
				endpointStyle:{ fillStyle:"#a7b04b" },
	   	overlays : [
					["Label", {													   					
						cssClass:"l1 component label",
						label : "'.$title.'", 
						location:0.1,
						id:"label"						
					}],
					["Arrow", {
						cssClass:"l1arrow",
						location:0.5, width:20,length:20
						
					}]
				]                                
			   				   
    
});';    
};
echo '<div class="demo kitchensink-demo" id="kitchensink-demo">';
// читаем файл с БП и рисуем Ноды
$bp=new Tbp;
$bp->GetById($bpid);
$xml = simplexml_load_file("../../../../modules/bp/$bp->xml");
//var_dump($xml);   
               foreach($xml->step as $step){                   
                 $post=GetPostOrgByid($step->user);                
                 $color='';
                 if (($bp->node==$step->node) and ($bp->status!=2)){$color='background-color: chartreuse;';};
                 if (($step->node=="-1") and ($bp->status==2)){$color='background-color: chartreuse;';};
                 echo '<div style="'.$color.'height:'.$step->heigth.';width:'.$step->width.';top:'.$step->top.';left:'.$step->left.';" class="component window" id="window'.$step->node.'"><strong>'.$step->title.'('.$step->node.')</strong><br>'.$post.'<br><strong>'.$step->comment.'</strong></div>
                         ';
               };               
echo '</div>';
?>

<script>
jQuery(function($) {
jsPlumb.ready(function() {    
jsPlumb.deleteEveryEndpoint();    
<?php
 foreach($xml->step as $step){                   
     if ($step->accept!="") {plumb("window".$step->node,"window".$step->accept,"BottomCenter", "TopCenter","Утвердить",'#a7b04b');};
     if ($step->cancel!="") {plumb("window".$step->node,"window".$step->cancel,"BottomLeft", "TopCenter","Отменить",'#f11515');};     
     if ($step->yes!="") {plumb("window".$step->node,"window".$step->yes,"BottomCenter", "TopCenter","Да",'#a7b04b');};     
     if ($step->no!="") {plumb("window".$step->node,"window".$step->no,"Left", "TopCenter","Нет",'#f11515');};     
     if ($step->thinking!="") {plumb("window".$step->node,"window".$step->thinking,"Left", "TopCenter","Доработать",'#c9b615');};          
     if ($step->one!="") {plumb("window".$step->node,"window".$step->one,"TopLeft", "TopCenter","1",'#2c2cde');};               
     if ($step->two!="") {plumb("window".$step->node,"window".$step->two,"TopRight", "TopCenter","2",'#2c2cde');};                    
     if ($step->three!="") {plumb("window".$step->node,"window".$step->three,"BottomLeft", "TopCenter","3",'#2c2cde');};                    
     if ($step->four!="") {plumb("window".$step->node,"window".$step->four,"BottomRight", "TopCenter","4",'#2c2cde');};                         
 };                 
?>
jsPlumb.draggable(jsPlumb.getSelector(".window"), { containment:".demo"});
jsPlumb.repaintEverything();
});
});    
</script>
