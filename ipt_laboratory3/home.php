<?php 
session_start();

if (isset($_SESSION['username'])) {

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>HOME</title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
     <h1>Hello, <?php echo $_SESSION['username']; ?></h1>
     <a href="logout.php"><button type="button" class="btn btn-dark">Logout</button></a>
</body>
</html>

<?php 
}else{
     header("Location: loginform.php");
     exit();
}
 ?>