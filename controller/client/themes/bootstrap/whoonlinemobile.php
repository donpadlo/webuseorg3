<?php
$SQL = "SELECT users.id as uid,unix_timestamp(now())-unix_timestamp(lastactivemob) as res,users_profile.fio as fio,users_profile.jpegphoto FROM users inner join users_profile on users_profile.usersid=users.id where unix_timestamp(now())-unix_timestamp(lastactivemob)<120";
$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список заходов пользователей!".mysqli_error($sqlcn->idsqlconnection));
$cntmob=0;$mob="";
while($row = mysqli_fetch_array($result)) {                                
        $fio=$row["fio"];                                
	$uid=$row["uid"];      
	$cntmob++;
	$mob=$mob."<button onclick=\"SendToMobile($uid);\" type=\"button\" class=\"btn btn-info\">$fio</button> ";		
	//если админ, то рисуем кнопку "посмотреть на карте"
	if ($user->mode==1){
	    $rnd=GetRandomId(5);
	    $mob=$mob."<button onclick=\"TackToMap($uid,$rnd);\" type=\"button\" class=\"btn btn-info\"><i class=\"fa fa-map-marker\" aria-hidden=\"true\"></i></button> ";			    
	};
};                              
if ($cntmob!=0){
  echo "$mob";  
};
?>
<!-- СМС Абоненту -->            
<div id="dialogmobilesend" title="Отослать сообщение пользователю">
    <input class="col-xs-12 col-md-12 col-sm-12" name="titlemobile" type="text" id="titlemobile" placeholder="Заголовок">                                      
    <textarea rows="3" class="col-xs-12 col-md-12 col-sm-12" name="mobiletext" id="mobiletext" placeholder="Текст сообщения"></textarea>
</div>
<script>
function SendToMobile(userid){
 guseridsender=userid;   
 $( "#dialogmobilesend" ).dialog("open" );                             
};
function TackToMap(userid,rnd){
  newWin = window.open('index.php?content_page=trackmap&printable=true&userid='+ userid+"&rnd="+rnd, 'MapWindows');
};
$(function() {     
    $("#dialogmobilesend" ).dialog({
      autoOpen: false,        
      resizable: false,
      height:300,
      width: 400,
      modal: true,
      buttons: {
        "Отправить сообщение": function() {
          $( this ).dialog( "close" );
            titlemobile=$("#titlemobile").val();
            mobiletext=$("#mobiletext").val();
            if (titlemobile!=""){
		$.get(route+"controller/server/common/messmobilesend.php", { titlemobile: titlemobile,mobiletext:mobiletext,userid:guseridsender},
		    function(data){
			$().toastmessage('showWarningToast', data);  
		});
	} else {
	  $().toastmessage('showWarningToast', 'Нет заголовка сообщения!');  
	};
        }        
      }
    });    
});
</script>    