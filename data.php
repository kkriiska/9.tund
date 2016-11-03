<?php
	//et saada ligi sessioonile
	require("function.php");
	
	//ei ole sisseloginud, suunan login lehele
	if (!isset ($_SESSION["userId"])) {
		//header ("Location: 5tund.php");
		//exit();
	}
	
	//kas kasutaja tahab valja logida
	//kas aadressireal on logaout olemas
	if (isset ($_GET["logout"])) {
		session_destroy();
		header("Location: 5tund.php");
		exit();
	}
	if (	isset($_POST["note"]) && 
			isset($_POST["color"]) && 
			!empty($_POST["note"]) && 
			!empty($_POST["color"]) 
	) {
		
		$note = cleanInput($_POST["note"]);
		saveNote($note, $_POST["color"]);
		
	}
	$notes = getAllNotes();
	
	echo "<pre>";
	//var_dump($notes);
	echo "</pre>";
?>

<h1>Data</h1>
<p>
	Tere tulemast <a href="user.php"><? = $_SESSION["userUsername"];?>!
	<a href = "?logout = 1">Logi valja</a>
</p>
<h2>M�rkmed</h2>
<form method="POST">
			
	<textarea name="note" rows="5" cols="25"></textarea>

	<br><br>
			
	<input placeholder="Vali v�rv" name="color" type="color">
			
			
	<br><br>
	
	<input type="submit" value="Salvesta">

</form>

<h2>arhiiv</h2>


<?php 
	//iga liikme kohta massiivis
	foreach ($notes as $n) {
		
		$style = "width:200px; float:left; min-height:100px; border: 1px solid gray; background-color: ".$n->noteColor.";";
		
		echo "<p style='  ".$style."  '>".$n->note."</p>";
	}
?>


<h2 style="clear:both;">Tabel</h2>
<?php 
	$html = "<table>";
		
		$html .= "<tr>";
			$html .= "<th>id</th>";
			$html .= "<th>M�rkus</th>";
			$html .= "<th>V�rv</th>";
		$html .= "</tr>";
		
	foreach ($notes as $note) {
		$html .= "<tr>";
			$html .= "<td>".$note->id."</td>";
			$html .= "<td>".$note->note."</td>";
			$html .= "<td>".$note->noteColor."</td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;
?>


