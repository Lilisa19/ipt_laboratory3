<?php 
session_start(); 
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {

	function validate($data){
       $data = trim($data);
	   $data = stripslashes($data);
	   $data = htmlspecialchars($data);
	   return $data;
	}

	$uname = validate($_POST['uname']);
	$pass = validate($_POST['password']);

	if (empty($uname)) {
		header("Location: loginform.php?error=User Name is required");
	    exit();
	}else if(empty($pass)){
        header("Location: loginform.php?error=Password is required");
	    exit();
	}else{
		// hashing the password
        $pass = md5($pass);

        
		$sql = "SELECT * FROM user WHERE username='$uname' AND password='$pass'";

		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) === 1) {
			$row = mysqli_fetch_assoc($result);
            if ($row['username'] === $uname && $row['password'] === $pass) {
				if ($row['is_verify'] == 0) {
					header("Location: loginform.php?error=This account is not yet verified, please check your registered email and click the Verify button.");
                    exit;
				}
            	$_SESSION['username'] = $row['username'];
            	$_SESSION['name'] = $row['name'];
            	$_SESSION['id'] = $row['id'];
            	header("Location: home.php");
		        exit();
            }else{
				header("Location: loginform.php?error=Incorect User name or password");
		        exit();
			}
		}else{
			header("Location: loginform.php?error=Incorect User name or password");
	        exit();
		}
	}
	
}else{
	header("Location: loginform.php");
	exit();
}
?>
