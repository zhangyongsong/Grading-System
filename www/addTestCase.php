<?php
	require_once('userFuncts.php');
	session_start();
	
	doHtmlHeader('Add Test Case');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayTestCaseAdder();
	doHtmlFooter();
?>
<?php
function displayTestCaseAdder()
{
	$getValue = false;
	if (isset($_GET['pid']))
	{
		$pid = $_GET['pid'];
		$getValue = true;
	}
	echo '<h2>Add Test Case</h2>';
	$conn = dbConnect();
	//get coursecode
	$instruName=$_SESSION['valid_user'];
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
		if (!$getValue)
		{
		$encloseForm = false;
?>
		<form method="post">
		Please select a course to start: <select name="course" onchange="this.form.submit()">
		<option value="">--Select Course--</option>
<?php
		for ($i=0; $i<$courseCount; $i++)
		{
			$row = $courseRes->fetch_assoc();
?>
			<option id="<?php echo $row['course_code'];?>" value="<?php echo $row['course_code'];?>"><?php echo $row['course_code'];?></option>
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
			$result = dbQuery($conn, "SELECT DISTINCT tutorial FROM problem WHERE course_code = '$course' ORDER BY tutorial ASC");
			if(!$result)
			{
				$conn->close();
				alertGoback('No Tutorial is Created in this Course.');
				gotoUrl('index.php');
				exit;
			}
			$num = $result->num_rows;
			if($num == 0)
				echo "<h3>No tutorial is created in this course.</h3><br/>";
			else
			{
				echo 'Select a tutorial: ';
?>
				<select name = "tutorial" onchange="this.form.submit()">
				<option value = "">--Select Tutorial--</option>
<?php
				for($i=0; $i<$num; $i++)
				{
					$row=$result->fetch_assoc();
?>
					<option id="<?php echo $row['tutorial'];?>" value="<?php echo $row['tutorial'];?>">Tutorial <?php echo $row['tutorial'];?></option>
<?php
				}

?>
				</select>
<?php
				if(isset($_POST['tutorial']) && $_POST['tutorial'] != "")
				{
					$tutorial = $_POST['tutorial'];
					echo "<script type='text/javascript'>document.getElementById('$tutorial').selected=true</script>";
					
					$result = dbQuery($conn, "SELECT p_id, p_name FROM problem where course_code = '$course' and tutorial = $tutorial");
					if(!$result)
					{
						$conn->close();
						alertGoback('No Problem is Created in this Course.');
						gotoUrl('index.php');
						exit;
					}
					$num = $result->num_rows;
					if($num == 0)
						echo "<h3>No problem is created in this course.</h3><br/>";
					else
					{
						$encloseForm = true;
						?>
						</form>
						<form action="newTestCase.php" method="post">
						Select the Problem to Edit:
						<select name = "problem">
<?php
						for($i=0; $i<$num; $i++)
						{
							$row=$result->fetch_assoc();
?>
							<option value="<?php echo $row['p_id'];?>"><?php echo $row['p_name'];?></option>
<?php
						}
?>
						</select>
<?php
						addTestCaseForm();		
?>
						</form>
<?php
					}
				}
			}
		}
		if (!$encloseForm)
			echo '</form>';
	}
	else
	{
		$result = dbQuery($conn, "select problem.p_name, problem.course_code, tutorial from problem 
			inner join enrollment on problem.course_code = enrollment.course_code 
			where problem.p_id = $pid and username = '$instruName'");
		if (!$result)
		{
			$conn->close();
			alertGoback('Error!');
			gotoUrl('index.php');
			exit;
		}
		$num = $result->num_rows;
		if ($num == 0)
			echo "You cannot add test cases for this problem<br/>";
		else
		{
			$row = $result->fetch_assoc();
			echo '<h4>Course: '.$row['course_code'].'</h4>';
			echo '<h4>Tutorial '.$row['tutorial'].'</h4>';
			echo '<h4>Problem: '.$row['p_name'].'</h4>';
			$result->free();
?>
			<form action="newTestCase.php" method="post">
			<?php addTestCaseForm();?>
			<input type="hidden" name="problem" value="<?php echo $pid;?>" />
			</form>
<?php
		}
	}
	
	}
	@$result->free();
	$conn->close();
}
function addTestCaseForm()
{
?>
<script type="text/javascript"> 
var count = 0; 
function Add()
{ 
	count += 1; 

	var File1 = document.getElementById("file1"); 
	var div = document.createElement("p");
	var countTxt = document.createTextNode("Test Case "+count+"  ");		
	var newLine0 = document.createElement("br");
	var inCountTxt = document.createTextNode("Input:  "); 
	var newLine1 = document.createElement("br");
	var inputTxt = document.createElement("textarea"); 
	inputTxt.name = "testInput[]"; 
	inputTxt.cols = "40";
	inputTxt.rows = "6";
	var newLine2 = document.createElement("br");
	var outCountTxt = document.createTextNode("Output: "); 
	var newLine3 = document.createElement("br");
	var inputTxta = document.createElement("Textarea"); 
	inputTxta.name = "testOutput[]"; 
	inputTxta.cols = "40";
	inputTxta.rows = "6";
	var btn = document.createElement("input"); 
	btn.type = "button"; 
	btn.value = "Delete"; 
	btn.onclick = function() 
	{ 
		this.parentNode.parentNode.removeChild(this.parentNode); 

		var n = File1.getElementsByTagName("p"); 
		for(var k=0; k<n.length; k++) 
		{ 
			n[k].firstChild.nodeValue = "Test Case "+(k+1)+"  "; 
		} 
		count -= 1; 
	} 

	div.appendChild(countTxt); 
	div.appendChild(newLine0);
	div.appendChild(inCountTxt);
	div.appendChild(newLine1);
	div.appendChild(inputTxt); 
	div.appendChild(newLine2);
	div.appendChild(outCountTxt);
	div.appendChild(newLine3);
	div.appendChild(inputTxta); 
	div.appendChild(btn); 
	File1.appendChild(div); 
} 
</script> 
<div id="file1"> </div> 
<input value="New Test Case" type="button" onclick="Add();" /> <br/>
<input type="submit" value="Submit"/>
<?php
}
?>
