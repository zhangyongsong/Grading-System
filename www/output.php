<?php
require_once("utilityFunctions.php");

function doHtmlHeader($title)
{
	//print an HTML header
?>
	<html>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<head>
		<title><?php echo $title;?></title>
		<link rel="stylesheet" type="text/css" href="cssWeb.css" >
		<link rel="stylesheet" type="text/css" href="Styles/SyntaxHighlighter.css"></link>
		
		<link rel="shortcut icon" type="image/x-icon" href="images/oj.ico" />
	</head>
	
	<body>
	<div id="container">
	<div id="header">
	<img src = "<?php echo getXmlAttribute('/GradingSystem/page', 'logo'); ?>" alt = "Web-Based Grading System" />
	
	<!-- <h1><?php echo getXmlAttribute('/GradingSystem/page', 'slogan');?> </h1> -->
<?php
	if(isset($_SESSION['valid_user']) && isset($_SESSION['priv']))
	{
		echo "<table id='message'><tr><td colspan = '2'>";
		echo "Welcome, <b>";  
		doHtmlUrl('index.php', $_SESSION['valid_user']); 
		$priv = $_SESSION['priv'];
		//if (strcasecmp($priv, 'user') == 0)
		//	echo '</b>. Your privilege is <b>student</b>.';
		//else 
		echo '</b>. Your privilege is <b>'.$priv.'</b>.';
		echo "<tr><th>";
		doHtmlUrl('changePassword.php', 'Change Password');
		echo "<th>";
		doHtmlUrl('logout.php', 'Log out');
		echo "</tr></table>";
	}
?>
	</div>	
	<br class="clearfix" />
	
<?php
}

function doHtmlMenu(){
?>
	<div id="menu">
	<div class="blocks">
	<img src="images/top_bg.gif" alt="" width="218" height="12" />
	<ul>
	
<?php 
	if(isset($_SESSION['valid_user']) && isset($_SESSION['priv'])){
		$priv = $_SESSION['priv'];
		
		if($priv =='administrator'){
			echo '<li>';
			doHtmlUrl('course.php', 'Course');
			echo '<li>';
			doHtmlUrl('addUser.php', 'Register User');
			echo '<li>';
			doHtmlUrl('deleteUser.php', 'View/Remove User');
			echo '<li>';
			doHtmlUrl('resetSystem.php', 'Reset System');
		}
		elseif ($priv=='instructor'){
			echo '<li>';
			doHtmlUrl('problem.php', 'Problem / Test Case');
			echo '<li>';
			doHtmlUrl('userStatus.php', 'User Status');
			echo '<li>';
			doHtmlUrl('problemStatus.php', 'Problem Status');
			
		}
		else{
			echo '<li>';
			doHtmlUrl('addAnswer.php', 'Problem List');
			echo '<li>';
			doHtmlUrl('listResult.php', 'Answer Status');
		}
		echo '<li>';
		doHtmlUrl('index.php', 'User Info');
	}
?>		
	</ul>
	<img src="images/bot_bg.gif" alt="" width="218" height="10" /><br />
	</div>
	</div>
	<div id="content">
<?php
}

function doHtmlFooter()
{
	//doHtmlUrl('logout.php', 'Please Log out here.');
?>
	</div>
	<br class="clearfix" />
	</div>
	<div id="footer">
		<a href="index.php" class="terms">Home</a>  |  <a href="index.php" class="terms">User Status</a> |  <a href="mailto:z080320@ntu.edu.sg" class="terms">Contact Us</a> 
		|  <a href="mailto:zhuw0005@ntu.edu.sg" class="terms">Feedback</a>  |  <a href="http://www3.ntu.edu.sg/home/ehchua/programming/index.html" class="terms">Other Information</a>
		<p>Copyright &copy;2011 All rights reserved. Designed by FYP students.</p>																																																																																																																																						
	</div>
	
<script class="javascript" src="Scripts/shCore.js"></script>  

<script class="javascript" src="Scripts/shBrushCSharp.js"></script>  

<script class="javascript" src="Scripts/shBrushPhp.js"></script>  

<script class="javascript" src="Scripts/shBrushJScript.js"></script>  

<script class="javascript" src="Scripts/shBrushJava.js"></script>  

<script class="javascript" src="Scripts/shBrushVb.js"></script>  

<script class="javascript" src="Scripts/shBrushSql.js"></script>  

<script class="javascript" src="Scripts/shBrushXml.js"></script>  

<script class="javascript" src="Scripts/shBrushDelphi.js"></script>  

<script class="javascript" src="Scripts/shBrushPython.js"></script>  

<script class="javascript" src="Scripts/shBrushRuby.js"></script>  

<script class="javascript" src="Scripts/shBrushCss.js"></script>  

<script class="javascript" src="Scripts/shBrushCpp.js"></script>  

<script class="javascript">  

dp.SyntaxHighlighter.HighlightAll('code');  

</script> 

	</body>
</html>
<?php
}

function doHtmlUrl($url, $keyword)
{
	echo "<a href = ".$url.">".$keyword."</a>";
}

function alertGoback($message)
{
	echo "<script type='text/javascript'> alert('$message');</script>";
		//history.go(-1) would make the history page expires sometimes
	//gotoUrl($_SERVER['HTTP_REFERER']);
}

function goBack()
{
	gotoUrl($_SERVER['HTTP_REFERER']);
}

function gotoUrl($url)
{
	echo "<script>window.location.href='$url';</script>";
}
?>
