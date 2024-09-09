<?php
session_start();

//Protection against Cross-Site Request Forgery (CSRF)
if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
    die("CSRF token validation failed.");
}

//Connect to MongoDB
require_once 'dbconnect.php';
$collection = $manager->mydb->newusers;

//***
//Functions

//Pass array to MongoDB
function register($document){
	global $collection;
	$collection->insertOne($document);
	return true;
}

//Check to see whether or not the email is on file
function chkEmail($userEmail){
	global $collection;
	$temp = $collection->findOne(array('EmailAddress'=> $userEmail));
	if(empty($temp)){
		return true;
	}
	else{
		return false;
	}
}

//***

//Get values from the Registration form, then pass them to an array
if(isset($_POST['reg'])){
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$userEmail = $_POST['email'];
	$temp  = $_POST['pass'];
	$myId = uniqid();
	$myDate = date("Y-m-d");
	$options = array('cost' => 10);
	$pass = password_hash($temp, PASSWORD_BCRYPT, $options);
	$vdkey= md5(time());

	$arrays = array(
		"FirstName"    => $fname,
		"LastName"     => $lname,
		"EmailAddress" => $userEmail,
		"Password"      => $pass,
		"VerificationKey" => $vdkey,
		"Verified" => "0",
		"_id" => $myId,
		"RegisterDate" => $myDate
	);
		
	//Check to see whether or not the user's email address is already on file
	$emailNotOnFile = chkEmail($userEmail);
	if($emailNotOnFile){
		//email not on file - using the API below to pass data from $arrays to SendGrid
		register($arrays);
		
		//SendGrid Username
		$sendgridUsername = "YOUR-SENDGRID-USERNAME-GOES-HERE";
		//"From" field
		$name = "SALT - Security Awareness Library Tool";
		//Body + Subject of Email
		$body = "<h3>Please click the link below to complete the SALT verification process</h3>
		<br><p>link/verify.php?vkey=$vdkey</p>";
		$subject = "SALT | Please verify your account";
	
		//Set HTTP Headers
		$headers = array(
			'Authorization: Bearer YOUR-SENDGRID-API-KEY-GOES-HERE',
			'Content-Type: application/json'
		);
		
		//Set Required POST Fields
		$data = array(
			"personalizations" => array(
				array(
					"to" => array(
						array(
							"email" => $userEmail
						)
					)
				)
			),
			"from" => array(
				"email" => $sendgridUsername,
				"name" => $name 
			),
			"subject" => $subject,
			"content" => array(
				array(
					"type" => "text/html",
					"value" => $body
				)
			)
		);
		
		//Send the data to the endpoint
		$curlHandle = curl_init();
		curl_setopt($curlHandle, CURLOPT_URL, "https://api.sendgrid.com/v3/mail/send");
		curl_setopt($curlHandle, CURLOPT_POST, 1);
		curl_setopt($curlHandle, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curlHandle);
		curl_close($curlHandle);
	
		echo $response;
		header("Location: SuccessfulEmailReg.php");
		//Once SendGrid receives this request, it will deliver an email to $userEmail
	}
	else{
		//email on file - exiting
		echo "Email already registered!";
		echo"<br>";
		echo "Please <a href='../public/register.html'>Register</a> with another email address";
   }	
}
else{
	echo "Unable to register: Please go back and fill in the required fields";
}
?>