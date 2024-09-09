//This checks to see whether or not the passwords match
function chk(){
	var pass = document.getElementById("pass").value;
	var confirmPass = document.getElementById("confirmPass").value;

    if(pass!=confirmPass){
      document.getElementById("error").innerHTML = "<br>Passwords do not match!";
      //document.getElementById("confirmPass").value = ""; //clear password
	  error.style.color = 'red'; // Set text color to red
    }
	else{
		error.style.display = 'none'; // Hide error message
	}
}

//Prompts user to create a password that is at least 8 characters long
document.getElementById('pass').addEventListener('input', function(){
	const passwordField = this;
	const errorElement = document.getElementById('passLengthError');

	if (passwordField.value.length < 8){
		errorElement.style.color = 'red'; // Set text color to red
		document.getElementById("passLengthError").innerHTML = "<br>The password must be at least 8 characters long.";
	} 
	else{
		errorElement.style.display = 'none'; // Hide error message
	}
});

//Prompts user to enter an email that ends in ".asu"
document.getElementById('email').addEventListener('input', function(){
	const emailField = this;
	const emailErrorElement = document.getElementById('emailError');
	const emailPattern = /(\W|^)[\w.+\-]*@asu\.edu(\W|$)/;

	if (!emailPattern.test(emailField.value)){
		emailErrorElement.style.color = 'red';
		document.getElementById("emailError").innerHTML = "<br>Please enter an ASU email address.";
	} 
	else{
		emailErrorElement.style.display = 'none'; // Hide error message
	}
});