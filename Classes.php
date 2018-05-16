<?php

abstract class myDatabase {

    protected $pdo;

    function __construct() {
	$host='10.73.0.124';
	$user = "test";
	$password = "test";
	$dsn = "mysql:host=$host;dbname=Testdb";
	
	$this->pdo = new PDO($dsn, $user, $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
	$this->pdo->exec("set character_set_client='utf8_default'");
	$this->pdo->exec("SET CHARACTER SET 'utf8_default'");
	// $this->pdo->exec("SET SESSION collation_connection = 'utf8_general_ci'");
    }
	//общие методы для всех подклассов
	//возвращает объект конретной модели
	function find ($id) {
		$this->selectPrep->execute([$id]);
		$arr = $this->selectPrep()->fetch();
		if (! is_array($arr)) {
			return null;
		}
		$obj = $this->createObj($arr);
		return ($obj);
	}
	//создание нужного объекта по конретной модели из асс. массива
	function createObj (array $arr) {
		$obj=$this->subCreateObj($arr);
		return $obj;
	}
	// возвращает массив моделей
	function findall () {
		$query = "SELECT * FROM ".$this->tableName();
		$arr = $this->pdo->query($query)->fetchall();
		if (! is_array($arr)) {
			return null;
		}
		foreach ($arr as $row) {
			$resultArr[]= new $this->createObj($row);
		}
		return $resultArr;
	}

	function insert () {

	}

//подготовка запроса в конретном классе таблицы
protected abstract function selectPrep ();
//
protected abstract function subCreateObj (array $arr);
protected abstract function subInsert();
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
		
		$this->selPrep=self::$pdo->prepare("SELECT * FROM ".$this->tableName()." WHERE id=?");  
		
	}
	
	function selectPrep () {
		return $this->selPrep;
	}

	function subCreateObj ($arr) {
		$clientObj = new ClientModel ();
		$clientObj->setAttributes ($arr);
		return $clientObj;
	}

	function tableName () {
		return 'client';
	}

    //изменение карточки
    function updateClient(ClientModel $clienObj) {
	$query = "UPDATE client SET Name=:Name,Surname=:Surname,Fname=:Fname,Birthday=:Birthday,Sex=:Sex WHERE id=:id";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':id' => $clienObj->id, ':Name' => $clienObj->name,':Surname'=>$clienObj->Surname,':Birthday'=>$clienObj->Birthday, ':Sex'=>$clientObj->Sex]);
    }

    //удаление клиента
    function delClient($id) {
	$query = "DELETE FROM client WHERE id=:id";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':id' => $id]);
    }

    //найти клиента по номеру
    function findOne($id) {
	$query = "SELECT * FROM client WHERE id=:id LIMIT 1";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':id' => $id]);
	$result = $prep->fetchAll();
//	 print_r ($result[0]);
	// $client = new ClientModel();
	// $client->setAttributes($result[0]);
	return $result;
	}
	// найти несколько
	function find($id) {
		$query = "SELECT * FROM client WHERE id=:id";
		$prep = $this->pdo->prepare($query);
		$prep->execute([':id' => $id]);
		$result = $prep->fetchAll();
	//	 print_r ($result[0]);
		$client = new ClientModel();
		$client->setAttributes($result);
		return $client;
		}

    //найти клиента по фамилии
    function findBySurname($Surname) {
	$query = "SELECT * FROM client WHERE Surname=:surname";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':surname' => $Surname]);
	$result = $prep->fetchAll();
//	 print_r ($result[0]);
	// $client = new ClientModel();
	// $client->setAttributes($result);
	return $result;
    }
    //вставка записи клиента
    function addClient (ClientModel $client) {
	$query="INSERT INTO client (Name,Surname,Fname,Birthday,Sex,CreateDate,UpdateDate,UpdateTime) VALUES (:Name,:Surname,:Fname,:Birthday,:Sex,:CreateDate,:UpdateDate,:UpdateTime)";
	$prep= $this->pdo->prepare($query);
	$prep->execute([':Name'=>$client->Name,':Surname'=>$client->Surname,':Fname'=>$client->Fname,':Birthday'=> $client->Birthday, ':Sex'=>$client-> Sex,':CreateDate'=>date("Y-m-d"),':UpdateDate'=>date("Y-m-d"),':UpdateTime'=>date("H:i:s")]);
    }

	function findLastUpdate () {
		$query="SELECT c1.id FROM client  c1 where c1.UpdateTime = (SELECT max(c2.UpdateTime) from client c2  LIMIt 1 )";
		// print_r ($query);
		return $this->pdo->query($query)->fetch()['id'];
	}
}

class phone extends myDatabase {

    //найти все телефоны клиента
    function findall($id_client) {
	$query = "SELECT * FROM phone WHERE id_client=:id_client";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':id_client' => $id_client]);
	return $prep->fetchAll();
    }

    //изменение телефона
    function updatePhone(PhoneModel $phoneObj) {
	$query = "UPDATE phone SET phone=:phone WHERE id=:id";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':id' => $phoneObj->id, ':phone' => $phoneObj->phone]);
    }

    //удаление телефона по номеру клиента
    function delPhoneByClient($id_client) {
	$query = "DELETE FROM phone WHERE id_client=:id_client";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':id_client' => $id_client]);
    }

    //найти клиентский id по номеру телефона
    function findOne($phone) {
	$query = "SELECT id_client FROM phone WHERE phone=:phone";
	$prep = $this->pdo->prepare($query);
	$prep->execute([':phone' => $phone]);
	$client_id = $prep->fetchAll();
	return $client_id;
    }
    function addPhone (PhoneModel $phone) {
	$query="INSERT INTO phone (phone,id_client) VALUES (:phone, :id_client)";
	$prep= $this->pdo->prepare($query);
	$prep->execute([':phone'=>$phone->phone,':id_client'=>$phone->id_client]);
    }

}

//шаблон для таблицы клиенты
class ClientModel {

    public $id;
    public $Name;
    public $Surname;
    public $Fname;
    public $Birthday;
    public  $Sex;
    public $CreateDate;
	public $UpdateDate;
	public $UpdateTime;

    //заполнение объекта
    function setAttributes(array $data) {
		foreach ($data as $key => $value) {
			if (property_exists('ClientModel', $key))
			$this->{$key} = $value;
		}
    }
	//вставка одного свойства (вывести в абстракт)
	function setAttribute ($nameAttr, $value) {
		if (property_exists('ClientModel',$nameAttr)) {
			$this->{$nameAttr} = $value;
			return true;
		}
		return false;
	}
    //
}

CLASS PhoneModel {
    public $id;
    public $phone;
    public $id_client;

    //заполнение объекта
    function setAttributes(array $data=[]) {
	foreach ($data as $key => $value) {
	    if (property_exists('PhoneModel', $key))
		$this->{$key} = $value;
	}
    }
}
// $client = new clientTable();
// $phone=new PhoneModel();
// $tab= new phone();
// print_r ($tab->findOne(2345));
// $phone->phone='123456';
// $phone->id_client='12';
// $tab->addPhone($phone);
// $clientMod = new ClientModel();
// //print_r (date('Y-m-d'));
// $clientMod->setAttributes(['Name'=>'Ivan']);
// //	print_r ($clientMod);
	// print_r ($client->findLastUpdate());

