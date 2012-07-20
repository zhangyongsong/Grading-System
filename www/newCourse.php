<?php 
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	
	//connect db
	$conn=dbConnect();
	
	//create short var names
	if(isset($_POST['coursecode']) && isset($_POST['coursename']))
	{
		$coursecode=escapeString($conn, $_POST['coursecode']);
		$coursename=escapeString($conn, $_POST['coursename']);
	}
	else
	{
		gotoUrl('addCourse.php');
		exit;
	}
	$query = "INSERT INTO course (course_code, course_name) VALUES ('$coursecode', '$coursename')";
	$result=$conn->query($query);
	if ($result)
	{
		echo '<h2>Add Course</h2>';
		echo 'The new course '. htmlspecialchars($coursecode).' '. htmlspecialchars(stripslashes($coursename)).' has been added successfully.';
		echo "<br/><a href='course.php'>See Course Details</a>";
	}	
	else 
	{
		alertGoback('An error occurred in database!');
		gotoUrl('addCourse.php');
		exit;
	}
	doHtmlFooter();	
?>
