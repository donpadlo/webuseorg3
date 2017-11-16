<?php
include_once ("db.class.php");

class PhoneGroups
{

    var $DB;

    function PhoneGroups($host, $login, $password, $database)
    {
        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $login, $password);
        $this->DB = new Db($pdo);
    }

    public function getUsers()
    {
        $result = array();
        $q = "SELECT * FROM sms_users";
        $result = $this->DB->queryRows($q);
        return $result;
    }

    public function getUser($id)
    {
        $q = "SELECT * FROM sms_users WHERE id=:id";
        $result = $this->DB->queryRow($q, array(
            "id" => $id
        ));
        return $result;
    }

    public function insertUser($name, $phone, $telegram)
    {
        $res = $this->DB->queryRow("SELECT * FROM sms_users WHERE name=:name AND phone=:phone", array(
            "name" => $name,
            "phone" => $phone
        ));
        if ($res != false)
            return false;
        $this->DB->insert("sms_users", array(
            "name" => $name,
            "phone" => $phone,
            "telegram" => $telegram
        ));
        return true;
    }

    public function saveEditUser($id, $name, $phone, $telegram)
    {
        $res = $this->DB->update("sms_users", array(
            "name" => $name,
            "phone" => $phone,
            "telegram" => $telegram
        ), "id=:id", array(
            "id" => $id
        ));
        return;
    }

    public function getGroups()
    {
        $result = array();
        $q = "SELECT * FROM sms_groups order by name";
        $result = $this->DB->queryRows($q);
        return $result;
    }

    public function getGroupName($id)
    {
        $result = array();
        $q = "SELECT * FROM sms_groups WHERE id=:id";
        $result = $this->DB->queryRow($q, array(
            "id" => $id
        ));
        return $result;
    }

    public function insertGroup($name)
    {
        $res = $this->DB->queryRow("SELECT * FROM sms_groups WHERE name=:name", array(
            "name" => $name
        ));
        if ($res != false)
            return false;
        $this->DB->insert("sms_groups", array(
            "name" => $name
        ));
        return true;
    }

    public function getGroupMembers($id)
    {
        $result = array();
        $q = "SELECT su.id,su.name FROM sms_users su LEFT JOIN sms_group_members sgm on sgm.user_id = su.id WHERE group_id=:id";
        $result = $this->DB->queryRows($q, array(
            "id" => $id
        ));
        return $result;
    }

    public function saveEditGroup($id, $name, $members)
    {
        $this->DB->update("sms_groups", array(
            "name" => $name
        ), "id=:id", array(
            "id" => $id
        ));
        $this->DB->sql("DELETE FROM sms_group_members WHERE group_id=$id");
        foreach ($members as $key => $member) {
            $result[] = $member;
            $this->DB->insert("sms_group_members", array(
                "group_id" => $id,
                "user_id" => $member
            ));
        }
        return $result;
    }

    public function getPhones($groups)
    {
        $result = array();
        // $groups = explode(",",$groups);
        for ($i = 0; $i < count($groups); $i ++) {
            $q = "SELECT su.phone AS phone FROM sms_users su 
		    LEFT JOIN sms_group_members sgm ON sgm.user_id = su.id 
		    LEFT JOIN sms_groups sg ON sg.id = sgm.group_id 
		    WHERE sg.name = :group_name";
            $res = $this->DB->queryRows($q, array(
                "group_name" => $groups[$i]
            ));
            if (count($res) > 0) {
                for ($a = 0; $a < count($res); $a ++) {
                    if (! in_array($res[$a]["phone"], $result))
                        $result[] = $res[$a]["phone"];
                }
            }
        }
        return $result;
    }
}