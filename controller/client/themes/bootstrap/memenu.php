<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>

            Организация:
            <select class="form-control" name="orgs" id="orgs">
                <?php 
                   for ($i = 0; $i < count($morgs); $i++) { 
                       $idorg=$morgs[$i]["id"];
                       $nameorg=$morgs[$i]["name"];
                 ?>
                <option <?php if ($idorg==$cfg->defaultorgid){echo "selected";}; ?> value=<?php echo "$idorg";?>><?php echo "$nameorg"; ?></option>
                <?php };?>
            </select>             
            Стиль:
            <select class="form-control" name="stl" id="stl">
                <option <?php if ($cfg->style=="Bootstrap"){echo "selected";}; ?> value="<?php echo "Bootstrap"; ?>"><?php echo "Bootstrap"; ?></option>
                <option <?php if ($cfg->style=="Normal"){echo "selected";}; ?> value="<?php echo "Normal"; ?>"><?php echo "Normal"; ?></option>
            </select>             
            
<script type="text/javascript" src="controller/client/js/memenu.js"></script>