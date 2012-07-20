<?php
	require_once('userFuncts.php');
	require_once('deleteFolder.php');
	session_start();
	doHtmlHeader('System Reset');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();

	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
	
	echo "<h2>System Reset</h2>";
	@$address = $_SERVER['HTTP_REFERER'];
	
	if (strrpos($address, '/resetSystem.php') > 0)
	{
		$dir = "$DOCUMENT_ROOT/../gradingsystem/uploads/";
		if (file_exists($dir))
		{
			deldir($dir);
			mkdir($dir, 0777, true);
		}
		$conn = dbConnect();
		dbQuery($conn, "DELETE FROM user WHERE privilege ='student'");
		// dbQuery($conn, 'DELETE FROM course');
		// dbQuery($conn, 'DELETE FROM enrollment');
		// dbQuery($conn, 'ALTER TABLE enrollment AUTO_INCREMENT=1');
		// dbQuery($conn, 'DELETE FROM problem');
		// dbQuery($conn, 'ALTER TABLE problem AUTO_INCREMENT=1');
		// dbQuery($conn, 'DELETE FROM testcase');
		// dbQuery($conn, 'ALTER TABLE testcase AUTO_INCREMENT=1');
		dbQuery($conn, 'DELETE FROM answer');
		//dbQuery($conn, 'ALTER TALBE answer AUTO_INCREMENT=1');
	
		echo 'The system has been reset.<br/>';
	}
	else exit;
?>
