<?php

/**
 * (с) 2011-2015 Грибов Павел
 * http://грибовы.рф * 
 * Если исходный код найден в сети - значит лицензия GPL v.3 * 
 * В противном случае - код собственность ГК Яртелесервис, Мультистрим, Телесервис, Телесервис плюс * 
 */
class Tmenu
{

    var $arr_menu = array();
 // Массив где хранится меню
    var $count = 0;

    /*
     * структура массива:
     * []
     */
    
    /**
     * Добавляем пункт меню.
     * Если такой uid уже есть - то обновляем содержимое
     *
     * @param type $parents
     *            (main - первый уровень меню), иначе ссылка вида uid на id "родителя"
     * @param type $name
     *            Наименование пункта меню
     * @param type $comment
     *            Пояснение
     * @param type $sort
     *            Сортировка
     * @param type $uid
     *            Некий идетификатор
     * @param type $path
     *            Путь для запуска скрипта (подставляется как content_page=$path)
     */
    function Add($parents, $ico, $name, $comment, $sort, $uid, $path, $ajax = false,$mobile=false){
        // Если корневой уровень меню - то добавляем его
        if ($parents == 'main') {
            $this->count ++;
            $this->arr_menu[$this->count]['sort'] = $sort;
            $this->arr_menu[$this->count]['ico'] = $ico;
            $this->arr_menu[$this->count]['id'] = $this->count;
            $this->arr_menu[$this->count]['parents'] = 'main';
            $this->arr_menu[$this->count]['name'] = $name;
            $this->arr_menu[$this->count]['comment'] = $comment;
            $this->arr_menu[$this->count]['uid'] = $uid;
            $this->arr_menu[$this->count]['path'] = $path;
            $this->arr_menu[$this->count]['ajax'] = $ajax;
	    $this->arr_menu[$this->count]['mobile'] = $mobile;
        } else {
            // Сначала ищем "родителя"
            foreach ($this->arr_menu as $value) {
                if ($parents == $value['uid']) {
                    $this->count ++;
                    $this->arr_menu[$this->count]['sort'] = $sort;
                    $this->arr_menu[$this->count]['ico'] = $ico;
                    $this->arr_menu[$this->count]['id'] = $this->count;
                    $this->arr_menu[$this->count]['parents'] = $value['uid'];
                    $this->arr_menu[$this->count]['name'] = $name;
                    $this->arr_menu[$this->count]['comment'] = $comment;
                    $this->arr_menu[$this->count]['uid'] = $uid;
                    $this->arr_menu[$this->count]['path'] = $path;
                    $this->arr_menu[$this->count]['ajax'] = $ajax;
		    $this->arr_menu[$this->count]['mobile'] = $mobile;
                }
            }
        }
    }

    function GetFromFiles($pp){
        global $cfg, $user;
        $mfiles = GetArrayFilesInDir($pp);
        foreach ($mfiles as &$fname) {
            if (is_file("$pp/$fname")) {
                include_once ("$pp/$fname");
            }
        }
    }

    function GetList($parents){
        $res = array();
        foreach ($this->arr_menu as $value) {
            if ($parents == $value['parents']) {
                $res[] = $value;
            }
        }
        array_multisort($res);
        return $res;
    }
}
