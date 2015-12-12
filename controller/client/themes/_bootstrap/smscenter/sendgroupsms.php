<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф


if ($user->mode==1){
?>
<div class="well">
    <legend>Отправление СМС группе</legend>    
    <div class="row-fluid">
        <div class="span4">
            <label>Введите текст СМС</label>
            <textarea name="txtsms" id="txtsms" class="span12" id="redex" maxlength="300" rows="4"></textarea>
            <style type="text/css">
                #redex { resize: none; }
            </style>     
            </br>
            <button type="submit" onclick="SendSMS();" class="btn">Отправить СМС</button>                        
            <div name="ressms" id="ressms">
            </div>    
        </div>
        <div class="span8">
            <label>Выберите группы получения</label>        
<?php
    include_once("class/groups.class.php");
    $groups = new PhoneGroups($mysql_host,$mysql_user,$mysql_pass,$mysql_base);
    $gr=$groups->getGroups();
    echo "<div class='span12'>";
    echo "<div class='span6'>";
    for ($i = 0; $i < count($gr); $i++) {
        if($i%2){
            $name=$gr[$i]["name"];
            $id=$gr[$i]["id"];
            echo "<input type=checkbox name=option$i id=option$i value=$id>$name</br>";
        };
    }
    echo "</div>";
    echo "<div class='span6'>";
    for ($i = 0; $i < count($gr); $i++) {
        if($i%2){} else {
            $name=$gr[$i]["name"];
            $id=$gr[$i]["id"];
            echo "<input type=checkbox name=option$i id=option$i value=$id>$name</br>";
        };
    }
    echo "</div>";
    echo "</div>";    

?>
        </div>    
    
    </div>
</div>    
<script>
 <?php $cntgr=count($gr);echo "cntgr=$cntgr;";?>
 function SendSMS(){
     for (var i = 0; i < cntgr; i++) {
       //if($("#option"+i).prop("checked")=true){ alert(i);};
     };   
     ids="";
     for (var i = 0; i < cntgr; i++) {
            ch=$("#option"+i).prop("checked");
            vl=$("#option"+i).val();
            if (ch==true) {ids=ids+vl+";"};            
     };
     txtsms=vl=$("#txtsms").val();
     if (txtsms!=""){
     $.post("controller/server/smscenter/sendsmsgroup.php",{ids:ids, txtsms: txtsms,billingid:$("#blibase").val()}, function(data){
        $('#ressms').html(data);
      });
    };
 }
</script>
<?php
} else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}
