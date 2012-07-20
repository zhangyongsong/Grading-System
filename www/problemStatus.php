<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Problem Status');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	echo "<h2>Problem Status</h2>";
	if (!isset($_GET['pid']))
		displayStatistics();
	if (isset($_GET['pid']))
		displayProblemStatistics();
	doHtmlFooter();
?>
<?php
function displayStatistics()
{
	$conn=dbConnect();
	$instruName = $_SESSION['valid_user'];
	//query for instructor's course
	$query="SELECT course_code FROM enrollment WHERE username = '$instruName' ORDER BY course_code ASC";
	$courseRes=$conn->query($query);
	if (!$courseRes)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$courseCount = $courseRes->num_rows;
	if ($courseCount == 0)
		echo "No course is registered.<br/>";
	else
	{
?>
		<form method="post">
		Please select a course to start:
		<select name="course" onchange="this.form.submit()">
		<option value="">--Select Course--</option>
<?php
		for ($i = 0; $i < $courseCount; $i++)
		{
			$row = $courseRes->fetch_assoc();
?>
			<option id="<?php echo $row['course_code'];?>" value="<?php echo $row['course_code'];?>" ><?php echo $row['course_code'];?><br/>
<?php
		}
		$courseRes->free();
?>
		</select><br/><br/>
<?php
		if (isset($_POST['course']) && $_POST['course'] != "")
		{
			$course = $_POST['course'];
			echo "<script type='text/javascript'>document.getElementById('$course').selected=true</script>";
			
			$query = "select distinct problem.p_name, p_num, problem.tutorial, problem.p_id, count(distinct username) as attemptNum, 
					count(answer.username) as submissionNum, sum(case when isLatest='true' and status='Yes' then 1 else 0 end) as solveNum
					FROM answer inner join problem on answer.p_id=problem.p_id where problem.course_code = '$course' group by answer.p_id ORDER BY 						problem.tutorial ASC;";
			$result = dbQuery($conn, $query);
			if (!$result)
			{
				$conn->close();
				alertGoback('Error!');
				gotoUrl('index.php');
				exit;
			}
			$num = $result->num_rows;
			if ($num == 0)
				echo 'No problem status is available. <br/>';
			else
			{
?>
				<table class="result">
				<tr>
					<th>Tutorial</th>
					<th>Problem Name</th>
					<th>No. of User Attempted</th>
					<th>No. of User Solved</th>
					<th>No. of Total Submission</th>
					<th>Details</th>
				</tr>
<?php
				for($i = 0; $i < $num; $i++)
				{
					$row = $result->fetch_assoc();
?>
				<tr>
					<td><?php echo $row['tutorial'];?></td>
					<td><a href="descProblem.php?pid=<?php echo $row['p_id'];?>"><?php echo $row['p_num'].' '.htmlspecialchars($row['p_name']);?></td>
					<td><?php echo $row['attemptNum'];?></td>
					<td><?php echo $row['solveNum'];?></td>
					<td><?php echo $row['submissionNum'];?></td>
					<td><a href="problemStatus.php?pid=<?php echo $row['p_id'];?>">Details</a></td>
				</tr>
<?php
				}
				echo '</table>';
				$result->free();
			}
		}
		echo '</form>';
	}
	$conn->close();
}
?>
<?php
function displayProblemStatistics()
{
	$conn = dbConnect();
	$pid = escapeString($conn, $_GET['pid']);
	$result = dbQuery($conn, "SELECT p_name FROM problem WHERE p_id = $pid");
	if (!$result)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$row = $result->fetch_assoc();
	$result->free();
	echo 'Problem: <b>'.$row['p_name'].'</b><br/>';
	$result = dbQuery($conn, "SELECT username, no_attempts, status, upload_time FROM answer WHERE p_id = $pid AND isLatest = 'true'");
	if (!$result)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$num = $result->num_rows;
	if ($num == 0)
		echo 'No user has tried this problem.<br/>';
	else
	{
?>
		<table class="result">
		<tr>
			<th>Attempted User</th>
			<th>Status</th>
			<th>No. of Submission</th>
			<th>Last Submission Time</th>
			<th>User Details</th>
		</tr>
<?php
		for($i = 0; $i < $num; $i++)
		{
			$row = $result->fetch_assoc();
?>
			<tr>
				<td><?php echo htmlspecialchars($row['username']);?></td>
				<td><?php echo interpretStatus($row['status']);?></td>
				<td><?php echo $row['no_attempts'];?></td>
				<td><?php echo $row['upload_time'];?></td>
				<td><a href="userStatus.php?pid=<?php echo $pid;?>&username=<?php echo $row['username'];?>">Details</a></td>
			</tr>
<?php
		}
		echo '</table>';
		$result->free();
	
	}
	$conn->close();
}
?>
