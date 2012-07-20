<?php
	require_once('userFuncts.php');
	// connect to mysql database using root account
	$conn = dbConnect();
	session_start();

	// Header 
	doHtmlHeader(getXmlValue('/GradingSystem/page/answerTitle'));
	doHtmlMenu();

	if(isset($_SESSION['valid_user']) && isset($_SESSION['priv'])){
		$username=$_SESSION['valid_user'];
		echo "<p class='Title'>Running Result for Answers</p>";
		if(isset($_GET['pid'])){
			$pid = $_GET['pid'];
			// if(!get_magic_quotes_gpc()){
				// $pid = mysql_real_escape_string($pid);
			// }
			listAnswer($conn, $username, $pid);
		}
		else{
			listAll($conn, $username);
		}
	}
	else{
		echo 'Please log in again!';
		gotoUrl('login.php');
		exit;
	}
	doHtmlfooter();
	$conn->close();
?>
<?php
	function listAnswer($conn, $username, $pid){
		$query = "SELECT problem.p_num, problem.p_name, answer.* FROM problem INNER JOIN answer ON problem.p_id = answer.p_id "
			."WHERE answer.username ='$username' and answer.p_id = $pid order by problem.p_id, answer.an_id DESC";
		$result = $conn->query($query);
?>	<table class="Result"> 
		<tr><th>Problem<th>Status<th>Result<th>Last Uploaded<th>details</tr>
<?php	
		for ($i =0; $i< $result->num_rows; $i++){
			$answer = $result->fetch_assoc();
			$b='';
			if($answer['isLatest']=='true'){
				$b='<strong>';
			}	
			echo '<tr>';
			echo '<td>'.$b.$answer['p_num'].' '.$answer['p_name'].'<td>'.$b.$answer['ex_status'].'<td>'.$b.interpretStatus($answer['status']).'<td>'
					.$b.$answer['upload_time'];	
			echo '<td>'.$b;
			doHtmlUrl("descAnswer.php?aid=".$answer['an_id'],'Details');
			echo '</tr>';

		}
		echo '</table>';
	}
	
	function listAll($conn, $username){
		$query = "SELECT problem.*, answer.* FROM problem INNER JOIN answer ON "
		."problem.p_id = answer.p_id WHERE answer.username ='$username' order by problem.course_code, problem.p_id, answer.an_id DESC";
		$result = $conn->query($query);
		$prev_course='';
		echo "<ul id='subHeading'>";
		if($result->num_rows==0)
			echo 'Sorry, no answer is provided by now.';
		for ($i =0; $i< $result->num_rows; $i++){
			$answer = $result->fetch_assoc();
			$curr_course = $answer['course_code'];
			if($curr_course!=$prev_course){
				if($prev_course!='')
					echo "</table>";
				echo "<li>$curr_course</li>";
				echo "<table class='Result'><tr><th>Tutorial<th>Problem<th>Status<th>Result<th>Last Uploaded<th>details</tr>";
			}
			$prev_course=$curr_course;
			echo '<tr><td>';
			echo $answer['tutorial'].'<td>'.$answer['p_num'].' '.$answer['p_name'].'<td>'.$answer['ex_status'].'<td>'.interpretStatus($answer['status']).'<td>'
				.$answer['upload_time'];
			echo '<td>';
			doHtmlUrl("descAnswer.php?aid=".$answer['an_id'],'Details');
			echo '</tr>';
			
			if($i==$result->num_rows -1)
				echo "</table>";
		}
		echo '</ul>';
	}
?>
