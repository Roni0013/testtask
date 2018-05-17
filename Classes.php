<?php
require_once("models.php");

abstract class myDatabase {

    protected static $pdo;

    function __construct() {
	$host='10.73.0.124';
	$user = "test";
	$password = "test";
	$dsn = "mysql:host=$host;dbname=Testdb";
	
	self::$pdo = new PDO($dsn, $user, $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
	self::$pdo->exec("set character_set_client='utf8'");
	self::$pdo->exec("SET CHARACTER SET 'utf8'");
	self::$pdo->exec("SET NAMES 'utf8'");
	self::$pdo->exec("SET character_set_connection 'utf8'");
	self::$pdo->exec("SET character_set_results 'utf8'");
	
	
	//общие prepare запросы
	$this->selPrep=self::$pdo->prepare("SELECT * FROM ".$this->tableName()." WHERE id=?"); 
	$this->delPrep=self::$pdo->prepare("DELETE FROM ".$this->tableName()." WHERE id=?");
	// $this->pdo->exec("SET SESSION collation_connection = 'utf8_general_ci'");
    }
	//общие методы для всех подклассов
	//поиск и возврат объекта конретной модели
	function findOne ($id) {
		$this->selPrep->execute([$id]);
		$arr = $this->select()->fetch();
		if (! is_array($arr)) {return null;}
		$obj = $this->createObj($arr);
		return $obj;
	}
	//создание нужного объекта по конретной модели из асс. массива
	private function createObj (array $arr) {
		$obj=$this->subCreateObj($arr);
		return $obj;
	}
	// возвращает массив моделей
	function findall () {
		$query = "SELECT * FROM ".$this->tableName();
		$arr = self::$pdo->query($query)->fetchall();
		if (! is_array($arr)) {
			return null;
		}
		foreach ($arr as $row) {
			$resultArr[]= $this->createObj($row);
		}
		return $resultArr;
	}

	function insert (model $obj) {
		return $this->subInsert($obj);
	}
	function update (model $obj) {
		$this->subUpdate($obj);
	}
	// возврат подготовленного запроса
	function del($id) {
		$this->delPrep->execute([$id]);
	}

	//общий возврат подготовленного запроса
	protected  function select () {
		return $this->selPrep;
	}

protected abstract function subCreateObj (array $arr);
protected abstract function subInsert(model $obj);
protected abstract function subUpdate(model $obj);
//вернуть имя таблицы в конкретном классе
abstract function tableName ();


//    function getConnection () {
//	return $this->pdo;
//     }
}

//  класс для таблицы клиентов
class clientTable extends myDatabase {

	function __construct()
	{
		parent::__construct();
		//
		
		$this->insPrep=self::$pdo->prepare("INSERT INTO ".$this->tableName()." (Name,Surname,Fname,Birthday,Sex,CreateDate,UpdateDate,UpdateTime) VALUES (:Name,:Surname,:Fname,:Birthday,:Sex,:CreateDate,:UpdateDate,:UpdateTime)"); 
		$this->updPrep=self::$pdo->prepare("UPDATE ".$this->tableName()." SET Name=:Name,Surname=:Surname,Fname=:Fname,Birthday=:Birthday,Sex=:Sex,UpdateDate=:UpdateDate,UpdateTime=:UpdateTime WHERE id=:id");
		
	}
	
	function selectPrep () {
		return $this->selPrep;
	}

	protected function subCreateObj (array $arr) {
		$clientObj = new ClientModel ();
		$clientObj->setAttributes ($arr);
		return $clientObj;
	}

	function tableName () {
		return 'client';
	}
 	//изменение карточки
	function subUpdate (model $obj) {
		$binResult = $this->updPrep->execute([':Name'=>$obj->Name,':Surname'=>$obj->Surname,':Fname'=>$obj->Fname,':Birthday'=> $obj->Birthday, ':Sex'=>$obj-> Sex,':UpdateDate'=>date("Y-m-d"),':UpdateTime'=>date("H:i:s")]);
		return $binResult;
	}

