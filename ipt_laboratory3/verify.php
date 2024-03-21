<?php  
require('db_conn.php');

if(isset($_GET['email']) && isset($_GET['v_code'])){
    $email = $_GET['email'];
    $v_code = $_GET['v_code'];

    // Query to check if the provided email and verification code match
    $query = "SELECT * FROM `user` WHERE `Email` = '$email' AND `verification_code`= '$v_code'";

    $result = mysqli_query($conn, $query);

    if($result){
        if(mysqli_num_rows($result) == 1){
            $result_fetch=mysqli_fetch_assoc($result);
            if($result_fetch['is_verify']==0){
                // Update the verification status in the database
                $update ="UPDATE user SET is_verify='1' WHERE Email = '$email'";
                if(mysqli_query($conn, $update)){
                    header("Location: loginform.php?success=Email verification successful.");
                    exit();
                } else{
                    header("Location: loginform.php?error=Unknown error occurred.");
                    exit();
                }
            }else{
                header("Location: loginform.php?error=Email Address was already registered");
                exit();
            }
        }
    } else {
        header("Location: loginform.php?error=Unknown error occurred.");
        exit();
    }
} else {
    // Handle case where email or verification code is not provided
    header("Location: loginform.php?error=Email or verification code is missing.");
    exit();
}
?>
