// The event handler function for the name text box

function chkName() {
  var myName = document.getElementById("username");

// Test the format of the input name 
  var pos = myName.value.search(
            /^[A-Za-z0-9\-\_\.]+$/);

  if (pos != 0) {
    alert("The name you entered (" + myName.value + 
          ") is not in the correct form. \n" +
          "Please go back and fix your name.");
    myName.focus();
    myName.select();
    return false;
  } else
    return true;
}
function chkPassword() { 
  var init = document.getElementById("password");
  var sec = document.getElementById("password2");

  if(init.value.length<6 || init.value.length >16)
  {
	alert("Password length must between 6 to 16 chars. Please enter again.");
	init.focus();
	init.select();
	return false;
  } 
  else if (init.value != sec.value) {
    alert("The two passwords you entered are not the same \n" +
          "Please re-enter both now");
    sec.focus();
    sec.select();
    return false;
  } 
  else
    return true;
}

// The event handler function for the email text box
function chkEmail() {
  var myEmail = document.getElementById("email");

// Test the format of the input phone number
  var pos = myEmail.value.search(/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/);

  if (pos != 0) {
    alert("The email you entered (" + myEmail.value +
          ") is not in the correct form. \n" +
          "Please go back and fix your Email.");
    myEmail.focus();
    myEmail.select();
    return false;
  } else
    return true;
}
function checkForm()
{
	if(chkName()==false||chkPassword()==false||chkEmail()==false)
		return false;
	else return true;
}