	//вставка клиента, возврат вставленного id
	function subInsert (model $obj) {
		$res=$this->insPrep->execute([':Name'=>$obj->Name,':Surname'=>$obj->Surname,':Fname'=>$obj->Fname,':Birthday'=> $obj->Birthday, ':Sex'=>$obj-> Sex,':CreateDate'=>date("Y-m-d"),':UpdateDate'=>date("Y-m-d"),':UpdateTime'=>date("H:i:s")]);
		// $lastId = myDatabase::$pdo->lastInsertId();
		return $res;
	}
	



    //найти клиентов по фамилии,возвращает массив 
    function findBySurname($Surname) {
		$prep = self::$pdo->prepare("SELECT * FROM client WHERE Surname=?");
		$prep->execute([$Surname]);
		$result = $prep->fetchAll();
		if (!is_array($result)) {return null;}
		foreach ($result as $row) {
			$obj=new ClientModel();
			$obj->setAttributes($row);
			$resultArr[]=$obj;
		}
		// print_r ($resultArr);
		return $resultArr;
    }
    
	function findLastUpdateId () {
		$query="SELECT c1.id FROM client  c1 where c1.UpdateTime = (SELECT max(c2.UpdateTime) from client c2  LIMIt 1 )";
		
		return self::$pdo->query($query)->fetch()['id'];
	}
}

class phoneTable extends myDatabase {


	function __construct()
	{
		parent::__construct();
		$this->insPrep=self::$pdo->prepare("INSERT INTO ".$this->tableName()." (phone,id_client) VALUES (:phone,:id_client)"); 
		$this->updPrep=self::$pdo->prepare("UPDATE ".$this->tableName()." SET phone=:phone,id_client=:id_client");
	}

    //найти все телефоны клиента, возврат массива
    function findByClient($id_client) {
		$prep = self::$pdo->prepare("SELECT * FROM phone WHERE id_client=?");
		$prep->execute([$id_client]);
		$result = $prep->fetchAll();
		if (!is_array($result)){ return null;}
		foreach ($result as $row) {
			$resultArr[]=(new PhoneModel())->setAttributes($row);
		}
		return $result;
    }

	protected function subCreateObj (array $arr) {
		$phoneObj = new PhoneModel();
		$phoneObj->setAttributes ($arr);
		return $phoneObj;
	}

	function tableName () {
		return 'phone';
	}

    //изменение телефона
    function subUpdate(model $obj) {
		$binResult=  $this->updPrep->execute([':phone'=>$obj->phone,':id_client'=>$obj->id_client]);
		return $binResult;
    }

    //удаление телефона по номеру клиента
    function delPhoneByClient($id_client) {
		$prep = self::$pdo->prepare("DELETE FROM phone WHERE id_client=?");
		$binResult = $prep->execute([$id_client]);
		return $binResult;
    }

    //найти клиентский id по номеру телефона
    function findByPhone($phone) {
		$prep = self::$pdo->prepare("SELECT id_client FROM phone WHERE phone=?");
		$binResult = $prep->execute([$phone]);
		$client_id = $prep->fetch();
	return $client_id;
	}
	// вставка телефона
    function subInsert (model $phoneMode) {
		$binResult = $this->insPrep->execute ([':phone'=>$phoneMode->phone,':id_client'=>$phoneMode->id_client]);
		return $binResult;
	}
	
	//найти id клиента по номеру телефона
	function findClientIdByPhone ($phone) {
		$prep= self::$pdo->prepare("SELECT id_client FROM ".$this->tableName()." WHERE phone=?");
		$prep->execute([$phone]);
		$arr=$prep->fetch();
		return $arr;
	}

}

// $phoneTab = new phoneTable();
// $phoneMod = new PhoneModel();
// $phoneMod->setAttributes(['phone'=>132454,'id_client'=>'2']);
// $phoneTab->insert($phoneMod);


// $clientTab = new clientTable();
// $clientMod = new ClientModel();
// $clientMod->setAttributes(['Name'=>'Petya','Surname'=>null]);
// $a=$clientTab->findBySurname('Ivanov');

// print_r ($a);

 

