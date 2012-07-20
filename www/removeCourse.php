<?php
	require_once('userFuncts.php');
	require_once('deleteFolder.php');
	session_start();
	doHtmlHeader('Course De-registration');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
	
	echo "<h2>Course De-registration</h2><br/>";
	
	if (!isset($_GET['course']))
	{
		gotoUrl('course.php');
		exit;
	}
	$conn = dbConnect();
	$course = addSlashes(rawurldecode($_GET['course']));
	echo $course;
	$checkEnroll = "SELECT count(username) as userenroll FROM enrollment WHERE username = ''";
	$result = dbQuery($conn, $checkEnroll);
	
	$deleteUser = "DELETE user.* FROM user WHERE user.username NOT IN (SELECT username FROM enrollment) AND user.privilege != 'administrator'";
	$deleteCourse = "DELETE FROM course WHERE course_code = '$course'";

	$dir = "$DOCUMENT_ROOT/../gradingsystem/uploads/$course/";
	if(file_exists($dir))
			deldir($dir);
//echo $deleteUser;
//echo '<br>'.$deleteCourse;
	
	$result = $conn->query($deleteCourse);
	$result = $conn->query($deleteUser);
	echo"Course <b>$course</b> is de-registered.<br/>";
	echo "<br/><a href='course.php'>See Course Details</a>";
	
	$conn->close();
	doHtmlFooter();
?>
