<?php
//общий шаблон-модель для таблиц
class model {


    //заполнение объекта
    function setAttributes(array $data) {
		foreach ($data as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                $this->{$key} = $value;
               
            }
            
		}
    }

    //вставка одного свойства (вывести в абстракт)
	function setAttribute ($nameAttr, $value) {
		if (property_exists(get_class($this),$nameAttr)) {
			$this->{$nameAttr} = $value;
		}
    }
    
}


class ClientModel extends model{

    public $id;
    public $Name;
    public $Surname;
    public $Fname;
    public $Birthday;
    public $Sex;
    public $CreateDate;
	public $UpdateDate;
	public $UpdateTime;

    
    //валидация
}

CLASS PhoneModel extends model{
    public $id;
    public $phone;
    public $id_client;

    //валидация
    
}