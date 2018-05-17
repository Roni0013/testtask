<?php
    require_once  "Classes.php";
    $request = $_GET;
    // print_r($request);

?>

<?php if (!empty($request)) : ?>
    <?php   
        $clientTab= new clientTable();
        $phoneTab = new phoneTable();
        if (isset($request['Surname']) and (!empty($request['Surname']))) {
            
        
            $resultFind = $clientTab->findBySurname($request['Surname']);
            // print_r ($resultFind); die;
  
        } elseif (isset($request['phone']) and (!empty($request['phone']))) {
            $clientId =  $phoneTab->findOne($request['phone']);
			print_r ($clientId); die;
			
            $resultFind= $clientTab->findClientIdByPhone($clientId[0]['id_client']);
            
        }
        // print_r ($resultFind);
    ?>


    <!-- результаты поиска -->

<a href="index1.php" > Главная </a>
<h1>Результат поиска</h1>
	
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
	    <?php foreach ($resultFind as $client) : ?>
	    
	    <tr>
		<td>
		    <?php  print_r ($client->Surname); ?>
		</td>
		<td>
		    <?php  print_r ($client->Surname); ?>
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




<?php else: ?>
    

<h1>Поиск</h1>
<form action="find.php">
    <p> Введите фамилию <input type="text" name="Surname"> </p>
    <p> или введите номер телефона <input type="text" name="phone"> </p>
    <input type="submit">
</form>

<?php  endif ?>