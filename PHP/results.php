<?php 
  include('server.php');
  session_start(); 
  
  $yellowTeam = array(); 
  $orangeTeam = array(); 
  
  // Temp arrays
  $yelUser = array();
  $orUser = array();
  
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
      
      $sql = $db->query("SELECT u.Username, concat(u.FirstName, ' ', u.Surname) AS Name, MatchID, Yellow 
                        FROM tbl_UserGame ug 
                        INNER JOIN tbl_User u 
                        ON u.Username = ug.Username 
                        WHERE MatchID = $matchID 
                        ORDER BY Yellow DESC");
      while ($players = $sql->fetch_assoc()) {
          $username  = $players['Username'];
          $yellow = $players['Yellow'];
                              
          // Yellow team
          if ($yellow === "1") {
              array_push($yelUser, $username);
          } else {
              array_push($orUser, $username);
          }
      }
      $noOfYellow = count($yelUser);
      $noOfOrange = count($orUser);
      
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
    <link rel="shortcut icon" type="image/png" href="logo.png"/>
    
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="myjs.js"></script>
    
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
            
            var goals_yel = document.getElementById("sumYellow").innerText;
            console.log(goals_yel);
            var goals_ora = document.getElementById("sumOrange").innerText;
            console.log(goals_ora);
            
            // Create cookies to store final score 
            createCookie("yellowGoals", goals_yel, "1");    // name, value, expirydays 
            createCookie("orangeGoals", goals_ora, "1"); 
    	}
    	
    	// Function to create the cookie 
        function createCookie(name, value, days) { 
            var expires; 
              
            if (days) { 
                var date = new Date(); 
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); 
                expires = "; expires=" + date.toGMTString(); 
            } 
            else { 
                expires = ""; 
            } 
            document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/"; 
        } 
        
        function test() {
            var yellowTeam = <?php echo json_encode($yelUser); ?>;
            var orangeTeam = <?php echo json_encode($orUser); ?>;
            
            for (var i = 0; i < yellowTeam.length; i++) {
                
                // Get each username with goals scored
                var username = yellowTeam[i];
                var goals = document.getElementById(username).value;
                
                // Create cookie for each user with the number of goals scored
                createCookie(username, goals, "0.5"); 
            }
            for (var j = 0; j < orangeTeam.length; j++) {
                
                // Get each username with goals scored
                var username = orangeTeam[j];
                var goals = document.getElementById(username).value;
                
                // Create cookie for each user with the number of goals scored
                createCookie(username, goals, "0.5"); 
            }
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
            <div class="newMatchTbl">
                <div class="gallery">
                    
                    <!--List of players-->
                    <div class="profile_row">
                        <div class="cols" style="font-weight: bold;">Date:</div>
                        <div class="col_4"><label name="matchDate"><?php echo $matchDate; ?></label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="matchDate" value="<?php echo $matchDate; ?>">
                    </div>
                     <div class="profile_row">
                        <div class="cols" style="font-weight: bold;">Match No.</div>
                        <div class="col_4"><label name="matchID"><?php echo $matchID; ?></label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="matchID" value="<?php echo $matchID; ?>">
                    </div>
                    <div class="hr"></div>
                    <div class="profile_row" style="font-weight: bold;">
                        <div class="res1" style="color: yellow;">Yellow Team:</div>
                        <div class="match_col_2" style="margin-right: 5%">Goals Scored:</div>
                        <div class="res1" style="color: orange; margin-right: -1%">Orange Team:</div>
                        <div class="match_col_3">Goals Scored:</div>
                    </div>
                    
                    <!--Display teams-->
                    <div class="teamContainer2">
                        <?php
                          // Identify who played in the game
                          $sql = $db->query("SELECT u.Username, concat(u.FirstName, ' ', u.Surname) AS Name, MatchID, Yellow 
                                FROM tbl_UserGame ug 
                                INNER JOIN tbl_User u 
                                ON u.Username = ug.Username 
                                WHERE MatchID = $matchID 
                                ORDER BY Yellow DESC");
                          
                          // Player count 
                          $ycounter = 0;
                          $ocounter = 0;
                                
                          /* Get field information for all fields */
                          while ($players = $sql->fetch_assoc()) {
                              $username  = $players['Username'];
                              $name  = $players['Name'];
                              $yellow = $players['Yellow'];
                              
                              // Yellow team
                              if ($yellow === "1") {
                                  // Add to yellow team array
                                  array_push($yellowTeam, $name);
                                        
                                  echo '<div class="profile_row">
                                            <div class="res1" name="yelName" style="width: 38%; margin-right: 10%;">' . $yellowTeam[$ycounter] . '</div>
                                            <div class="res2"><input class="yellowGoal" type="number" id="' . $username .'" min="0" max="15" name="' . $username .'" onkeydown="return (event.keyCode!=107&&event.keyCode!=109);" value="0"></div>
                                        </div>';
                                    $ycounter++;  
                              } 
                              // Orange Team
                              else {
                                  // Add to orange team array
                                  array_push($orangeTeam, $name); 
                                  
                                  echo '<div class="profile_row">
                                            <div class="res2" name="oraName" style="width: 38%; margin-right: 10%;">' . $orangeTeam[$ocounter] .'</div>
                                            <div class="res2"><input class="orangeGoal" type="number" id="' . $username .'" min="0" max="15" name="' . $username .'" onkeydown="return (event.keyCode!=107&&event.keyCode!=109);" value="0"></div>
                                        </div>';
                                    $ocounter++;
                              }
                          }
                          
                        ?>
                    </div>
                    
                    <div class="hr"></div>
                    
                    <!--Totals Row-->
                    <div class="profile_row">
                        <div class="match_col_1"></div>
                        <div class="goalsScored" style="color: yellow; width: 20%;"><b>Yellow Team:</b></div>
                        <div class="match_col_1"></div>
                        <div class="goalsScored" style="color: orange; width: 20%;"><b>Orange Team:</b></div>
                    </div>
                    
                    <div class="profile_row">
                        <div class="match_col_1" style="font-weight: bold;">Final Score:</div>
                        <div class="goalsScored"><label name="yelGoals" value="0" id="sumYellow" style="font-size: 25px; margin-left: 1em;">0</label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="yelGoals">
                        
                        <div class="v">v</div>
                        
                        <div class="goalsScored"><label name="oraGoals" value="0" id="sumOrange" style="font-size: 25px; margin-left: 1em;">0</label></div>
                        <input type="hidden" name="oraGoals">
                    </div>
                    
                    <div class="hr"></div>
                    
                    <div class="profile_row">
                        <div class="match_col_1" style="font-weight: bold;">Drop outs?</div>
                        <div class="player_col_2">
                                
                                <?php
                                    // List of all players
                                    $sql = $db->query("SELECT Username, CONCAT(FirstName, ' ', Surname) AS Name FROM tbl_User ORDER BY Name ASC");
                                    $players = $sql->fetch_row();
                                      
                                    echo "<div class='tooltip'><span class='tooltiptext'>Hold 'Ctrl' to select multiple players</span>
                                           <select style='height: 200px; width: 180px' id='ddlMth' name='dropouts' multiple='multiple'>";
                                      
                                        while ($players = $sql->fetch_row()) {
                                            $username = $players[0];
                                            $name = $players[1];
                                            echo "<option value='$name'>$name</option>";
                                        }   
                                        echo "</select></div>";
                                ?>
                        </div>
                    </div>
                    
                    <div class="hr"></div>
                    
                    <div class="publish_gry">
                        <div class="tooltip">
                            <span class="tooltiptext">Submit results of match.</span>
                            <button type="submit" name="update_results" onclick="test()" class="publish">Submit <i class="far fa-edit"></i></button>
                        </div>
                    </div>
                    
                </div>
            </div>
      </form>
    </div> <!-- /form -->
    
</body>
</html>