<?php
	//edit.php
	require("function.php");
	require("editFunction.php");
	
	//kas aadressireal on delete
	id(isset($_GET["delete"])) {
		deleteNote($_GET["id"])
		header("Location: data.php");
		exit();
	
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		updateNote(cleanInput($_POST["id"]), cleanInput($_POST["note"]), cleanInput($_POST["color"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	$c = getSingleNoteData($_GET["id"]);
	var_dump($c);
	
?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="note" >Markus</label><br>
	<textarea  id="note" name="note"><?php echo $c->note;?></textarea><br>
  	<label for="color" >värv</label><br>
	<input id="color" name="color" type="color" value="<?=$c->color;?>"><br><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
  <br>
  <br>
  <a href='?id=<?=$_GET["id"];?>&delete=true">kustuta</a>