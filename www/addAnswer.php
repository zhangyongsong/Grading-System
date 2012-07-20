<script type="text/javascript">
	function validateRequired(field,alerttext){
		with (field)
		{
			if (value==null||value=="")
			{
				alert(alerttext);
				return false;
			}
			else return true;
		}
	}
	
	function fileValidate(field){
		var fileTypes = new Array('.java', '.c','.cpp', '.c++');
		var fileName = field.value;
		var extension = fileName.substr(fileName.lastIndexOf('.'), fileName.length);
		var valid =0;
		for(var i in fileTypes)
        {
            if(fileTypes[i].toLowerCase() == extension.toLowerCase())
            {
                valid = 1;
                break;   
            }
        }
        if(valid == 1)
            return true;
        else
            return false;
	}
	
	function validateRadio(radioObj){
		if (!radioObj)
			return false;
		var radioLength = radioObj.length;
		if(radioLength == undefined)
			if(radioObj.checked)
				return true
			else
				return false;
		for(var i = 0; i < radioLength; i++) {
			if(radioObj[i].checked) {
				return true;
			}
		}
		return false;

	}
	
	function validateForm(thisform)
	{
		with (thisform)
		{
			if(validateRadio(p_id)== false)
			{
				alert("Please selecct your problem!");
				return false;
			}
			else if (validateRequired(userfile, "Please choose source code file before submitting!")==false)
			{
				userfile.focus();
				return false;
			}
			else if(!fileValidate(userfile)){
				alert("Please choose correct source file format for uploading!");
				return false;
			}
			else return true;
		}
	}
</script>

<?php
	require_once('userFuncts.php');
	// connect to mysql database using root account
	$conn = dbConnect();
	session_start();

	// Header 
	doHtmlHeader(getXmlValue('/GradingSystem/page/problemTitle'));
	doHtmlMenu();

	if(isset($_SESSION['valid_user']) && isset($_SESSION['priv'])){
		$username=$_SESSION['valid_user'];
		$priv=$_SESSION['priv'];
		ProblemForm($conn, $username, $priv);
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
function ProblemForm($conn, $username, $priv){
	// Display your uploading form  here.
	echo '<div>';
	$query="select * from enrollment where username='$username'";
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
	<h3>Select Course and Tutorial</h3>
	<label for="course">Course:</label>
<?php
	if($cNumber ==1){
		$row = $cResult->fetch_assoc();
		$course =$row['course_code'];
		echo $course.'<br/>';
	}
	else{
?>		
		<select id="course" name="course" onchange="this.form.submit();">
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
		$query="SELECT DISTINCT tutorial FROM problem WHERE Course_Code='$course' ORDER BY tutorial ASC";
		$tResult=$conn->query($query);
		$tNumber = $tResult->num_rows;

		for($i=0; $i<$tNumber; $i++)
		{
			$row=$tResult->fetch_assoc();
?>
			<option id="tutorial<?php echo $row['tutorial'];?>" value="<?php echo $row['tutorial'];?>">Tutorial <?php echo $row['tutorial'];?></option>
<?php
		}
		echo '</select></form>';
		@ $tResult->free();

		if(isset($_POST['tutorial']) && $_POST['tutorial']!= '0'){
				$tutorial = $_POST['tutorial'];
				echo "<script type='text/javascript'>document.getElementById('tutorial$tutorial').selected='true';</script>";
		}
		
		if (isset($tutorial)){
?>
			<form action="newAnswer.php" method="post" enctype="multipart/form-data" id="selectFile" onsubmit="return validateForm(this)">
<?php
			echo '<h3>Select problem from below:</h3>';
			$course = escapeString($conn, $course);
			$tutorial = escapeString($conn, $tutorial);
			
			$query="SELECT * FROM problem WHERE course_code='$course' AND tutorial= $tutorial";
			$pResult = $conn->query($query);
			$pNumber = $pResult->num_rows;
			echo "<table id='tblProblem'><tr><th>Problem<th>Brief Description<th>details</th></tr>";
			for($i=0; $i<$pNumber; $i++)
			{
				$row=$pResult->fetch_assoc();
?>
				<tr><td><input type="radio" name="p_id" id="p_id" value="<?php echo $row['p_id'];?>">
					<?php echo $row['p_num'].' '.$row['p_name'];?></input>
				<td><?php echo htmlspecialchars($row['explanation']);?>
				<td><a href="descProblem.php?pid=<?php echo $row['p_id'];?>" target="_blank">Details</a></tr>
<?php
			}
			echo "</table>";
			@ $pResult->free();

?>
		<h3>Upload your source file:</h3>
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
		<label for="userfile">Select your file: </label>
		<input type="file" name="userfile" id="userfile" onchange="setProblemValue();"/><br>
		<label for="comments">Type in your comments here:</label><br>
		<textarea name="comments" cols=40 rows=7></textarea><br/>
		<input type="submit" value="Submit" />&nbsp&nbsp&nbsp&nbsp
		<input type="reset" value="Reset"/>
	   </form>	
<?php
	   }
	}
	else 
		 echo '</form>';
		 
	echo '</div>';
}
?>
