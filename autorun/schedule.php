<?php

/*
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф *
 * Если исходный код найден в сети - значит лицензия GPL v.3 *
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс *
 */
if ($user->mode == 1) {
    $md = new Tmod(); // обьявляем переменную для работы с классом модуля
    $md->Register("schedule", "Расписание уведомлений", "Грибов Павел");
    if ($md->IsActive("schedule") == 1) {
  //      $cfg->quickmenu[] = '<a title="Расписание уведомлений" href=index.php?content_page=schedule><button type=\'button\' class=\'btn btn-info navbar-btn \'><i class="fa fa-bullhorn"></i></button></a>';
    }
    ;
    unset($mb);
}
;
?>