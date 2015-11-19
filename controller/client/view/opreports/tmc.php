<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
 //ini_set('mssql.charset', 'CP1251');
$link = mssql_connect('mmm', 'gribov.p', 'pavel1979');
//echo mssql_get_last_message();  
mssql_select_db('upp_rss', $link);
        
$result = mssql_query("SELECT 
	[upp_rss].[dbo].[_Reference248].[_Description] as skladname
,[_Reference186].[_Description] as nomename              	
      ,[upp_rss].[dbo].[_Reference246].[_Description] as seria  
      ,[_Reference186].[_Fld2914] as emk            
      ,[_Fld24642] as kol           
      FROM [upp_rss].[dbo].[_AccumRgT24645]  
      INNER JOIN [upp_rss].[dbo].[_Reference186]
      ON [upp_rss].[dbo].[_AccumRgT24645].[_Fld24638RRef]=[upp_rss].[dbo].[_Reference186].[_IDRRef]       
      INNER JOIN [upp_rss].[dbo].[_Reference84] 
      ON [upp_rss].[dbo].[_Reference186].[_Fld2887RRef]=[upp_rss].[dbo].[_Reference84].[_IDRRef]
      INNER JOIN [upp_rss].[dbo].[_Reference246]
      ON [upp_rss].[dbo].[_AccumRgT24645].[_Fld24641RRef]=[upp_rss].[dbo].[_Reference246].[_IDRRef]
      INNER JOIN [upp_rss].[dbo].[_Reference248]
      ON [upp_rss].[dbo].[_AccumRgT24645].[_Fld24637RRef]=[upp_rss].[dbo].[_Reference248].[_IDRRef]
      
      WHERE [_Reference84].[_Description]='Продукция' and
      [_AccumRgT24645].[_Period]='5999-11-01 00:00:00.000' and [_AccumRgT24645].[_Fld24642]<>0
  order by skladname,nomename desc");
//echo mssql_get_last_message();  
?>
<table class="table table-condensed table-hover table-striped">  
  <thead>
    <tr>
      <th>Номенклатура</th>
      <th>Бутылок</th>
      <th>Декалитров</th>      
    </tr>
  </thead>
  <tbody>
<?php
$old='';$oldskl='';
$it=0;$it2=0;$cnt=0;$itser=0;
$dekit=0;$deksk=0;$itserdek=0;
while($row = mssql_fetch_array($result)) {
    $nm1=$row['_Period'];$nm2=$row['nomename']."<br>";$nm3=$row['seria'];$nm4=round($row['kol'],0);
    
    $sk='<strong>'.$row['skladname'].'</br></strong>';
    $dek=round($row['emk']*$nm4/100,2);
    $dekit=$dekit+$dek;
    
    
    $it=$it+$nm4;
    
    
    
    
    if ($nm2==$old) {$nm2='';}
    if ($sk==$oldskl) {$sk='';}
    
    $old=$row['nomename']."<br>";
    $oldskl='<strong>'.$row['skladname'].'</br></strong>';
    // Сумма по серии 
     if (($nm2!='') and ($itser!=0) and ($cnt!=0)){echo "<tr><td><strong>всего по номенклатуре:</strong></td><td><strong>$itser</strong></td><td><strong>$itserdek</strong></td></tr>";$itser=0;$itserdek=0;};                
     

    if (($sk!='') and ($it2!=0) and ($cnt!=0)){echo "<tr><td><strong>Всего по складу:</strong></td><td><strong>$it2</strong></td><td><strong>$deksk</strong></td></tr>";$it2=0;$deksk=0;};        
$deksk=$deksk+$dek;
$it2=$it2+$nm4;
    $itser=$itser+$nm4;     
     $itserdek=$itserdek+$dek;
    
    // Склад
     if ($sk!="") {echo "<tr><td>$sk</td><td></td><td></td></tr>";};
    
    // Номенклатура 
     if ($nm2!="") {echo "<tr><td>$nm2</td><td></td><td></td></tr>";};
    
    echo "<tr><td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$nm3</td><td>$nm4</td><td>$dek</td></tr>";            
    $cnt++;
};  
echo "<tr><td><strong>всего по номенклатуре:</strong></td><td><strong>$itser</strong></td><td><strong>$itserdek</strong></td></tr>";
echo "<tr><td><strong>Всего по складу:</strong></td></td><td><strong>$it2</strong></td><td><strong>$deksk</strong></td></tr>";
echo "<tr><td><strong>ИТОГО:</strong></td><td><strong>$it</strong></td><td>$dekit</td></tr>";        
mssql_close($link);
?>
</tbody></table>
