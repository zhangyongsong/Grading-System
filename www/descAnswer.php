<?php
	require_once('userFuncts.php');
	// connect to mysql database using root account
	$conn = dbConnect();
	session_start();

	// Header 
	doHtmlHeader('Details of Answer');
	doHtmlMenu();
	
	if(isset($_SESSION['valid_user']) && isset($_SESSION['priv'])){
		if(isset($_GET['aid'])){
			$aid = $_GET['aid'];
			$aid = escapeString($conn, $aid);
			showAnswer($conn, $aid);
			if ($_SESSION['priv'] == 'instructor')
				provideInstruction($conn, $aid);
		}
		else{
			echo 'Error occurred. No Answer Selected!';
		}
	}
	else{
		echo 'Please log in again!';
		gotoUrl('login.php');
		exit;
	}
	doHtmlFooter();
	$conn->close();
?>

<?php
	function showAnswer($conn, $anid){
		$aQuery = "SELECT problem.*, answer.* FROM ".
			" problem INNER JOIN answer ON problem.p_id = answer.p_id where answer.an_id =".$anid;
		
		$aResult = $conn->query($aQuery);
		if($aResult->num_rows){
			$aRow = $aResult->fetch_assoc();
			echo "<table class='Result'><tr><th>Tutorial<th>Problem<th>Result<th>Comment</tr>";
			echo '<tr><td>'.$aRow['tutorial'].'<td>'.$aRow['p_num'].' '.$aRow['p_name'].'<td>'.$aRow['status']."<td><pre>"
				.htmlspecialchars($aRow['user_comments']).'</pre></tr></table>';
			
			if ($_SESSION['priv'] == 'student')
				$username = $_SESSION['valid_user'];
			else 
				$username = $aRow['username'];
			$path = $_SERVER['DOCUMENT_ROOT']."/../gradingsystem/uploads/".$aRow['course_code'].'/'
				.$aRow['p_name']."/$username/".$aRow['filename'];
			showSourceFile($path);
			
			if($aRow['instruction']!=''){
				echo "<h3>Instruction by tutor:</h3>";
				echo '<pre class="instruction">'.htmlspecialchars($aRow['instruction']).'</pre>';
			}
			
		}
		else{
			echo 'Error occured. No answer found.';
		}
		
		@ $aResult->free();
	}
	
	function showSourceFile($path){
		if(file_exists($path)){
			$contents=file_get_contents($path);	
?>			<h3>Source Code:</h3>
			<form>
			<textarea name="code" id="code" class="java" rows="15" cols="30">
<?php
			echo htmlspecialchars($contents);
			echo '</textarea></form>';	
		}
		else
		{
			echo "Target source file not found!";
		}
	}
?>
<?php
function provideInstruction($conn, $aid)
{
?>
	<form method="post">
	<textarea name="instruction" cols="40" rows="7"></textarea>
	<input type="submit" value="Submit" />
	</form>
<?php
	if (isset($_POST['instruction']))
	{
		$instruction = escapeString($conn, $_POST['instruction']);
		$result = dbQuery($conn, "UPDATE answer SET instruction = '$instruction' WHERE an_id = $aid");
		if (!$result)
		{
			$conn->close();
			alertGoback('Error!');
			gotoUrl('index.php');
			exit;
		}
		echo "<script>window.location.href=window.location.href;</script>";
	}
}

?>
