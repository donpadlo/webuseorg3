<?php

// Данный код создан и распространяется по лицензии GPL v3
// Разработчики:
//   Грибов Павел,
//   Сергей Солодягин (solodyagin@gmail.com)
//   (добавляйте себя если что-то делали)
// http://грибовы.рф

defined('WUO_ROOT') or die('Доступ запрещён'); // Запрещаем прямой вызов скрипта.

$step = GetDef('step');
$userid = GetDef('userid');
$fio = PostDef('fio');
$post = PostDef('post');
$photo = PostDef('picname');
$code = PostDef('code');
$phone1 = PostDef('phone1');
$phone2 = PostDef('phone2');
//echo "!$userid!";
$tmpuser = new Tusers();
$tmpuser->GetById($userid);
$tmpuser->fio = $fio;
$tmpuser->jpegphoto = $photo;
//echo "$fio!$userid";
$tmpuser->post = $post;
$tmpuser->tab_num = $code;
$tmpuser->telephonenumber = $phone1;
$tmpuser->homephone = $phone2;
$tmpuser->Update();
unset($tmpuser);

echo 'ok';
