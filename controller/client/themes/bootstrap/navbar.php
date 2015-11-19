<?php
if (count($cfg->navbar)>0){   
?>
<ul class="breadcrumb">
<?php
 for ($i=0;$i<count($cfg->navbar);$i++){
   $ntxt=$cfg->navbar[$i];  
   echo "<li>$ntxt <span class='divider'>/</span></li>";  
 };
?>    
</ul>
<?php
};
?>