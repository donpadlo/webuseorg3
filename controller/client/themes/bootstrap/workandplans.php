<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

function GetArrayPlans(){ // Возврат - массив планов завода
global $smarty,$cfg;
        if (file_exists('import/plans.xml')) {        
                $xml = simplexml_load_file("import/plans.xml");    
                //var_dump($xml);
                               $i=0;
                               $vpkol_i=0;
                               $vpdal_i=0;
                               $plprkol_i=0;
                               $plprdal_i=0;
                               $protklplkol_i=0;
                               $protklpldal_i=0;
                               $otgrkol_i=0;
                               $otgrdal_i=0;                                                              
                               $res = array();
                               foreach($xml->plans as $plans){                                   
                                    $res[$i]['tm']=$plans->tm;
                                    $res[$i]['nom']=$plans->nom;
                                    $res[$i]['vpkol']=str_replace(" ","",$plans->vpkol);
                                    $res[$i]['vpdal']=str_replace(" ","",$plans->vpdal);
                                    if ($res[$i]['nom']!=""){
                                       $vpkol_i=$vpkol_i+$res[$i]['vpkol'];
                                       $vpdal_i=$vpdal_i+$res[$i]['vpdal'];                                    
                                       $res[$i]['vpkol_i']=$vpkol_i;
                                       $res[$i]['vpdal_i']=$vpdal_i;
                                    };
                                    $res[$i]['plprkol']=str_replace(" ","",$plans->plprkol);
                                    $res[$i]['plprdal']=str_replace(" ","",$plans->plprdal);
                                    if ($res[$i]['nom']!=""){
                                       $plprkol_i=$plprkol_i+$res[$i]['plprkol'];
                                       $plprdal_i=$plprdal_i+$res[$i]['plprdal'];
                                       $res[$i]['plprkol_i']=$plprkol_i;
                                       $res[$i]['plprdal_i']=$plprdal_i;                                                                        
                                    };
                                    $res[$i]['protklplkol']=str_replace(" ","",$plans->protklplkol);
                                    $res[$i]['protklpldal']=str_replace(" ","",$plans->protklpldal);
                                    if ($res[$i]['nom']!=""){
                                    $protklplkol_i=$protklplkol_i+$res[$i]['protklplkol'];
                                    $protklpldal_i=$protklpldal_i+$res[$i]['protklpldal'];
                                    $res[$i]['protklplkol_i']=$protklplkol_i;
                                    $res[$i]['protklpldal_i']=$protklpldal_i;
                                    };
                                    //% выполнения расчитываем
                                    //$plprkol_i=100
                                    //$protklplkol_i=$res[$i][prpr_i]
                                    //$rz=$res[$i][plprdal];
                                    //echo "$rz<br>";
                                    //$res[$i][prpr_i]=abs(round($vpkol_i*100/$plprkol_i,2));
                                    
                                    // правил Евгений Шарабура
                                     if ($plprkol_i != 0) { $res[$i]['prpr_i'] = abs(round($vpkol_i*100/$plprkol_i,2)); } else {$res[$i]['prpr_i'] = 0; };

                                    
                                    $res[$i]['prpr']=str_replace(" ","",$plans->prpr);
                                    if ($res[$i]['nom']==""){$res[$i]['prpr']=100-abs(round($res[$i]['protklplkol']*100/$res[$i]['plprkol'],2));};
                                                                                                        
                                    $res[$i]['plotgrkol']=str_replace(" ","",$plans->plotgrkol);
                                    $res[$i]['plotgrdal']=str_replace(" ","",$plans->plotgrdal);                                   
                                    $res[$i]['otgrkol']=str_replace(" ","",$plans->otgrkol);
                                    $res[$i]['otgrdal']=str_replace(" ","",$plans->otgrdal);
                                    if ($res[$i]['nom']!=""){
                                    $otgrkol_i=$otgrkol_i+$res[$i]['otgrkol'];
                                    $otgrdal_i=$otgrdal_i+$res[$i]['otgrdal'];
                                    $res[$i]['otgrkol_i']=$otgrkol_i;
                                    $res[$i]['otgrdal_i']=$otgrdal_i;
                                    };
                                    $res[$i]['otkplotgkol']=str_replace(" ","",$plans->otkplotgkol);
                                    $res[$i]['otkplotgdal']=str_replace(" ","",$plans->otkplotgdal);
                                    $res[$i]['protgr']=str_replace(" ","",$plans->protgr);
                                    
                                    $i++;
                                   // echo "$plans->tm!!!!";
                               };
                               return $res;
                
        } else  {
            echo "Нет файла plans.xml!";};
	};        

