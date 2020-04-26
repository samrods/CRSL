<?php 
  include('server.php');
  session_start(); 
  
  // Check to see if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  } else {
      
      $yellowGoals = 0;
      $orangeGoals = 0;
      
      // Retrieve information for upcoming match
      $sql = $db->query("SELECT * FROM view_futureMatch");
      $match = $sql->fetch_row();
      
      if (!empty($match)) {
          // Get future match ID and date
          $matchID = $match[0];
          $matchDate = $match[1];
          $matchDate = date("l d M Y", strtotime($matchDate));
      } else {
          array_push($errors, "Error loading upcoming fixtures"); 
      }
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
	<title>Enter Results</title>
	
    <!--External sources -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />	
    
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script>
    	$(document).ready(function(){
    		
    		// Iterate through each textbox
    		$(function() {
    		    // Handlers to trigger the calculateSum function
    		    $(".yellowGoal").keyup(calculateSum);
    		    $(".yellowGoal").click(calculateSum);
    		    
    		    $(".orangeGoal").keyup(calculateSum);
    		    $(".orangeGoal").click(calculateSum);
    		});
    	});
    
    	function calculateSum() {
    
    		var sumyel = 0;
    		var sumor = 0;
    		// Iterate through each textbox and add the values
    		$(".yellowGoal").each(function() {
    
    			// Validation to ensure numbers only
    			if(!isNaN(this.value) && this.value.length!=0) {
    				sumyel += parseFloat(this.value);
    			}
    		});
    		$(".orangeGoal").each(function() {
    
    			// Validation to ensure numbers only
    			if(!isNaN(this.value) && this.value.length!=0) {
    				sumor += parseFloat(this.value);
    			}
    		});
    		
    		$("#sumYellow").html(sumyel);
            $("#sumOrange").html(sumor);
    	}
    </script>
   
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
    
    <!--Responsive form layout--> 
    <div class="newMatchForm"> 
        <h2 class="caption">Enter Results</h2>
                
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <?php include('errors.php'); ?>
            
            <!--List of players-->
            <div class="profile_row">
                <div class="cols" style="font-weight: bold;">Date:</div>
                <div class="col_4"><?php echo $matchDate; ?></div>
            </div>
            <div class="hr"></div>
            
            <div class="profile_row">
                <div class="match_col_1" style="color: yellow;">Yellow Team:</div>
                <div class="match_col_2">Goals Scored:</div>
                <div class="match_col_3" style="color: orange;">Orange Team:</div>
                <div class="match_col_3">Goals Scored:</div>
            </div>
            
            <div class="grid_results">
                <div class="responsive">
                   <!-- <div class="gallery">-->
                        
                        <!--<div class="profile_row">
                            <div class="match_col_1" style="color: yellow;">Yellow Team:</div>
                            <div class="match_col_2">Goals Scored:</div>
                            <div class="match_col_3" style="color: orange;">Orange Team:</div>
                            <div class="match_col_3">Goals Scored:</div>
                        </div>               -->
                        <?php
                          // Identify who played in the game
                          $sql = $db->query("SELECT u.Username, concat(u.FirstName, ' ', u.Surname) AS Name, MatchID, Yellow 
                                FROM tbl_UserGame ug 
                                INNER JOIN tbl_User u 
                                ON u.Username = ug.Username 
                                WHERE MatchID = $matchID 
                                ORDER BY Yellow DESC");
                          
                          /* Get field information for all fields */
                          while ($players = $sql->fetch_assoc()) {
                              $username  = $players['Username'];
                              $name  = $players['Name'];
                              $yellow = $players['Yellow'];
                              
                              // Yellow team
                              if ($yellow === "1") {
                                  echo '<div class="responsive">
                                            <div class="gallery">
                                                <div class="profile_row">
                                                    <div class="cols2">' . $name . '</div>
                                                    <div class="cols2"><input class="yellowGoal" type="number" min="0" max="15" name="yellowGoal" value="0"></div>
                                                </div>
                                                </div>
                                            </div>';
                              } 
                              // Orange Team
                              else {
                                  echo '<div class="responsive">
                                  <div class="gallery">
                                                <div class="profile_row">
                                                    <div class="cols2"></div>
                                                    <div class="cols2"></div>
                                                    <div class="cols2">' . $name .'</div>
                                                    <div class="cols2"><input class="orangeGoal" type="number" min="0" max="15" name="orangeGoal" value="0"></div>
                                                </div>
                                                </div>
                                            </div>                              ';
                              }
                          }
                          
                        ?>
                        
                        
                        
                   <!-- </div>-->
                    
                    
                </div>
            
            </div>
            
            
            
            
            
      </form>
    </div> <!-- /form -->
    
</body>
</html>