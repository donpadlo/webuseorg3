<?php

// Данный код создан и распространяется по лицензии GPL v3
// Изначальный автор данного кода - Грибов Павел
// http://грибовы.рф

class Tbp {

	var $id;  // уникальный идентификатор
	var $userid; // кто создатель
	var $dt;  // дата и время создания
	var $title;  // заголовок БП
	var $bodytxt; // пояснение
	var $status; // 0 - создано, 1-стартовано, 2-утверждено, 3-отменено, 4-отправлено на доработку
	var $node;  // текущий узел обработки в файле xml
	var $step;  // текущий шаг в данном БП
	var $xml;  // схема БП файл xml

	/**
	 * Получаем данные по идентификатору
	 * @global type $sqlcn
	 * @param type $id
	 */

	function GetById($id) {
		global $sqlcn;
		$result = $sqlcn->ExecuteSQL("SELECT * FROM bp_xml WHERE id='$id'")
				or die('Неверный запрос Tbp_xml.GetById: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['id'];
			$this->userid = $myrow['userid'];
			$this->title = $myrow['title'];
			$this->bodytxt = $myrow['bodytxt'];
			$this->status = $myrow['status'];
			$this->dt = $myrow['dt'];
			$this->node = $myrow['node'];
			$this->xml = $myrow['xml'];
			$this->step = $myrow['step'];
		}
	}

	/**
	 * Получаем последний созданный БП
	 * @global type $sqlcn
	 */
	function GetLast() {
		global $sqlcn;
		$result = $sqlcn->ExecuteSQL('SELECT * FROM bp_xml ORDER BY id DESC LIMIT 1')
				or die('Неверный запрос Tbp_xml.GetById: '.mysqli_error($sqlcn->idsqlconnection));
		while ($myrow = mysqli_fetch_array($result)) {
			$this->id = $myrow['id'];
			$this->userid = $myrow['userid'];
			$this->title = $myrow['title'];
			$this->bodytxt = $myrow['bodytxt'];
			$this->status = $myrow['status'];
			$this->dt = $myrow['dt'];
			$this->node = $myrow['node'];
			$this->xml = $myrow['xml'];
			$this->step = $myrow['step'];
		}
	}

	/**
	 * Записываем в базу параметры о текущем шаге
	 * @global type $sqlcn
	 * @global type $cfg
	 * @param type $node
	 */
	function SetNodeToBase($node) {
		global $sqlcn, $cfg;
		$url = $cfg->urlsite.'/index.php?content_page=mybp';
		if (file_exists("../../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../modules/bp/$this->xml");
		} else
		if (file_exists("../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../modules/bp/$this->xml");
		} else
		if (file_exists("modules/bp/$this->xml")) {
			$xml = simplexml_load_file("modules/bp/$this->xml");
		}
		//var_dump($xml);    
		foreach ($xml->step as $step) {
			//echo "!$step->node!$node";
			if ($step->node == $node) {
				//echo "!$step->node!$node";
				if ($step->thinking == '') {
					$step->thinking = -2;
				}
				if ($step->accept == '') {
					$step->accept = -2;
				}
				if ($step->cancel == '') {
					$step->cancel = -2;
				}
				if ($step->yes == '') {
					$step->yes = -2;
				}
				if ($step->no == '') {
					$step->no = -2;
				}
				if ($step->one == '') {
					$step->one = -2;
				}
				if ($step->two == '') {
					$step->two = -2;
				}
				if ($step->three == '') {
					$step->three = -2;
				}
				if ($step->four == '') {
					$step->four = -2;
				}

				$st = $this->step + 1;
				foreach ($step->user as $user) {
					$user = GetUserIdByPostId($user);
					$sql = "INSERT INTO bp_xml_userlist (id,bpid,dtstart,timer,userid,accept,cancel,thinking,yes,no,one,two,three,four,status,result,node,step) VALUES 
						(NULL,$this->id,NOW(),$step->timer,$user,'$step->accept','$step->cancel','$step->thinking','$step->yes','$step->no','$step->one','$step->two','$step->three','$step->four',0,0,'$node','$st')";
					$result = $sqlcn->ExecuteSQL($sql, $cfg->base_id);
					$zz = new Tusers;
					$zz->GetById($user);
					smtpmail($zz->email, "Новая задача!", " Вам необходимо зайти на портал и согласовать задачу <a href=$url>здесь</a>");
					//echo "$sql";
				}
				$sql = "UPDATE bp_xml SET node='$node',step=step+1 WHERE id='$this->id'";
				$result = $sqlcn->ExecuteSQL($sql, $cfg->base_id);
				//echo $sql;
			}
		}
	}

	/**
	 * 
	 * @global type $sqlcn
	 * @param type $node
	 * @return type
	 */
	function GetTitleNode($node) {
		global $sqlcn;
		$tt = array();
		$tt['comment'] = '';
		$tt['title'] = '';
		if (file_exists("../../../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../modules/bp/$this->xml");
		} else
		if (file_exists("../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../modules/bp/$this->xml");
		} else
		if (file_exists("modules/bp/$this->xml")) {
			$xml = simplexml_load_file("modules/bp/$this->xml");
		}
		foreach ($xml->step as $step) {
			if ($step->node == $node) {
				$title = $step->title;
			}
		}
		return $title;
	}

	/**
	 * 
	 * @global type $sqlcn
	 * @param type $node
	 * @return type
	 */
	function GetTitleAndCommentNode($node) {
		//global $sqlcn;
		$tt = array();
		$tt['comment'] = '';
		$tt['title'] = '';
		if (file_exists("../../../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../../modules/bp/$this->xml");
		} else
		if (file_exists("../../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../../modules/bp/$this->xml");
		} else
		if (file_exists("../modules/bp/$this->xml")) {
			$xml = simplexml_load_file("../modules/bp/$this->xml");
		} else
		if (file_exists("modules/bp/$this->xml")) {
			$xml = simplexml_load_file("modules/bp/$this->xml");
		}

		foreach ($xml->step as $step) {
			if ($step->node == $node) {
				$tt['title'] = $step->title;
				$tt['comment'] = $step->comment;
			}
		}
		return $tt;
	}

	/**
	 * 
	 * @global type $sqlcn
	 * @global type $cfg
	 * @param type $status
	 */
	function SetStatus($status) {
		global $sqlcn, $cfg;
		if ($this->status != $status) {
			$url = $cfg->urlsite;
			$sqlcn->ExecuteSQL("UPDATE bp_xml SET status='$status' WHERE id='$this->id'", $cfg->base_id)
					or die('Неверный запрос Tbp_xml.SetStatus: '.mysqli_error($sqlcn->idsqlconnection));
			$zz = new Tusers;
			$zz->GetById($this->userid);
			smtpmail($zz->email, "Изменился статус БП!", "Внимание! Зайдите на портал и посмотрите статус БП№ $this->id <br><a href=$url/index.php?content_page=bp>$this->title</a>");
		}
	}

}

?>