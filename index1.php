<!DOCTYPE html>



<html>
    <head>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Клиенты</title>
    </head>
    <body>

<?php

    require_once  "Classes.php";

    $request = $_GET;
    $clientTab=new clientTable();
    $phoneTab=new phoneTable();
	if (isset($request['action'])) {
		if ($request['action']=='add') {
			$clientMod = new ClientModel();
			$phoneMod = new PhoneModel();
			
			$clientMod->setAttributes($request);
			
			$clientTab->insert($clientMod);
			// print_r ($);
			$lastId=$clientTab->findLastUpdateId();
			$phoneMod->id_client=$lastId;
			// print_r ($phoneMod); 
			if (isset ($request['phone1']) and !empty($request['phone1']))  {
				$phoneMod->phone=$request['phone1'];
				print_r ($phoneMod);
				$phoneTab->insert($phoneMod);
			}
			
			if (isset ($request['phone2']) and !empty($request['phone2']))  {
				$phoneMod->phone=$request['phone2'];
				$phoneTab->insert($phoneMod);
			}
		} else if ($request['action']=='del') {
			$clientTab->del($request['id']);
			$phoneTab->delPhoneByClient($request['id']);
		}
	}
	$allClients= $clientTab->findall();
?>



<h1>Список клиентов</h1>
	
	<table>
	    <tr>
		<td>
		    Фамилия			
		</td>
		<td>
		    Имя			
		</td>
		<td>
		    Отчество		
		</td>

	    </tr>
	    <?php foreach ($allClients as $client) : ?>
	    <?php   //print_r ($client);     ?>
	    <tr>
		<td>
		    <?php  print_r ($client->Surname); ?>
		</td>
		<td>
		    <?php  print_r ($client->Name); ?>
		</td>
		<td>
		    <?php  print_r ($client->Fname); ?>
		</td>

		<td>
		    <a href="card.php?client_id=<?php    print_r( $client->id)     ?> "> Подробнее
		</td>
		<td>
		    <a href="index1.php?action=del&id= <?php  print_r ($client->id) ?>  ">   Удалить  </a>
		</td>






	    </tr>

	    <?php	    endforeach; ?>

	</table>
	 <p> <a href="addclient.php"> Добавить  клиента </a> </p>
	 <p> <a href="find.php"> Поиск по фамилии или номеру телефона</a> </p>
	 
    </body>
</html>
