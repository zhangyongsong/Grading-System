<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/PHPMailer_v5.1/class.phpmailer.php');
	require_once('userFuncts.php');
	doHtmlHeader('Reset Password');
	echo "<div id='content'>";

	echo '<h2>Reset Password</h2>';
	if(isset($_POST['username']))
		$name = addSlashes($_POST['username']);
	else gotoUrl('index.php');
	$conn = dbConnect();
	$result = dbQuery($conn, "SELECT * FROM user WHERE username = '$name'");
	if (!$result)
	{
		$conn->close();
		exit;
	}
	if ($result->num_rows != 1)
	{
		echo '<h3>The username you entered is not valid</h3>';
		echo "<br/><br/><a href='login.php'>Log in<br/>";
		exit;
	}

	$email = $name.'@ntu.edu.sg';
	$pwdLength = 6;
	$newPassword = generatePassword($pwdLength);
	
	$emailSubject = 'New Password for GradingSystem';
	$emailBody = "<html><body>
			<p>Hi $name,<br/><br/>
			Your new password for GradingSystem is <b><font color='red'>$newPassword</font></b>.<br/>
			You may change this password when you login to <a href=\"http://172.19.32.43\">NTU GradingSystem website</a> anytime.<br/><br/>

			Thank you,<br/>
			Grading System<br>
			Nanyang Technological Univeristy<br>
			</p>

			</body>
			</html>";
			
	$altEmailBody = "<html><body>
			<p>Hi $name,<br/><br/>
			Your new password for GradingSystem is <b><font color='red'>$newPassword</font></b>.<br/>
			You may change this password when you login to <a href=\"http://172.19.32.43\">NTU GradingSystem website</a> anytime.<br/><br/>

			Thank you,<br/>
			Grading System<br>
			Nanyang Technological Univeristy<br>
			</p>

			</body>
			</html>";

	//send the new pass word with email
	if(!sendEmail($email, $emailSubject, $emailBody, $altEmailBody))
		echo '<b>Error!</b><br/>We cannot send your new password out!<br/>Please contact your supervisor for assistance.';
	else{ 
		echo '<b>Message Sent!</b><br/>Please check your email for your new password.';

		$result = dbQuery($conn, "UPDATE user SET password = sha1('$newPassword') WHERE username = '$name'");
		$conn->close();
		echo "<br/><br/><a href='login.php'>Log in<br/>";
	}
	doHtmlFooter();
?>
<?php
//generate a random password, default length is 6
function generatePassword($pwdLength = 6)
{
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$charLength = strlen($chars);
	$password = '';
	for ($i = 0; $i < $pwdLength; $i++)
		$password .= $chars[mt_rand(0, $charLength-1)];
	return $password;
}


function sendEmail($address, $subject, $body, $altBody = '')
{
	date_default_timezone_set("Asia/Shanghai");
	
	$host =getXmlValue('/GradingSystem/email/host');
	$port =intval(getXmlValue('/GradingSystem/email/port'));
	$username =getXmlValue('/GradingSystem/email/username');
	$password=getXmlValue('/GradingSystem/email/password');
	$from =  getXmlValue('/GradingSystem/email/mailbox');
	$fromName = getXmlValue('/GradingSystem/email/mailname');
	$replyTo = getXmlValue('/GradingSystem/email/ReplyTo');
	$cc = getXmlValue('/GradingSystem/email/CarbonCopy');

	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";
	$mail->Encoding = 'base64';
	$mail->IsSMTP();
	$mail->Host = $host;
	$mail->Port = $port;
	$mail->SMTPAuth = true;
	$mail->Username = $username;
	$mail->Password = $password;
	$mail->From = $from;
	$mail->FromName = $fromName;
	$mail->AddAddress($address);
	$mail->AddReplyTo($replyTo);
	$mail->AddCC($cc);
	$mail->WordWrap = 50;
	$mail->IsHtml(true);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->AltBody = $altBody;
	
	if(!$mail->Send()){
		//echo $mail->ErrorInfo;
		return false;
	}
	else 
		return true;
}
?>
