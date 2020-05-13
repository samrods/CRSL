<!----------------------------------->
<!-- Main Home page - Screenshots---->
<!----------------------------------->

<?php 
    //Connect to database
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
	<title>Screenshots</title>
	
    <!-- Sytling sheets -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
	
	<!-- External sources -->
	<link rel="shortcut icon" type="image/png" href="logo.png"/>                                <!--website favicon-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>   <!--menu favicons-->
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>                            <!--jQuery library-->
</head>

<body>
    <h1 class="title">CRSL Football 5 a-side</h1>
    
    <!--Navigation bar with username-->
    <div id="nav-placeholder"></div>
    <script>
        $(function(){
          $("#nav-placeholder").load("nav.php");
        });
    </script>
   
    <!--Responsive container for images-->
    <div class="newMatchTbl">
        <div class="ss_gallery">
            <div class="profile_row">
                
                <div class="profile_row_2">
                    <h2 class="stats-title">Screenshots - Prototype</h2>
                </div>
                
                <!--Display QR codes upon selection-->
                <select name="qr_codes" class="qr_codes" onchange="showQR(this.value)">
                    <option selected disabled hidden>Display QR Code:</option>
                    <option value="apps">Most Appearances</option>
                    <option value="goalsSc">Goals Scored</option>
                    <option value="tgoalsSc">Team Goals Scored</option>
                    <option value="tGoalsCo">Team Goals Conceded</option>
                    <option value="wins ">Most Wins</option>
                </select>
                <!--Display different QR code at runtime-->
                <div class="profile_row">
                    <!--Load QR code functionality from displayQR.php-->
                    <div id="imgQR" class="QR"></div>
                </div>
            </div>
            
            <!--Screenshots-->
            <div class="profile_row">
                <img src="images\\Screenshot\\Apps_Average.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\Apps_Department.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\Department_Goals.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\Goals_Conceded.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\Goals_Scored.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\NoOfWins.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\AppsByDepartmentTree.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\Team_Goals.jpg" class="img_ss" alt="No picture available">
                <img src="images\\Screenshot\\Website Usage.jpg" class="img_ss" alt="No picture available">
            </div>
        </div>
    </div>
    		
</body>
</html>