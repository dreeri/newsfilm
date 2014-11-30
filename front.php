<!--
Copyright 2014 Dreeri
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/gpl.txt>.
-->


<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="initial-scale=1">
	<title>Hem</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">

	<?php
	$db = new SQLite3('hem.db');
	if(!isset($_GET['i']))
		$queryString = -1;
	else
		$queryString = SQLite3::escapeString($_GET['i']);
	$results = $db->query('SELECT * FROM uutisfilmi WHERE id = "' . $queryString .'";');
	$frontPageResults = $db->query('SELECT id, otsikko from uutisfilmi ORDER BY otsikko COLLATE NOCASE;');
	$row = $results->fetchArray();

	if(!empty($_POST['inputSubmit']))
	{
		$otsikko = $_POST['otsikko'];
		$id = $_POST['id'];
		$alaotsikko = $_POST['alaotsikko'];
		$tarkistuspvm = $_POST['tarkistuspvm'];
		$pituusAika = $_POST['pituusAika'];
		$pituusMetri = $_POST['pituusMetri'];
		$kuvaajat = $_POST['kuvaajat'];
		$leikkaajat = $_POST['leikkaajat'];
		$ksikirjoitus = $_POST['ksikirjoitus'];
		$suunnittelu = $_POST['suunnittelu'];
		$tagit = $_POST['tagit'];
		$selostus = $_POST['selostus'];
		$db->query('insert into uutisfilmi ( id, otsikko, alaotsikko, tarkistuspvm, pituusAika, pituusMetri, kuvaajat, leikkaajat, ksikirjoitus, suunnittelu, selostus, tagit) values ( '.$id.', "'.$otsikko.'", "'.$alaotsikko.'", "'.$tarkistuspvm.'", "'.$pituusAika.'", "'.$pituusMetri.'", "'.$kuvaajat.'", "'.$leikkaajat.'", "'.$ksikirjoitus.'", "'.$suunnittelu.'", "'.$selostus.'", "'.$tagit.'");');
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}

	if(!empty($_POST['deleteSubmit']))
	{
		$id = $_POST['id'];
		$db->query('delete from uutisfilmi where id='.$id.';');
		header("Location: ".$_SERVER['REQUEST_URI']);
		exit();
	}

	if(!empty($_POST['searchSubmit']))
	{
		$searchString = $_POST['search'];
		switch($_POST['searchOption'])
		{
			case "otsikko":
				$searchType = "otsikko";
				break;
			case "id":
				$searchType = "id";
				break;
			case "alaotsikko":
				$searchType = "alaotsikko";
				break;
			case "tagit":
				$searchType = "tagit";
				break;
			default:
				echo "Invalid search type. Seek psychiatric help.";
				break;
		}
		if($searchType == "tagit")
		{
			$results = $db->query('select * from uutisfilmi where ' .$searchType. ' like "%'.$searchString.'%";');
		}
		else
		{
			$results = $db->query('SELECT * FROM uutisfilmi WHERE '. $searchType .' = "' . $searchString .'";');
		}
		$row = $results->fetchArray();
	}

	?>
</head>

