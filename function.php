<?php

	//functions.php
	require("../../config.php");
	
	//et saab kasutada $_SESSION muutujaid
	//koigis failides mis on selle failiga seotud
	session_start();
	
	/* UHENDUS */
	$database = "if16_karokrii";
	$mysqli = new mysqli ($serverHost, $serverUsername, $serverPassword, $database);
	
	
	require("User.class.php");
	$User = new User($mysqli);
	
	

	

	//var_dump($GLOBALS);

	function signup($username, $email, $password, $FirstName, $LastName, $gender) {
	
		$mysqli = new mysqli(
	
		$GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"] );
	
		$stmt = $mysqli->prepare("INSERT INTO user_sample (username, email, password, FirstName, LastName, gender) VALUES (?, ?, ?, ?, ?, ?)");
		echo $mysqli->error;
	
		$stmt->bind_param("ssssss", $username, $email, $password, $FirstName, $LastName, $gender);
	
		if ($stmt->execute()) {
			echo "salvestamine onnestus";
		}else{
			echo "ERROR ".$stmt->error;
		}
	}
	
	function login($username, $password) {
		
		$notice = "";
		
		$mysqli = new mysqli ($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare ("SELECT id, username, password, created FROM user_sample WHERE username = ?");
		
		//asendan?
		$stmt->bind_param ("s", $username);
		
		//maaran muutujad reale mis katte saan
		$stmt->bind_result ($id, $usernameFromDb, $passwordFromDb, $created);
		
		$stmt->execute();
		//ainult SELECTi puhul
		if ($stmt->fetch()) {
			
			//vahemalt uks rida tuli
			//kasutaja sisselogimis parool rasiks
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				//onnestus
				echo "Kasutaja ".$id." logis sisse";
				
				$_SESSION["userId"] = $id;
				$_SESSION["userUsername"] = $usernameFromDb;
				$_SESSION["userEmail"] = $emailFromDb;
				
				header("Location: data.php");
				exit();
				
			}else{
				
				$notice = "Vale parool!";
			}
				
		}else{
			
			//ei leitud uhtegi rida
			$notice = "Sellist emaili ei ole!";
		}
		
		return $notice;
	}
	
	function saveNote ($note, $color) {
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"],  $GLOBALS["serverPassword"],  $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("INSERT INTO colorNotes (note, color) VALUES (?, ?)");
		echo $mysqli->error;
		
		$stmt->bind_param ("ss", $note, $color );
		
		if ($stmt->execute()) {
			echo "salvestamine onnestus";
		}else{
			echo "ERROR ".$stmt->error;
		}
	}
	
	function getAllNotes() {
		$mysqli = new mysqli (GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		
		$stmt = $mysqli->prepare("SELECT id, note, color FROM colorNotes WHERE deleted IS NULL");
		
		$stmt->bind_result("ss", $note, $color);
		$stmt->execute();
		
		$result = array();
		
		//tsukkel tootab seni, kuni saab uue rea AB'i
		//nii mitu korda palju SELECT lausega tuli
		while ($stmt->fetch()) {
			//echo $note."<br>";
			
			$object = new StdClass();
			$object->id = $id;
			$object->note = $note;
			$object->noteColor = $color;
			
			array_push ($result, $oject);
		}
		
		return $result;
	}
	
	function cleanInput ($input ) {
		
		//"  tere tulemast  "
		$input = trim($input);
		//"tere tulemast"
		
		//"tere \\tulemast"
		$input = striplashes($input);
		//"tere tulemast"
		
		//"<"
		$input = htmlspecialchars($input);
		//"&lt;"
		
		return $input;
	}
	
	function saveInterest ($interest) {
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO interests (interest) VALUES (?)");
	echo $mysqli->error;
	$stmt->bind_param("s", $interest);
	if($stmt->execute()) {
		echo "salvestamine õnnestus";
	} else {
		echo "ERROR ".$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
}
function saveUserInterest ($interest) {
	
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("
		SELECT id FROM user_interests 
		WHERE user_id=? AND interest_id=?
		");
		
	echo $mysqli->error;
	
	$stmt->bind_param("ii", $_SESSION["userId"], $interest);
	$stmt->execute();
	
	//kas oli olemas
	if ($stmt->fetch()) {
		
		//oli olemas, ei salvesta
		echo "Juba olemas";	
		return; //see katkestab funktsiooni, edasi ei loe koodi
	}
	//lähme edasi ja salvestamine
	$stmt->close();
	
	$stmt = $mysqli->prepare("
		INSERT INTO user_interests 
		(user_id, interest_id) VALUES (?, ?)
		");
	echo $mysqli->error;
	$stmt->bind_param("ii", $_SESSION["userId"], $interest);
	if($stmt->execute()) {
		echo "salvestamine õnnestus";
	} else {
		echo "ERROR ".$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
}
	function getAllInterests() {
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("
			SELECT id, interest
			FROM interests 
		");
		echo $mysqli->error;
		$stmt->bind_result($id, $interest);
		$stmt->execute();
		//tekitan massiivi
		$result = array();
		//tee seda seni, kuni on rida andmeid
		//mis vastab select lausele
		while ($stmt->fetch()) {
			//tekitan objekti
			$i = new StdClass();
			$i->id = $id;
			$i->interest = $interest;
			
			array_push($result, $i);
		}
		
		$stmt->close();
		$mysqli->close();
		return $result;
		
	}


//return htmlspecialchars(stripslashes(trim($input)));
	/*function sum($x, $y) {
		$answer = $x+$y;
		
		return $answer;
	}
	
	function hello ($firstname, $lastname) {
		return "Tere tulemast ".$firstname." ".$lastname."!";
	}
	
	echo sum (123456789, 123456789);
	echo "<br>";
	echo sum (1,2);
	echo "<br>";
	echo hello ("Karolin", "K.");
	*/
?>