<?php

/* 
 * (с) 2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */

include_once ("inc/lbfunc.php");                    // загружаем функции LB
if ($user->mode==1){
?>
<div class="row-fluid">
    <div class="span4">
        <script> 
            function getRandomInt(min, max){
                return Math.floor(Math.random() * (max - min + 1)) + min;
            };
            function openurl(url){
               //clearInterval(timer);                
              $("#fr" ).html('<iframe src="" class="span12" height="768" align="left" name="frame1" id="frame1"></iframe>');
              parent.frame1.location.href= url;
//              alert(url);  
            };
            function openGeturl(url){
              // clearInterval(timer);                
              //parent.frame1.location.href= url;              
              $("#fr" ).load("controller/server/astra/get_log.php?url="+url);                                                                             
            };

            function openMonurl(aid){
              //parent.frame1.location.href= url;  
              $("#fr").html("<img src=controller/client/themes/"+theme+"/img/loading.gif><br>*выполнение запроса может занять некоторое время..");        
              //$("#fr" ).load("controller/server/astra/monitoring.php?astra_id="+aid+"&rand="+getRandomInt(10,10000));                                                                             
                   $.get("controller/server/astra/monitoring.php?astra_id="+aid+"&rand="+getRandomInt(10,10000),function(data){
                     $("#fr" ).html(data);
                   });
            };


            function showcurrent(asid){
                //clearInterval(timer);                
              //  alert(asid);
                $("#listmon" ).load("controller/server/astra/list_mon.php?astra_id="+asid);                                                                             
            };
        </script>
      <?php
    		$result = $sqlcn->ExecuteSQL("SELECT * FROM astra_servers order by id  asc");
  		if ($result!='') {
                    echo '<select name="astrabase" id="astrabase">';
                    while ($myrow = mysqli_fetch_array($result)){
                      $name=$myrow["name"];                
                      $id=$myrow["id"];                
                      echo "<option value=$id>$name</option>";                      
                    };
                    echo '</select></br>';
                };                                           
      ?>
        <script>    
            $("#astrabase").change(function() {// обрабатываем выбор базы
                //clearInterval(timer);                 
                showcurrent($("#astrabase").val());
                openMonurl($("#astrabase").val());
            });
            $( document ).ready(function() {
                //clearInterval(timer);                 
                showcurrent($("#astrabase").val());
                openMonurl($("#astrabase").val());
            });
            
        </script>    
        <div id="listmon" name="listmon"></div>
    </div>
    <div class="span8">
      <div id="fr" name="fr"></div>     
 </div>
</div>

<?php
}
 else {
?>
<div class="alert alert-error">
  У вас нет доступа в данный раздел!
</div>
<?php
    
}

?>