<body>
<div class="container">
	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href=".">Uutisfilmi</a>
			</div>
			<div>
				<ul class="nav navbar-nav">
					<li><a href="#" onclick="viewForm()">Lue</a></li>
					<li><a href="#" onclick="inputForm()">Syötä</a></li>
					<li><a href="#" onclick="editForm()">Muokkaa</a></li>
					<li><a href="#" onclick="deleteForm()">Poista</a></li>
					<li><a href="./hem.db">Lataa tietokanta</a></li>
				</ul>
				<form class="navbar-form navbar-right" id="search" method="post" action="?i=">
					<input type="text" name="search">	
					<select name="searchOption">
					<option value="otsikko">Otsikko</option>
					<option value="id">ID</option>
					<option value="alaotsikko">Alaotsikko</option>
					<option value="tagit">Tagit</option>
					</select>
					<input style="width: 20%" name="searchSubmit" type="submit" value="Hae">
				</form>
			</div>
		</div>
	</nav>	

	<form id="frontForm">
		<h1>Artikkelit</h1>
		<?php
		while($frontPageRow = $frontPageResults->fetchArray())
		{
			echo "<a href='?i=".$frontPageRow['id']."' onclick='viewForm();'>".$frontPageRow['otsikko']."</a><br>";
		}

		?>
	</form>


	
	<form id="viewForm">
		<h1>Lue</h1>
		<p class="head">Otsikko:</p> <input type="text" value="<?php echo $row['otsikko']?>"/> <br>
		<p class="head">ID:</p> <input type="text" value="<?php echo $row['id']?>"/> <br>
		<p class="head">Alaotsikko:</p> <input type="text" value="<?php echo $row['alaotsikko']?>"/> <br>
		<p class="head">Tarkistuspäivämäärä:</p> <input type="text" value="<?php echo $row['tarkistuspvm']?>"/> <br>
		<p class="head">Ajallinen pituus:</p> <input type="text" value="<?php echo $row['pituusAika']?>"/> <br>
		<p class="head">Filmirullan pituus:</p> <input type="text" value="<?php echo $row['pituusMetri']?>"/> <br>
		<p class="head">Kuvaajat:</p> <input type="text" value="<?php echo $row['kuvaajat']?>"/> <br>
		<p class="head">Leikkaajat:</p> <input type="text" value="<?php echo $row['leikkaajat']?>"/> <br>
		<p class="head">Käsikirjoitus:</p> <input type="text" value="<?php echo $row['ksikirjoitus']?>"/> <br>
		<p class="head">Suunnittelu:</p> <input type="text" value="<?php echo $row['suunnittelu']?>"/> <br>
		<p class="head">Tagit:</p> <input type="text" value="<?php echo $row['tagit']?>"/> <br>
		<p class="head">Selostus:</p> <textarea id="selostus"> <?php echo $row['selostus']?></textarea> <br>
	</form>

	<form id="inputForm" method="post" action="#">
		<h1>Syötä</h1>
		<p class="head">Otsikko:</p> <input type="text" name="otsikko"/> <br>
		<p class="head">ID:</p> <input type="text" name="id"/> <br>
		<p class="head">Alaotsikko:</p> <input type="text" name="alaotsikko"/> <br>
		<p class="head">Tarkistuspäivämäärä:</p> <input type="text" name="tarkistuspvm"/> <br>
		<p class="head">Ajallinen pituus:</p> <input type="text" name="pituusAika"/> <br>
		<p class="head">Filmirullan pituus:</p> <input type="text" name="pituusMetri"/> <br>
		<p class="head">Kuvaajat:</p> <input type="text" name="kuvaajat"/> <br>
		<p class="head">Leikkaajat:</p> <input type="text" name="leikkaajat"/> <br>
		<p class="head">Käsikirjoitus:</p> <input type="text" name="ksikirjoitus"/> <br>
		<p class="head">Suunnittelu:</p> <input type="text" name="suunnittelu"/> <br>
		<p class="head">Tagit:</p> <input type="text" name="tagit"/> <br>
		<p class="head">Selostus:</p> <textarea id="inputText" name="selostus" cols="40" rows"4"></textarea> <br>
		<input type="submit" name="inputSubmit" value="Syötä">
	</form>	

	<form id="editForm" method="post" action="#">
	
	</form>

	<form id="deleteForm" method="post" action="#">
		<h1>Poista</h1>
		<p>Poistettavan uutisfilmin id: <input type="text" name="id">
		<input type="submit" name="deleteSubmit" value="Poista">
	</form>

	<script>
	window.onload = showViewForm();
	function showViewForm()
	{
		var phpGetVar = "<?php Print($queryString); ?>";
		if(phpGetVar > -1)
		{
			viewForm();
		}
	}

	function frontForm()
	{
		document.getElementById("frontForm").style.display = "block";
		document.getElementById("inputForm").style.display = "none";
		document.getElementById("viewForm").style.display = "none";
		document.getElementById("deleteForm").style.display = "none";
		document.getElementById("search").style.display = "none";
	}

	function inputForm()
	{
		document.getElementById("frontForm").style.display = "none";
		document.getElementById("inputForm").style.display = "block";
		document.getElementById("viewForm").style.display = "none";
		document.getElementById("deleteForm").style.display = "none";
		document.getElementById("search").style.display = "none";
	}

	function viewForm()
	{
		document.getElementById("frontForm").style.display = "none";
		document.getElementById("inputForm").style.display = "none";
		document.getElementById("viewForm").style.display = "block";
		document.getElementById("deleteForm").style.display = "none";
		document.getElementById("search").style.display = "block";
	}

	function deleteForm()
	{
		document.getElementById("frontForm").style.display = "none";
		document.getElementById("deleteForm").style.display = "block";
		document.getElementById("inputForm").style.display = "none";
		document.getElementById("viewForm").style.display = "none";
		document.getElementById("search").style.display = "none";
	}
	</script>
</div>
</body>

</html> 