$pl=GetArrayPlans();
?>
<div class="container-fluid">
<div class="row-fluid">       
<table class="table table-bordered table-condensed table-hover">   
  <thead>
    <tr>
        <th colspan="1" rowspan="2" style="vertical-align: top;">Номенклатура</th>
        <th colspan="2" rowspan="1" style="vertical-align: top; text-align: center;">Выпуск ГП</th>
        <th colspan="2" rowspan="1" style="vertical-align: top; text-align: center;">План производства</th>
        <th colspan="2" rowspan="1" style="vertical-align: top; text-align: center;">Отклонение от плана</th>
        <th colspan="1" rowspan="2" style="vertical-align: top; text-align: center;">% выполнения</th>
        <th colspan="2" rowspan="1" style="vertical-align: top; text-align: center;">Отгружено</th>
    </tr>
    <tr>
        <th style="vertical-align: top; text-align: right;">Бут</th>
        <th style="vertical-align: top; text-align: right;">Дал</th>
        <th style="vertical-align: top; text-align: right;">Бут</th>
        <th style="vertical-align: top; text-align: right;">Дал</th>
        <th style="vertical-align: top; text-align: right;">Бут</th>
        <th style="vertical-align: top; text-align: right;">Дал</th>
        <th style="vertical-align: top; text-align: right;">Бут</th>
        <th style="vertical-align: top; text-align: right;">Дал</th>
    </tr>
  </thead>            
  <tbody>       
<?php
        $vpkol_i=0;
        $vpdal_i=0;
        $plprkol_i=0;
        $plprdal_i=0;
        $protklplkol_i=0;
        $protklpldal_i=0;
        $prpr_i=0;
        $otgrkol_i=0;
        $otgrdal_i=0;

 for ($i=0;$i<count($pl);$i++){
     $nm=$pl[$i]['nom'];
     $tm=$pl[$i]['tm'];    
     $vpkol=$pl[$i]['vpkol'];    
     $vpdal=$pl[$i]['vpdal'];    
     $plprkol=$pl[$i]['plprkol'];    
     $plprdal=$pl[$i]['plprdal'];    
     $protklplkol=$pl[$i]['protklplkol'];    
     $protklpldal=$pl[$i]['protklpldal'];    
     $prpr=$pl[$i]['prpr'];    
     if ($plprkol==0) {$prpr=0;} else {$prpr=round($vpkol*100/$plprkol,2);};
     $otgrkol=$pl[$i]['otgrkol'];    
     $otgrdal=$pl[$i]['otgrdal'];      
     if ($nm!=""){
        $vpkol_i=$vpkol_i+$vpkol;
        $vpdal_i=$vpdal_i+$vpdal;
        $plprkol_i=$plprkol_i+$plprkol;
        $plprdal_i=$plprdal_i+$plprdal;
        $protklplkol_i=$protklplkol_i+$protklplkol;
        $protklpldal_i=$protklpldal_i+$protklpldal;        
        $prpr_i=$prpr_i+$prpr;
        if ($plprkol_i==0) {$prpr_i=0;} else {$prpr_i=round($vpkol_i*100/$plprkol_i,2);};
        $otgrkol_i=$otgrkol_i+$otgrkol;
        $otgrdal_i=$otgrdal_i+$otgrdal;
     };
?>    
    <tr <?php 
    if ($pl[$i]['prpr']>=100) {echo 'class="success"';}; 
    if ($pl[$i]['nom']==""){echo 'class="error"';};?>>    
        <td style="vertical-align: top;"><?php echo "$nm";if ($nm=="") {echo "<strong>$tm</strong>";};?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$vpkol"; ?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$vpdal"; ?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$plprkol"; ?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$plprdal"; ?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$protklplkol"; ?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$protklpldal"; ?></td>
        <td style="vertical-align: top; text-align: center;"><strong><?php echo "$prpr"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$otgrkol"; ?></td>
        <td style="vertical-align: top; text-align: right;"><?php echo "$otgrdal"; ?></td>
    </tr>            
<?php      
 };
?>
    <tr class="info">
        <td style="vertical-align: top;"><strong>ИТОГО:</strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$vpkol_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$vpdal_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$plprkol_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$plprdal_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$protklplkol_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$protklpldal_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: center;"><strong><?php echo "$prpr_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$otgrkol_i"; ?></strong></td>
        <td style="vertical-align: top; text-align: right;"><strong><?php echo "$otgrdal_i"; ?></strong></td>
    </tr>                    
<?php      
 
?>
  </tbody>  
</table>      
</div>
</div>