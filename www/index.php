
<?php
	require_once('userFuncts.php');
	// connect to mysql database using root account
	$conn = dbConnect();	
	session_start();
	if(isset($_SESSION['valid_user']) &&isset($_SESSION['priv']))
	{
		$username = $_SESSION['valid_user'];
		$priv = $_SESSION['priv'];
	}
	elseif(isset($_POST['username'])&&isset($_POST['password'])){
		$username = strtolower($_POST['username']);
		$password = $_POST['password'];
		$domain = $_POST['domain'];
		
		userValidation($conn, $username, $password, $domain);	
		$priv = $_SESSION['priv'];
	}
	else {
		gotoUrl('login.php');
		exit;
	}
	

	// Header 
	doHtmlHeader(getXmlValue('/GradingSystem/page/mainTitle'));
	doHtmlMenu();
?>
	<h2 class="welcome">Welcome to Grading System!</h2>
<?php
	if($priv!='administrator')
		$courseQuery = "SELECT course.course_code, course.course_name"
		." from course inner join enrollment on course.course_code = enrollment.course_code"
		." where enrollment.username ='$username'";
	else
		$courseQuery = 'select * from course';
		
	$courseResult = $conn->query($courseQuery);
	for($i=0; $i< ($courseResult->num_rows); $i++){
		$row = $courseResult->fetch_assoc();
		$courseList[$i]=$row['course_code'].' '.$row['course_name'];
	}
	@ $courseResult->free();
?>	
	
	<table id="info"><tr><td>Username:<td><?php echo $username; ?>
		   <tr><td>Privilege:<td><?php echo $priv; ?>
		   <tr><td>Courses:<td><ul>
<?php
	if(isset($courseList)){
		foreach ($courseList as $course)
			echo '<li>'.$course;
	}
	else echo '<li>No course!';
	echo '</ul></table>';
	doHtmlfooter();
	$conn->close();
	//for Student, redirect into Problem page.
	if(isset($_POST['username']) && $priv == 'student'){
		gotoUrl('addAnswer.php');
		exit;
	}
		
function userValidation($conn, $username, $password, $domain){
	$query = 'select * from user '.
			 " where username = '$username' ".
			" and password = sha1('$password')";

	$result = $conn->query($query);
	if($result->num_rows)
	{
		$row = $result->fetch_assoc();
		$priv = $row['privilege'];
		$result->free();
		if(domainValue($priv) < domainValue($domain)){
			alertGoback('You are not authorized to log in. Please try again.');
			gotoUrl('login.php');
			exit;
		}
		$_SESSION['valid_user'] = $username;
		$_SESSION['priv']= $domain;
	}
	else
	{
		$result->free();		
		alertGoback('We cannot log you in. Please try again.');
		gotoUrl('login.php');
		exit;
	}
}

function domainValue($domain){
	switch($domain){
		case 'student':
			$value =1;
			break;
		case 'instructor':
			$value =2;
			break;
		case 'administrator':
			$value =3;
			break;
		default: $value =0;
	}
	return $value;
}

?>
