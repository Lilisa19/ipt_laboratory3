<?php 
session_start(); 
include "db_conn.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendMail($email, $v_code){
	require ("PHPMailer/PHPMailer.php");
	require ("PHPMailer/SMTP.php");
	require ("PHPMailer/Exception.php");

	$mail = new PHPMailer(true);

	try {
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = 'melmel.iptlaboratory@gmail.com';                     //SMTP username
		$mail->Password   = 'apdw cfko gqtz wejj';                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
		$mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	
		//Recipients
		$mail->setFrom('melmel.iptlaboratory@gmail.com', 'Melissa Estrella');
		$mail->addAddress($email);     //Add a recipient
	
	
		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'EMAIL VALIDATION | ESTRELLA LABORATORY ACTIVITY';
		$mail->Body    = "<html>

		<body>
			<div class='container'>
				<h1>Thank you for registration!</h1>
				<h3>Click the link below to successfully verify your account</h3>
				<hr>
				<p><a class='button' href='http://localhost/ipt_lab3/verify.php?email=$email&v_code=$v_code' style='text-decoration: none;'>Verify</a></p>

			</div>
		</body>
		</html>";
	
		$mail->send();
		return true;
	} catch (Exception $e) {
		return false;
	}

}

if (isset($_POST['firstname']) && isset($_POST['middlename']) && isset($_POST['lastname']) 
	&& isset($_POST['uname']) && isset($_POST['password']) && isset($_POST['email'])
    && isset($_POST['re_password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$firstname = validate($_POST['firstname']);
	$middlename = validate($_POST['middlename']);
	$lastname = validate($_POST['lastname']);

	$email = validate($_POST['email']);

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	$re_pass = validate($_POST['re_password']);
	$terms = $_POST['terms'];


	if (empty($lastname)) {
		header("Location: signup.php?error=User Name is required&$lastname");
	    exit();
	}else if (empty($firstname)) {
		header("Location: signup.php?error=User Name is required&$firstname");
	    exit();
	}else if (empty($lastname)) {
		header("Location: signup.php?error=User Name is required&$lastname");
	    exit();
	}else if (empty($uname)) {
		header("Location: signup.php?error=User Name is required&$user_data");
	    exit();
	}else if (empty($email)) {
		header("Location: signup.php?error=User Name is required&$email");
	    exit();
	}else if(empty($pass)){
        header("Location: signup.php?error=Password is required&$user_data");
	    exit();
	}
	else if(empty($re_pass)){
        header("Location: signup.php?error=Re Password is required&$user_data");
	    exit();
	}else if($pass !== $re_pass){
        header("Location: signup.php?error=The confirmation password  does not match&$user_data");
	    exit();
	}else if(empty($terms)){
		header("Location: signup.php?error=the terms and condition is required&$user_data");
		exit();
	}

	else{

		// hashing the password
        $pass = md5($pass);

	    $sql = "SELECT * FROM user WHERE username='$uname' ";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			header("Location: signup.php?error=The username is taken try another&$user_data");
	        exit();
		}else {
			$v_code= bin2hex(random_bytes(16));
           $sql2 = "INSERT INTO user(Lastname, First_name, Middle_name, username, Email, password, verification_code, is_verify) VALUES('$lastname','$firstname','$middlename','$uname', '$email','$pass', '$v_code', '0')";
           $result2 = mysqli_query($conn, $sql2);
           if ($result2 && sendMail($_POST['email'], $v_code)) {
           	 header("Location: signup.php?success=Your account has been registered, please check your email to verify");
	         exit();
           }else {
	           	header("Location: signup.php?error=unknown error occurred&$user_data");
		        exit();
           }
		}
	}
	
}else{
	header("Location: signup.php");
	exit();
}