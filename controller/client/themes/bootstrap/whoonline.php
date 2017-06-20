<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчки: Грибов Павел, (добавляйте себя если что-то делали))
// http://грибовы.рф
                            
$SQL = "SELECT unix_timestamp(now())-unix_timestamp(lastdt) as res,users_profile.fio as fio,users_profile.jpegphoto FROM users inner join users_profile on users_profile.usersid=users.id where unix_timestamp(now())-unix_timestamp(lastdt)<120";
$result = $sqlcn->ExecuteSQL( $SQL ) or die("Не могу выбрать список заходов пользователей!".mysqli_error($sqlcn->idsqlconnection));
while($row = mysqli_fetch_array($result)) {                                
    $res=$row["res"];                                
    $fio=$row["fio"];                                
    $jpegphoto=$row["jpegphoto"];                                
    if (file_exists("photos/$jpegphoto")==false){$jpegphoto="noimage.jpg";};                                                                        
	echo '<div class="col-xs-1 col-md-1 col-sm-1">';
	echo "<div class=thumbnail>";
	    if (!file_exists("photos/$jpegphoto")) {	
			if (!file_exists("photos/$jpegphoto")) {
			    $jpegphoto = 'noimage.jpg';
			};
	    };				    
	if ($jpegphoto=="" ){} else {
	    echo "<img title='$fio' src=photos/$jpegphoto>";
	};
	//echo "<p align=center>$fio</p>";
	echo "</div>";
	echo "</div>";                                                                                             
};                                                           
