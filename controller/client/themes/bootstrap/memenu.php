<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
?>

            Организация:
<select class="form-control" name="orgs" id="orgs">
                <?php
                for ($i = 0; $i < count($morgs); $i ++) {
                    $idorg = $morgs[$i]["id"];
                    $nameorg = $morgs[$i]["name"];
                    ?>
                <option
		<?php if ($idorg==$cfg->defaultorgid){echo "selected";}; ?>
		value=<?php echo "$idorg";?>><?php echo "$nameorg"; ?></option>
                <?php };?>
            </select>
Размер шрифта:
<select class="form-control" name="fontsize" id="fontsize">
	<option <?php if ($cfg->fontsize=="11px"){echo "selected";}; ?>
		value="<?php echo "11px"; ?>"><?php echo "11px"; ?></option>
	<option <?php if ($cfg->fontsize=="12px"){echo "selected";}; ?>
		value="<?php echo "12px"; ?>"><?php echo "12px"; ?></option>
	<option <?php if ($cfg->fontsize=="13px"){echo "selected";}; ?>
		value="<?php echo "13px"; ?>"><?php echo "13px"; ?></option>
	<option <?php if ($cfg->fontsize=="14px"){echo "selected";}; ?>
		value="<?php echo "14px"; ?>"><?php echo "14px"; ?></option>
</select>

<script type="text/javascript" src="controller/client/js/memenu.js"></script>