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
	<title>Power Bi</title>
	
    <!--External sources -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />	
	<link href="admin.css" rel="stylesheet" type="text/css" />	
	<link rel="shortcut icon" type="image/png" href="logo.png"/>
	
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="myjs.js"></script>
    
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
    
    <div class="newMatchTbl">
        <div class="gallery">
            <div class="profile_row">
                
                <div class="profile_row" style="width: 75%;">
                    <h2 class="caption2" style="background: #00214d; font-style: italic; text-align: center; font-size: 26px; padding: 0.2em; margin: 0;">Power Bi</h2>
                </div>
                
                <select name="qr_codes" style="float: right; width: 23%; display: inline-block;" onchange="showQR(this.value)">
                    <option selected disabled hidden>Display QR Code:</option>
                    <option value="apps">Most Appearances</option>
                    <option value="goalsSc">Goals Scored</option>
                    <option value="tgoalsSc">Team Goals Scored</option>
                    <option value="tGoalsCo">Team Goals Conceded</option>
                    <option value="wins ">Most Wins</option>
                </select>
                <div class="profile_row">
                    <!--Display different QR code at runtime-->
                    <div id="imgQR" class="QR"></div>
                </div>
            </div>
            
            <!--Power Bi Charts-->
            <div class="profile_row">
                <iframe width="850" height="450" src="https://app.powerbi.com/view?r=eyJrIjoiMzVjYWQwNDQtY2M3Yy00ZWFiLWExY2EtYjczNWEyYTA5MWE5IiwidCI6ImE5MDBiYjkwLTk0ZmUtNDY1OC04YjM0LWRkNzIwODRjNTA2NCIsImMiOjh9" frameborder="0" allowFullScreen="true">
                </iframe>
            </div>
            
        </div>
    </div>
    		
</body>
</html>