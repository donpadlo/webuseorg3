<?php
class Tequipment
{
    var $id;            // уникальный идентификатор
    var $orgid;         // какой организации принадлежит
    var $placesid;      // в каком помещении
    var $usersid;       // какому пользователю принадлежит
    var $nomeid;        // связь со справочником номенклатуры
    var $tmcname;       // наименование ТМЦ из справочника номенклатуры    
    var $buhname;       // имя по "бухгалтерии"
    var $datepost;      // дата прихода
    var $cost;          // стоимость прихода
    var $currentcost;   // текущая стоимость
    var $sernum;        // серийный номер
    var $invnum;        // инвентарный номер
    var $shtrihkod;     // штрихкод
    var $os;            // основные средства? 1 - да, 0 - нет
    var $mode;          // списано?  1 - да, 0 - нет
    var $comment;       // комментарий к ТМЦ
    var $photo;         // файл с фото
    var $repair;        // в ремонте?   1 - да, 0 - нет
    var $active;        // помечено на удаление?  1 - да, 0 - нет
    var $ip;            // ИП адрес
    var $mapx;          // координата Х на карте
    var $mapy;          // координата У на карте
    var $mapmoved;      // было перемещение?  1 - да, 0 - нет
    var $mapyet;        // отображать на карте?  1 - да, 0 - нет
    
    
function GetById($id){ // обновляем профиль работника с текущими данными (все что заполнено)
	global $sqlcn;                
	$SQL = "SELECT equipment.comment,equipment.mapyet,equipment.mapmoved,equipment.mapx,equipment.mapy,equipment.ip,equipment.photo,equipment.nomeid,getvendorandgroup.grnomeid,equipment.id AS eqid,equipment.orgid AS eqorgid, org.name AS orgname, getvendorandgroup.vendorname AS vname, 
            getvendorandgroup.groupname AS grnome,places.id as placesid, places.name AS placesname, users.login AS userslogin, users.id AS usersid,
            getvendorandgroup.nomename AS nomename, buhname, sernum, invnum, shtrihkod, datepost, cost, currentcost, os, equipment.mode AS eqmode,equipment.mapyet AS eqmapyet,equipment.comment AS eqcomment, equipment.active AS eqactive,equipment.repair AS eqrepair
	FROM equipment
	INNER JOIN (
	SELECT nome.groupid AS grnomeid,nome.id AS nomeid, vendor.name AS vendorname, group_nome.name AS groupname, nome.name AS nomename
	FROM nome
	INNER JOIN group_nome ON nome.groupid = group_nome.id
	INNER JOIN vendor ON nome.vendorid = vendor.id
	) AS getvendorandgroup ON getvendorandgroup.nomeid = equipment.nomeid
	INNER JOIN org ON org.id = equipment.orgid
	INNER JOIN places ON places.id = equipment.placesid
	INNER JOIN users ON users.id = equipment.usersid WHERE equipment.id='$id'";	                
                
                $result = $sqlcn->ExecuteSQL($SQL);                
  		if ($result!=''){                    
                    while ($myrow = mysqli_fetch_array($result)){
                        $this->id=$myrow["eqid"];
                        $this->orgid=$myrow["eqorgid"];
                        $this->placesid=$myrow["placesid"];
                        $this->usersid=$myrow["usersid"];
                        $this->nomeid=$myrow["nomeid"];
                        $this->buhname=$myrow["buhname"];
                        $this->datepost=$myrow["datepost"];
                        $this->cost=$myrow["cost"];
                        $this->currentcost=$myrow["currentcost"];
                        $this->sernum=$myrow["sernum"];
                        $this->invnum=$myrow["invnum"];
                        $this->shtrihkod=$myrow["shtrihkod"];
                        $this->os=$myrow["os"];
                        $this->mode=$myrow["eqmode"];
                        $this->comment=$myrow["comment"];
                        $this->photo=$myrow["photo"];
                        $this->repair=$myrow["eqrepair"];
                        $this->active=$myrow["eqactive"];
                        $this->ip=$myrow["ip"];
                        $this->mapx=$myrow["mapx"];
                        $this->mapy=$myrow["mapy"];
                        $this->mapmoved=$myrow["mapmoved"];
                        $this->mapyet=$myrow["mapyet"];
                        $this->tmcname=$myrow["nomename"];
                };};
  		if ($result==''){die('Неверный запрос Tequipment.GetById: ' . mysqli_error($sqlcn->idsqlconnection));}
    
}    
};
?>