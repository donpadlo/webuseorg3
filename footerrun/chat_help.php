<?php

/* 
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
$md=new Tmod; // обьявляем переменную для работы с классом модуля
if ($md->IsActive("chat")==1) {
    $printable=_GET("printable");
	if ($printable!="true"){
	?>
	 <link rel="stylesheet" href="controller/client/themes/bootstrap/css/chat_client_noc.css">
	 <script type="text/javascript" src="chat_client/chat_client_noc.php"></script> 
	 <div id='chat_message_tab' onclick='ViewContactListBox()' class='message_online'>Мессенджер</div> 
	 <div id='chat_box' class='chat_box'>
	     <div id='chat_contactlist_box' class='chat_contactlist_box'></div>
	     <div id='chat_message_box' class='chat_message_box'></div>
	     <div id='chat_button_close' class='chat_button_close' onclick='HideMessenger();'>[X]</div>
	     <div id='chat_user_select' class='chat_user_select'></div>     
	     <textarea onkeypress='ChatCtrlEnter(event)' oninput='textinput()' id='chat_enter_text' class='chat_enter_text' placeholder='Введите текст сообщения'></textarea>
	     <input class='chat_button_send' type='submit' onclick='ChatSendText()' value='Ctrl+Enter'>     
	 </div> 
	<?php
	};
};
unset($md);
?>