<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф
if (($user->mode==1) or ($user->mode==0)){
?>

    <label>Кому поставлена задача:</label></br>
    <div id=susers1>
        <?php
           $SQL = "SELECT users.id, users.login, users_profile.fio FROM users INNER JOIN users_profile ON users.id = users_profile.usersid WHERE users.orgid='$cfg->defaultorgid' AND users.active=1 ORDER BY users.login";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));
            $sts="<select class='chosen-select'name=suserid1 id=suserid1>";
                while($row = mysqli_fetch_array($result)) {
                 $sts=$sts."<option value=".$row["id"]." ";                
                 $sts=$sts.">".$row["fio"].'</option>';
                };
            $sts=$sts.'</select>';
            echo "$sts";
    ?>
    </div></br>      
    <label>Его руководитель:</label></br>
    <div id=susers2>
        <?php
           $SQL = "SELECT users.id, users.login, users_profile.fio FROM users INNER JOIN users_profile ON users.id = users_profile.usersid WHERE users.orgid='$cfg->defaultorgid' AND users.active=1 ORDER BY users.login";
            $result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список пользователей!".mysqli_error($sqlcn->idsqlconnection));
            $sts="<select class='chosen-select' name=suserid2 id=suserid2>";
                while($row = mysqli_fetch_array($result)) {
                 $sts=$sts."<option value=".$row["id"]." ";                
                 $sts=$sts.">".$row["fio"].'</option>';
                };
            $sts=$sts.'</select>';
            echo "$sts";
    ?>
    </div></br>      
    <label>Срок до:</label></br>
    <input name=dtpost id=dtpost value="<?php echo "$dtpost"; ?>" size=14></br>       
    <label>Пояснение:</label></br>
    <textarea class="span12" name=comment rows="8">    
    </textarea> </br></br>
    <div align=center><input type="submit" class="btn btn-primary" name="Submit" value="Создать задачу"></div>       
<script>    
 $("#dtpost").datepicker();
    $("#dtpost").datepicker( "option", "dateFormat", "dd.mm.yy");    
    
     for (var selector in config) {
      $(selector).chosen(config[selector]);
    };     
</script>        
<?php
};
?>