<script language="javascript"> 
function sel(name)
{ 
	var o=document.getElementsByName(name);
	for(i=0;i<o.length;i++) 
		o[i].checked=event.srcElement.checked;
} 
</script> 
<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('View Registered User/De-register User');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	
	displayUserDeregistration();
	doHtmlFooter();
?>
<?php
function displayUserDeregistration()
{
	echo "<h2>View Registered User/De-register User</h2>";
?>
	<form action="removeUser.php" method="post">
	<h3>Administrator</h3>
<?php
	$conn=dbConnect();
	//admin
	$query="SELECT username FROM user WHERE privilege = 'administrator' AND username != '".$_SESSION['valid_user']."'";
	$result=$conn->query($query);
	if (!$result)
	{
		$conn->close();
		alertGoback('Error.');
		gotoUrl('index.php');
		exit;
	}
	$adminCount = $result->num_rows;
	if ($adminCount == 0)
		echo 'Only one administrator is registered.<br/>';
	else
	{
		for($i=0; $i<$adminCount; $i++)
		{
			$row = $result->fetch_assoc();
			echo "<input type=\"checkbox\" name=\"administrator[]\" value=\"".$row['username']."\" />".$row['username']."<br/>";
		}
		$result->free();
?>
		<br/>Select all<input type="checkbox" onclick="sel('administrator[]')"> <br/>
<?php
	}
	
	//divide instructor and user by course
	$query = "SELECT course_code FROM course";
	$result = $conn->query($query);
	if (!$result)
	{
		$conn->close();
		alertGoback('Error.');
		gotoUrl('index.php');
		exit;
	}
	$num = $result->num_rows;
	if ($num == 0)
		echo 'No course is registered. <br/>';
	else 
	{
		for ($i = 0; $i < $num; $i++)
		{
			$row = $result->fetch_assoc();
			echo "<hr><h3>".$row['course_code']."</h3>";
			echo '<h4>Instructor</h4>';
			$query = "SELECT user.username FROM user INNER JOIN enrollment ON user.username = enrollment.username 
					WHERE privilege = 'instructor' AND course_code='".$row['course_code']."'";
			$instruRes = $conn->query($query);
			if(!$instruRes)
			{
				$conn->close();
				alertGoback('Error.');
				gotoUrl('index.php');
				exit;
			}
			$instruCount = $instruRes->num_rows;
			if ($instruCount == 0)
				echo 'No instructor is registered in this course<br/>';
			else
			{
				for ($j = 0; $j < $instruCount; $j++)
				{
					$instruRow = $instruRes->fetch_assoc();
					echo "<input type=\"checkbox\" name=\"instru".$row['course_code']."[]\" value=\"".$instruRow['username']."\" />".$instruRow['username']."<br/>";
				}
				$instruRes->free();
?>
				<br/>Select all<input type="checkbox" onclick="sel('instru<?php echo $row['course_code'];?>[]')"> <br/>
<?php
			}
			echo '<h4>User</h4>';
			$query = "SELECT user.username FROM user INNER JOIN enrollment ON user.username = enrollment.username 
					WHERE privilege = 'student' AND course_code='".$row['course_code']."'";
			$userRes = $conn->query($query);
			if(!$userRes)
			{
				$conn->close();
				alertGoback('Error.');
				gotoUrl('index.php');
				exit;
			}
			$userCount = $userRes->num_rows;
			if ($userCount == 0)
				echo 'No user is registered in this course<br/>';
			else
			{
				for ($j = 0; $j < $userCount; $j++)
				{
					$userRow = $userRes->fetch_assoc();
					echo "<input type=\"checkbox\" name=\"user".$row['course_code']."[]\"  value=\"".$userRow['username']."\" />".$userRow['username']."<br/>";
				}
				$userRes->free();
?>
				<br/>Select all<input type="checkbox" onclick="sel('user<?php echo $row['course_code'];?>[]')"> <br/>
<?php
			}
		}
	}
	$conn->close();
?>
	<br/><br/><input type="submit" value="De-register">&nbsp&nbsp&nbsp&nbsp
	<input type="reset" value="Reset">
	</form>
<?php
}
?>
