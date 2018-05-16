<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
	<?php
	    $request = $_GET;
//	    print_r ($request);
	   require_once  "Classes.php";
	    $clientObj=new clientTable();
		$phoneObj = new PhoneModel();
		$phoneTable = new phone();
	    $client = $clientObj->findOne($request['client_id']);
	    // $phones= $phoneObj->findall($request['client_id']);
		$phones= $phoneTable->findall($request['client_id']);
		// print_r ($request['client_id']);
	?>

	<a href="index1.php" > Главная </a>
	<p>Карточка клиента </p>
	<table>
	    <tr>
		<td>
		    Фамилия
		</td>
		<td>
		    <?php    print_r ($client->Surname);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Имя
		</td>
		<td>
		    <?php    print_r ($client->Name);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Отчество
		</td>
		<td>
		    <?php    print_r ($client->Fname);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Дата рождения
		</td>
		<td>
		    <?php    print_r ($client->Birthday);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Пол
		</td>
		<td>
		    <?php    print_r ($client->Sex);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Дата создания записи
		</td>
		<td>
		    <?php    print_r ($client->CreateDate);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Дата обновления записи
		</td>
		<td>
		    <?php    print_r ($client->UpdateDate);   ?>
		</td>
	    </tr>
		
	</table>
	<p> Телефоны </p>
		<table>
	    <?php    foreach ($phones as $phone) :    ?>
	    <tr>
			<td>
			   Телефон
			</td>
			<td>
		    	<?php     print_r ($phone['phone']);   ?>
			</td>
		
		<?php endforeach;  ?>
		</table>

	<a href="addclient.php"> Добавить клиента </a>
	<a href="index1.php?action=del&id= <?php  print_r ($client->id) ?>  ">   Удалить клиента </a>
	
    </body>
</html>
