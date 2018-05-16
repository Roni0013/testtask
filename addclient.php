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
		<h1>Карточка клиента	</h1>
	<form action="index1.php" method="GET" name="addclient">

	    <p> Фамилия <input type="text" name="Surname"> </p>
	    <p> Имя <input type="text" name="Name"> </p>
	    <p> Отчество <input type="text" name="Fname"> </p>
	    <p> День рождения <input type="date" name="Birthday"> </p>


	    Пол <input list="pol" name="Sex" autocomplete="false">
	    <datalist id="pol" >
		<option value="М" >Мужчина
		    <option value="Ж" name="Sex">Женщина
	    </datalist>
	    <p> Телефон1 <input type="text" name="phone1"> <p> 
	    <p> Телефон2 <input type="text" name="phone2"> <p>
	     <input type="hidden" name="action" value="add">


	     <input type="submit">
	</form>



    </body>
</html>
