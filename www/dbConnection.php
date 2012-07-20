<?php
	require_once('utilityFunctions.php');

	function dbConnect()
	{
		$db = getXmlValue('/GradingSystem/database/dbName');
		$dbLocation = getXmlAttribute('/GradingSystem/database', 'addr');
		$user = getXmlValue('/GradingSystem/database/user');
		$password = getXmlAttribute('/GradingSystem/database/user', 'password');
		$result = new mysqli($dbLocation, $user, $password, $db);
		if(!$result)
		{
			alertGoback('Failed to connect to database server');
			goBack();
			exit;
		}
		else
			return $result;
	}
	
	function dbQuery($conn, $query){
		$result = $conn->query($query);
		return $result;
	}
?>