<?php

 $viberenterhttp=$_POST["viberenterhttp"];
 $viberlogin=$_POST["viberlogin"];
 $viberpass=$_POST["viberpass"];
 $vibersender=$_POST["vibersender"];
 
 $cfg->SetByParam("viber-enter-http",$viberenterhttp);
 $cfg->SetByParam("viber-login",$viberlogin);
 $cfg->SetByParam("viber-password",$viberpass);
 $cfg->SetByParam("viber-sender",$vibersender);
 
 ?>
