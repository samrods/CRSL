<?php 
  include('server.php');
  session_start(); 
  
  // Check to see if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  
  // Destroy session and log the user out
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Admin - New Match</title>
	
    <!--External sources -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />	
	<link href="admin.css" rel="stylesheet" type="text/css" />	
	<link rel="shortcut icon" type="image/png" href="logo.png"/>
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	
</head>

<body>
    <h1 class="title">CRSL Football 5 a-side - <i>Admin</i></h1>
    
    <!--Navigation bar with username-->
    <div id="nav-placeholder"></div>
    
    <script>
        $(function(){
          $("#nav-placeholder").load("nav.php");
        });
    </script>
    
    		
</body>
</html>