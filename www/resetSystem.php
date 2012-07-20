<?php
	require_once('userFuncts.php');
	require_once('deleteFolder.php');
	session_start();
	doHtmlHeader('System Reset');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
?>
<script type="text/javascript">
	onload = function(){
	var r=confirm("Do you want to reset the whole system?\n (All old submissions would be permanently removed from the system!!");
	if (r==false)
	{
		window.location.href="index.php"; 
	}
	else
	{
		window.location.href="confirmResetSystem.php";
	}
 }
</script>
