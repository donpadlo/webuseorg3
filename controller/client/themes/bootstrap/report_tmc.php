<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

?>
<div class="well">
        <form ENCTYPE="multipart/form-data" action="?content_page=reports&step=view" method="post" name="form1" target="_self">
<div class="row-fluid">
  <div class="span4">
      <label>Название отчета</label>
        <select name="sel_rep" id="sel_rep">
            <option value=1>Наличие ТМЦ</option>
            <option value=2>Наличие ТМЦ - только не ОС и не списанное</option>
        </select>
        <label>Человек</label>
        <div name="sel_plp" id="sel_plp"></div>      
  </div>
  <div class="span4">
        <label>Организация</label>
        <select name="sel_orgid" id="sel_orgid">
            <?php
                $morgs=GetArrayOrgs();
                for ($i = 0; $i < count($morgs); $i++) {           
                    $nid=$morgs[$i]["id"];
                    $nm=$morgs[$i]["name"];
                    if ($nid==$user->orgid){$sl=" selected";} else {$sl="";};
                    echo "<option value=$nid $sl>$nm</option>";
                };
            ?>
        </select>      
          <label class="checkbox">
            <input type="checkbox" name="os" id="os" value="1"> Основные
          </label>
          <label class="checkbox">
            <input type="checkbox" name="mode" id="mode" value="1"> Списано
          </label>
          <label class="checkbox">
            <input type="checkbox" name="gr" id="gr" value="1"> По группам
          </label>        
  </div>
  <div class="span4">
    <label>Помещение</label>
    <div name="sel_pom" id="sel_pom"></div>      
          <label class="checkbox">
            <input type="checkbox" name="repair" id="repair" value="1"> В ремонте
          </label>    
  </div>    
</div>    
<p><input type="button" name=sbt id=sbt value="Сформировать">
<input type="button" id=btprint value="Распечатать"></p>
</form>
<table id="list2"></table>
<div id="pager2"></div>
</div>
<?php echo "<script>curuserid=$user->id;</script>"?>
<script type="text/javascript" src="controller/client/js/report.js"></script>