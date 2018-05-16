<!DOCTYPE html>



<html>
    <head>
        <meta charset="UTF-8">
        <title>Клиенты</title>
    </head>
    <body>

<?php

    require_once  "Classes.php";

    $request = $_GET;
//    print_r($request);
    $client=new clientTable();
    $phone=new phone();
	if (isset($request['action'])) {
		if ($request['action']=='add') {
			$clientMod = new ClientModel();
			$phoneMod = new PhoneModel();
			$clientMod->setAttributes($request);
			$client->addClient($clientMod);
			
			$phoneMod->id_client=$client->findLastUpdate();
			
			if (isset ($request['phone1']))  {
				$phoneMod->phone=$request['phone1'];
				// print_r ($phoneMod);die;
				$phone->addPhone($phoneMod);
			}
			
			if (isset ($request['phone2']))  {
				$phoneMod->phone=$request['phone2'];
				$phone->addPhone($phoneMod);
			}

			
			

		} else if ($request['action']=='del') {
			$client->delClient($request['id']);
			$phone->delPhoneByClient($request['id']);
		}
		$allClients= $client->findall();
	}

//    print_r ($request);



//    print_r ($allClients);
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
		    <?php  print_r ($client['Surname']); ?>
		</td>
		<td>
		    <?php  print_r ($client['Name']); ?>
		</td>
		<td>
		    <?php  print_r ($client['Fname']); ?>
		</td>

		<td>
		    <a href="card.php?client_id=<?php    print_r( $client['id'])     ?> "> Подробнее
		</td>
		<td>
		    <a href="index1.php?action=del&id= <?php  print_r ($client['id']) ?>  ">   Удалить  </a>
		</td>






	    </tr>

	    <?php	    endforeach; ?>

	</table>
	 <p> <a href="addclient.php"> Добавить  клиента </a> </p>
	 <p> <a href="find.php"> Поиск по фамилии или номеру телефона</a> </p>
	 
    </body>
</html>
