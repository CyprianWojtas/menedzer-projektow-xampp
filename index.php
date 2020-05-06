<!DOCTYPE html>
<html>
	<head>
	<meta charset="utf-8">
	<title>Projekty</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
	<link rel="stylesheet" href="/css/index.css">
	</head>
	<body>
		<div id="srodek">
			<div id="menu">
				<div id="tytul">Projekty</div>
				<a href="/phpmyadmin">PHP My Admin</a>
				<div id="prawo">
					<span id="ile">0</span>
					<input type="text" id="wyszukiwanie" placeholder="Wyszukaj...">
				</div>
			</div>
			<div id="lista"></div>
			
			<form action="projekty.php">
				<input name="strona" type="text">
				<input name="dodaj" type="hidden" value="true">
				<input type="submit" value="Dodaj">
			</form>
		</div>
		<script>
			var strony = [<?php
				$strony = fopen("strony.txt", "r") or die("Unable to open file!");
				echo '"'.str_replace("\r\n", '","', fread($strony, filesize("strony.txt"))).'"';
				fclose($strony);
				?>];
			var wyszukiwanie;
			var wyszukiwanie2;
	
			setInterval ( function ()
			{
				wyszukiwanie = document.getElementById ("wyszukiwanie").value.toLowerCase ();
				if (wyszukiwanie != wyszukiwanie2)
					Odswierz (wyszukiwanie);
				wyszukiwanie2 = document.getElementById ("wyszukiwanie").value.toLowerCase ();
			}, 100);
			
			function Odswierz (wyszukiwanie)
			{
				var lista = "";
				var ile = 0;
				
				for (var i = 0; i < strony.length; i++)
				{
					var stronaTylArray = strony[i].split(".");
					var stronaTyl = stronaTylArray[0];
					
					for (var i2 = 1; i2 < stronaTylArray.length; i2++)
					{
						stronaTyl = stronaTylArray[i2] + "." + stronaTyl;
					}
					
					
					
					
					if (strony[i].toLowerCase ().search (wyszukiwanie) != -1)
					{
						lista = lista + '<div class="strona"><a href="http://' + stronaTyl + '.<?php echo $_SERVER['SERVER_NAME']; ?>">' + strony[i] + '</a><a href="/projekty.php?dodaj=false&strona=' + strony[i] + '" class="usun"> x</a></div>';
						
						ile++;
					}
				}
				if (ile == 0)
					lista = "Brak";
				
				document.getElementById ("lista").innerHTML = lista;
				document.getElementById ("ile").innerHTML = ile;
			}
		</script>
	</body>
</html>