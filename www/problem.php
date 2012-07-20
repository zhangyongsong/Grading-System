<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Problem');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	
	$conn = dbConnect();

	echo '<h2>Problem</h2>';

	displayCourseTutorial($conn);
?>
	<br/><br/><a href="addProblem.php">Add new problems</a><br/>
<?php	
	$conn->close();
	doHtmlFooter();



function displayCourseTutorial($conn){
	echo '<div>';
	$instruName = $_SESSION['valid_user'];
	$query="SELECT distinct course_code FROM enrollment WHERE username = '$instruName' ORDER BY course_code ASC";
	$cResult=$conn->query($query);
	$cNumber = $cResult->num_rows;
	if($cNumber == 0){
            echo '<br/>';
	    echo('Sorry, no courses set yet!');	
	    echo '<br/>';
	    @ $cResult->free();
	    exit;
	}
?>	
	<form method="POST" id="selectCourse">
	<label for="course">Course:</label>
<?php
	if($cNumber ==1){
		$row = $cResult->fetch_assoc();
		$course =$row['course_code'];
		echo $course.'<br/>';
	}
	else{
?>		
		<select id="course" name="course" onchange="this.form.submit()">
			<option value="0" >-- select course --</option>
<?php
		for($i=0; $i<$cNumber; $i++)
		{
			$row=$cResult->fetch_assoc();
?>
			<option id="<?php echo $row['course_code'];?>" value="<?php echo $row['course_code'];?>"><?php echo $row['course_code'];?></option>
<?php
		}
?>
		</select><br/>
<?php 
	}

	@ $cResult->free();
	
	if(isset($_POST['course']) && $_POST['course']!= '0'){
			$course = $_POST['course'];
			echo "<script type='text/javascript'>document.getElementById('$course').selected='true';</script>";
	}
	if(isset($course)){
?>		<br/>
		<label for="tutorial">Tutorial:</label>
		<select id="tutorial" name="tutorial" onchange="this.form.submit()">
			<option value="0" >--select tutorial--</option>
<?php
		$query="SELECT DISTINCT tutorial FROM problem WHERE course_code = '$course' ORDER BY tutorial ASC";
		$tResult=$conn->query($query);
		$tNumber = $tResult->num_rows;

		for($i=0; $i<$tNumber; $i++)
		{
			$row=$tResult->fetch_assoc();
?>
			<option id="tutorial<?php echo $row['tutorial'];?>" value="<?php echo $row['tutorial'];?>">Tutorial <?php echo $row['tutorial'];?></option>
<?php
		}
		echo '</select>';
		@ $tResult->free();

		if(isset($_POST['tutorial']) && $_POST['tutorial']!= '0'){
				$tutorial = $_POST['tutorial'];
				echo "<script type='text/javascript'>document.getElementById('tutorial$tutorial').selected='true';</script>";
		}
		
		if (isset($tutorial)){
			displayProblems($conn, $course, $tutorial);
		}
	}
	echo '</form></div>';
}


function displayProblems($conn, $course, $tutorial)
{
	echo "<br/>";
	//echo $course.'<br>'.$tutorial;

	$result = dbQuery($conn, "SELECT p_id, p_num, p_name FROM problem where course_code = '$course' and tutorial = $tutorial");
	if(!$result)
	{
		$conn->close();
		alertGoback('No Problem is Created in this Course.');
		gotoUrl('problem.php');
		exit;
	}
	$num = $result->num_rows;
	if($num == 0)
		echo "<h3>No problem is created in this course.</h3><br/>";
	else
	{
?>
		<table class="result">
		<th>Title</th><th>Details</th><th>Action</th><th>Test Cases</th></tr>
<?php
		for($i=0; $i<$num; $i++)
		{
			$row=$result->fetch_assoc();
			echo '<td>'.$row['p_num'].' '.$row['p_name'].'</td>';
			echo "<td><a href='descProblem.php?pid=".$row['p_id']."' target='_blank'>Details</a></td>";
			echo "<td><a href='editProblem.php?pid=".$row['p_id']."'>Edit</a> <a href='removeProblem.php?pid=".$row['p_id']."'>Delete</a></td>";
			echo "<td><a href='testCase.php?pid=".$row['p_id']."' target=\"_blank\">Details</a></td></tr>";
		}
?>
		</table>
<?php
	}
}
?>


<?php
/*

function getCourseArr($conn, &$courseArr)
{
	$instruName=$_SESSION['valid_user'];
	$query="SELECT course_code FROM enrollment WHERE username = '$instruName' ORDER BY course_code ASC";
	$courseRes=$conn->query($query);
	if (!$courseRes)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('problem.php');
		exit;
	}
	$courseCount = $courseRes->num_rows;
	for ($i = 0; $i < $courseCount; $i++)
	{
		$row = $courseRes->fetch_assoc();
		$courseArr[$i] = $row['course_code'];
	}
	@$courseRes->free();
}

function displayTutProblems($conn, $course)
{
	$courseArr = array();
	$tutorialArr = array();
	getCourseArr($conn, $courseArr);
	if (!in_array($course, $courseArr))
	{
		echo '<h3>Error: The course code is wrong.</h3>';
		exit;
	}
	echo "<h3>Course: $course</h3>";
	getCourseTutArr($conn, $course, $tutorialArr);
	$tutCount = count($tutorialArr);
	if ($tutCount == 0)
		echo '<h4>No Tutorial is created in this course</h4>';
?>
	<form method="get">
	<input type="hidden" name="course" value="<?php echo $course; ?>" />
	Select a tutorial: 
	<select name="tutorial" onchange="this.form.submit()">
	<option value = "">--Select Tutorial--</option>
<?php
	for($i = 0; $i < $tutCount; $i++)
	{
?>
		<option value="<?php echo $tutorialArr[$i]; ?>">Tutorial <?php echo $tutorialArr[$i]; ?></option>
<?php
	}
?>
	</select>
	</form>
<?php
}




function displayCourseTutProblems($conn)
{
	$courseArr = array();
	getCourseArr($conn, $courseArr);
?>
	<form method="get">
	Please select a course to start: 
	<select name = "course" onchange="this.form.submit()">
	<option value="">--Select Course--</option>
<?php
	for ($i = 0; $i < count($courseArr); $i++)
	{
?>	
		<option value="<?php echo $courseArr[$i]; ?>"><?php echo $courseArr[$i]; ?></option>
<?php
	}
?>
	</select>
	</form>
<?php
}


function getCourseTutArr($conn, $course, &$tutorialArr)
{
	$result = dbQuery($conn, "SELECT DISTINCT tutorial FROM problem WHERE course_code = '$course' ORDER BY tutorial ASC");
	if(!$result)
	{
		$conn->close();
		alertGoback('No Tutorial is Created in this Course.');
		gotoUrl('problem.php');
		exit;
	}
	$tutorialCount = $result->num_rows;
	for ($i = 0; $i < $tutorialCount; $i++)
	{
		$row = $result->fetch_assoc();
		$tutorialArr[$i] = $row['tutorial'];
	}
	@$result->free();
}

*/

?>
