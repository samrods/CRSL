<?php 
  include('server.php');
  session_start(); 
  
  // Check to see if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  } else {
      // Identify username
      $username = $_SESSION['username']; 
  }
  
  // Destroy session and log the user out
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
?>

<!doctype html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Profile Gallery</title>
	
    <!--External sources -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/png" href="logo.png"/>
    
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2.3.2/dist/email.min.js"></script>
    <script type="text/javascript" src="myjs.js"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
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

    <h2 style="text-align: center;">Players Profiles</h2>
        <div class="grid_gallery">
            
            <?php
                // Load data for profiles who have been added to gallery
                $query = "SELECT * FROM view_gallery";
                $results = mysqli_query($db, $query);
                
                if (mysqli_num_rows($results) > 0) {
                  while($row = $results->fetch_assoc()) {
                        $uname      = $row['Username'];
                        $name       = $row["Name"];
                        $nickname   = $row["Nickname"];
                        $pos        = $row["Position"];
                        $supports   = $row["Team"];
                        $favPlayer  = $row["FavPlayer"];
                        $strength   = $row["Strength"];
                        $weakness   = $row["Weakness"];
                        $addprofile = $row["AddProfile"];
                        $pic        = $row["Image"];
                        
                        // Number of appearances
                        $result4 = $db->query("SELECT Apps FROM view_totalApps WHERE Username LIKE '$uname'");
                        $info = mysqli_fetch_assoc($result4);
                        $noOfApps = $info['Apps'];
                          
                        // Number of goals scored
                        $result5 = $db->query("SELECT ScoredGoals FROM view_totalGoals WHERE Username LIKE '$uname'");
                        $info = mysqli_fetch_assoc($result5);
                        $noOfGoals = $info['ScoredGoals'];
                        
                        // Number of wins
                        $result6 = $db->query("SELECT TotalWins FROM view_totalWins WHERE Username LIKE '$uname'");
                        $info = mysqli_fetch_assoc($result6);
                        $noOfWins = $info['TotalWins'];
                        
                        if (empty($noOfApps)) {
                            $noOfApps = '0';
                        } 
                        if (empty($noOfGoals)) {
                            $noOfGoals = '0';
                        }
                        if (empty($noOfWins)) {
                            $noOfWins = '0';
                        }
                        
                        // Create grid for gallery
                        echo '<div class="responsive">
                               <div class="gallery">
                               
                                    <div class="desc"><b>Name:</b> ' . $name . '</div>
                                    <img src="images\\' . $pic . '" alt=" No picture available">
                                    <div class="desc"><b>Nickname:</b> ' . $nickname . '</div>
                                    <div class="desc"><b>Position:</b> ' . $pos . '</div>
                                    <div class="desc"><b>Team:</b> ' . $supports . '</div>
                                    <div class="desc"><b>Fav Player:</b> ' . $favPlayer . '</div>
                                    <div class="desc"><b>Strength:</b> ' . $strength . '</div>
                                    <div class="desc"><b>Weakness:</b> ' . $weakness . '</div>
                                    <div class="hr" style="margin: 0.2em;"></div>
                                    <div class="desc"><b>Appearances:</b> ' . $noOfApps . '</div>
                                    <div class="desc"><b>No Of Wins:</b> ' . $noOfWins . '</div>
                                    <div class="desc"><b>Goals Scored:</b> ' . $noOfGoals . '</div>
                                </div>
                            </div>';
                    }
                }
            ?>
        </div>
    <div class="clearfix"></div>
    
</body>
</html>