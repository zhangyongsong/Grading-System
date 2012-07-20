<?php
	require_once('userFuncts.php');
	require_once('deleteFolder.php');
	session_start();
	doHtmlHeader('Delete Problem');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayRemoveProblem();
	doHtmlFooter();
?>
<?php
function displayRemoveProblem()
{
	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
	echo "<h2>Delete Problem</h2>";
	if (!isset($_GET['pid']) || !is_numeric($_GET['pid']))
	{
		gotoUrl('problem.php');
		exit;
	}
	$problem = $_GET['pid'];
	$conn = dbConnect();
	
	$pDelete = "DELETE FROM problem WHERE p_id = $problem";
	$instruName = $_SESSION['valid_user'];
	$result = dbQuery($conn, "SELECT problem.course_code, problem.tutorial, problem.p_name FROM problem INNER JOIN enrollment ON problem.course_code = 			enrollment.course_code WHERE enrollment.username = '$instruName' AND problem.p_id = $problem");
	//$result = dbQuery($conn, "SELECT course_code, tutorial, p_name FROM problem WHERE p_id = $problem");
	if (!$result || $result->num_rows != 1)
	{
		echo '<h4>Error: The target problem is not found.</h4>';
		exit;
	}
	$row = $result->fetch_assoc();
	$coursecode = $row['course_code'];
	$pName = $row['p_name'];
	$tutorial = $row['tutorial'];
	$out = 'Problem <b>'.$pName.'</b>in <b>'.$coursecode.', Tutorial '.$tutorial.'</b> is deleted';
	$result->free();

	$dir = "$DOCUMENT_ROOT/../gradingsystem/uploads/$coursecode/$pName";
	if(file_exists($dir))
		deldir($dir);

	dbQuery($conn, $pDelete);
	echo $out.'<br/>';
	echo "<br/><a href='problem.php'>Problem details</a>";
	
	$conn->close();
	doHtmlFooter();
}
?>
