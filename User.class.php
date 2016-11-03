<?php 
class User {
	
	private $connection;
	
	//kaivitatakse siis kui new ja see mis saadetakse
	//sulgudesse new User(?) see jouab siia
	function __construct($mysqli){
		
		//this viitab sellele klassile
		//selle klassi muutuja connection
		$this->connection = $mysqli;
	}
	
	/* KLASSI FUNKTSIOONID */
	
	function login($username, $password) {
		
		$notice = "";
		
		$stmt = $this->connection->prepare ("SELECT id, username, password, created FROM user_sample WHERE username = ?");
		
		//asendan?
		$stmt->bind_param ("s", $username);
		
		//maaran muutujad reale mis katte saan
		$stmt->bind_result($id, $usernameFromDb, $passwordFromDb, $created);
		
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
				
				//header("Location: data.php");
				//exit();
				
			}else{
				
				$notice = "Vale parool!";
			}
				
		}else{
			
			//ei leitud uhtegi rida
			$notice = "Sellist emaili ei ole!";
		}
		
		return $notice;
	}
	
	function signup($username, $email, $password, $FirstName, $LastName, $gender) {
	
		$stmt = $this->connection->prepare("INSERT INTO user_sample (username, email, password, FirstName, LastName, gender) VALUES (?, ?, ?, ?, ?, ?)");
		echo "plapla";
		$stmt->bind_param("ssssss", $username, $email, $password, $FirstName, $LastName, $gender);
	
		if ($stmt->execute()) {
			echo "salvestamine onnestus";
		}else{
			echo "ERROR ".$stmt->error;
		}
	}
	
	
	
	
}	
?>