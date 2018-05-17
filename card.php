<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
	<?php
	    $request = $_GET;
	    require_once  "Classes.php";
	    $clientTab=new clientTable();
		$phoneMod = new phoneModel();
		$phoneTab = new phoneTable();
		$oneClient = $clientTab->findOne($request['client_id']);
		$phones= $phoneTab->findByClient($request['client_id']);
	?>

	<a href="index1.php" > Главная </a>
	<p>Карточка клиента </p>
	<table>
	    <tr>
		<td>
		    Фамилия
		</td>
		<td>
		    <?php    print_r ($oneClient->Surname);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Имя
		</td>
		<td>
		    <?php    print_r ($oneClient->Name);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Отчество
		</td>
		<td>
		    <?php    print_r ($oneClient->Fname);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Дата рождения
		</td>
		<td>
		    <?php    print_r ($oneClient->Birthday);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Пол
		</td>
		<td>
		    <?php    print_r ($oneClient->Sex);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Дата создания записи
		</td>
		<td>
		    <?php    print_r ($oneClient->CreateDate);   ?>
		</td>
	    </tr>
	    <tr>
		<td>
		    Дата обновления записи
		</td>
		<td>
		    <?php    print_r ($oneClient->UpdateDate);   ?>
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
	<a href="index1.php?action=del&id= <?php  print_r ($oneClient->id) ?>  ">   Удалить клиента </a>
	
    </body>
</html>
