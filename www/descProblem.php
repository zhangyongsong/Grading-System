<?php
	require_once('userFuncts.php');
	// connect to mysql database using root account
	$conn = dbConnect();
	session_start();

	// Header 
	doHtmlHeader('Problem description');
	doHtmlMenu();

	if(isset($_SESSION['valid_user']) && isset($_SESSION['priv'])){
		if(isset($_GET['pid'])){
			$pid = $_GET['pid'];
			$pid = escapeString($conn, $pid);
			describe($conn, $pid);
		}
		else{
			echo 'Error occurred. No problem selected!';
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
	function describe($conn, $pid){
		$query = "select * from problem where problem.p_id =".$pid;
		$descResult = $conn->query($query);
		
		if($descResult->num_rows){
			$row = $descResult->fetch_assoc();
			echo "<p class='Title'>Problem: ".$row['p_name']."</p>";
			echo "<ul id='subHeading'>";
			sectionDisplay('Description', $row['description']);
			sectionDisplay('Language', $row['lang']);
			sectionDisplay('Input', $row['input']);
			sectionDisplay('Output', $row['output']);
			sectionDisplay('Sample Input', $row['sample_input']);
			sectionDisplay('Sample Output', $row['sample_output']);
			sectionDisplay('Hint', $row['hint']);
			echo "</ul>";
		}
		else{
			echo 'Description for this problem not set in the database!';
		}
		@ $descResult->free();
	}
	
	function sectionDisplay($heading, $text){
		echo "<li class='subheading'>".$heading."</li>";
		echo "<pre class=\"wrap\">$text</pre>";
		echo '<br/>';
	}

//	function sectionDisplay($heading, $text){
//		echo "<li class='subheading'>".$heading."</li>";
//		echo "<p class ='text'>".nl2br(htmlspecialchars($text))."</p>";
//		echo '<br/>';
//	}
?>
