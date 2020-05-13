<?php 
/*----------------------------------------------*/
/*-- ADMIN PAGE: PHP to enter results of game --*/
/*----------------------------------------------*/

    // connect to database
    include('server.php');
    session_start(); 
    
    // initialise arrays to store team names
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
      
      // initialise variables
      $yellowGoals = 0;
      $orangeGoals = 0;
      
      // Retrieve information for upcoming match
      $sql = $db->query("SELECT * FROM view_futureMatch");
      $match = $sql->fetch_row();
      
      // upcoming match has been created
      if (!empty($match)) {
          // Get future match ID and date
          $matchID = $match[0];
          $matchDate = $match[1];
          $matchDate = date("l d M Y", strtotime($matchDate));
      } else {
          array_push($errors, "Error loading upcoming fixtures"); 
      }
      
      // identify who played in what team
      $sql = $db->query("SELECT u.Username, concat(u.FirstName, ' ', u.Surname) AS Name, MatchID, Yellow 
                        FROM tbl_UserGame ug 
                        INNER JOIN tbl_User u 
                        ON u.Username = ug.Username 
                        WHERE MatchID = $matchID 
                        ORDER BY Yellow DESC");
      //loop through results
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
      // get length of arrays
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
	
    <!-- Styling sheets -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />	
    
    <!-- External sources -->
    <link rel="shortcut icon" type="image/png" href="logo.png"/>
    <script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="myjs.js"></script>
    
    <!-- Function to calculate final score -->
    <script>
    	$(document).ready(function(){
    		
    		// Iterate through each textbox
    		$(function() {
    		    // Handler to trigger function when user presses key
    		    $(".yellowGoal").keyup(calculateSum);
    		    // Handler to trigger function when user clicks on incrementor
    		    $(".yellowGoal").click(calculateSum);
    		    // Handler to trigger function when user presses key
    		    $(".orangeGoal").keyup(calculateSum);
    		    // Handler to trigger function when user clicks on incrementor
    		    $(".orangeGoal").click(calculateSum);
    		});
    	});
    	
    	// Method to calculate final score based on data entry
    	function calculateSum() {
    		var sumyel = 0;
    		var sumor = 0;
    		
    		// YELLOW TEAM
    		// Iterate through each textbox and add the values
    		$(".yellowGoal").each(function() {
    
    			// Validation to ensure numbers only
    			if(!isNaN(this.value) && this.value.length!=0) {
    				sumyel += parseFloat(this.value);
    			}
    		});
    		// ORANGE TEAM
    		$(".orangeGoal").each(function() {
    
    			// Validation to ensure numbers only
    			if(!isNaN(this.value) && this.value.length!=0) {
    				sumor += parseFloat(this.value);
    			}
    		});
    		
    		// identify html value
    		$("#sumYellow").html(sumyel);
            $("#sumOrange").html(sumor);
            
            // output value to screen - change at runtime
            var goals_yel = document.getElementById("sumYellow").innerText;
            console.log(goals_yel);
            var goals_ora = document.getElementById("sumOrange").innerText;
            console.log(goals_ora);
            
            // Create cookies to store final score 
            createCookie("yellowGoals", goals_yel, "1");    // (name, value, expirydays) 
            createCookie("orangeGoals", goals_ora, "1");    // (name, value, expirydays) 
    	}
    	
    	// Function to create cookie 
        function createCookie(name, value, days) { 
            var expires; 
              
            // set date for cookie to expire  
            if (days) { 
                var date = new Date(); 
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); 
                expires = "; expires=" + date.toGMTString(); 
            } 
            else { 
                expires = ""; 
            } 
            // assign expiration to cookie 
            document.cookie = escape(name) + "=" + escape(value) + expires + "; path=/"; 
        } 
        
        // Create cookie for every player and their associated goals
        function playerGoals() {
            // encode value in JSON format
            var yellowTeam = <?php echo json_encode($yelUser); ?>;
            var orangeTeam = <?php echo json_encode($orUser); ?>;
            
            // loop through yellow team data structure
            for (var i = 0; i < yellowTeam.length; i++) {
                
                // Get each username with goals scored
                var username = yellowTeam[i];
                var goals = document.getElementById(username).value;
                
                // Create cookie for each user with the number of goals scored
                createCookie(username, goals, "0.5"); 
            }
            // loop through orange team data structure
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
            <!--Display errors when submitting form-->
            <?php include('errors.php'); ?>
            <div class="newMatchTbl">
                <div class="gallery">
                    
                    <!--Display match information-->
                    <div class="profile_row">
                        <div class="column_1">Date:</div>
                        <div class="column_2"><label name="matchDate"><?php echo $matchDate; ?></label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="matchDate" value="<?php echo $matchDate; ?>">
                    </div>
                     <div class="profile_row">
                        <div class="column_1">Match No.</div>
                        <div class="column_2"><label name="matchID"><?php echo $matchID; ?></label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="matchID" value="<?php echo $matchID; ?>">
                    </div>
                    <div class="hr"></div>      <!--Line divider-->
                    
                    <!--Dynamic table structure to display players and no of goals each-->
                    <div class="profile_row">
                        <div class="yel_head_1">Yellow Team:</div>
                        <div class="column_3">Goals Scored:</div>
                        <div class="ora_head_1">Orange Team:</div>
                        <div class="column_4">Goals Scored:</div>
                    </div>
                    
                    <!--Display teams-->
                    <div class="team_container">
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
                              
                              // YELLOW TEAM
                              if ($yellow === "1") {
                                  // Add to yellow team array
                                  array_push($yellowTeam, $name);
                                        
                                  // output data
                                  echo '<div class="profile_row">
                                            <div class="yel_head_2" name="yelName">' . $yellowTeam[$ycounter] . '</div>
                                            <div class="ora_head_2"><input class="yellowGoal" type="number" id="' . $username .'" min="0" max="15" name="' . $username .'" 
                                            onkeydown="return (event.keyCode!=107&&event.keyCode!=109&&event.keyCode!=69&&event.keyCode!=110);" value="0"></div>
                                        </div>';
                                  // next player
                                  $ycounter++;  
                              } 
                              // ORANGE TEAM
                              else {
                                  // Add to orange team array
                                  array_push($orangeTeam, $name); 
                                  
                                  // Output data
                                  echo '<div class="profile_row">
                                            <div class="ora_head_3" name="oraName">' . $orangeTeam[$ocounter] .'</div>
                                            <div class="ora_head_2"><input class="orangeGoal" type="number" id="' . $username .'" min="0" max="15" name="' . $username .'" 
                                            onkeydown="return (event.keyCode!=107&&event.keyCode!=109&&event.keyCode!=69&&event.keyCode!=110);" value="0"></div>
                                        </div>';
                                  // next player
                                  $ocounter++;
                              }
                          }
                          
                        ?>
                    </div>
                    
                    <div class="hr"></div>  <!--Line divider-->
                    
                    <!--Final Score Headings-->
                    <div class="profile_row">
                        <div class="match_col_1"></div>
                        <div class="goals_scored_yel"><b>Yellow Team:</b></div>
                        <div class="match_col_1"></div>
                        <div class="goals_scored_ora"><b>Orange Team:</b></div>
                    </div>
                    
                    <!--Display final score-->
                    <div class="profile_row">
                        <div class="column_5">Final Score:</div>
                        <div class="goals_scored_2"><label name="yelGoals" class="final_score" value="0" id="sumYellow">0</label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="yelGoals">
                        
                        <div class="v">v</div> 
                        
                        <div class="goals_scored_2"><label name="oraGoals" class="final_score" value="0" id="sumOrange">0</label></div>
                        <!--Labels not submitted with form so create hidden input field-->
                        <input type="hidden" name="oraGoals">
                    </div>
                    
                    <div class="hr"></div>     <!--Line divider-->
                    
                    <!--Display combo box of all players-->
                    <div class="profile_row">
                        <div class="column_5">Drop outs?</div>
                        <div class="player_col_2">
                                
                                <?php
                                    // List of all players
                                    $sql = $db->query("SELECT Username, CONCAT(FirstName, ' ', Surname) AS Name FROM tbl_User ORDER BY Name ASC");
                                    $players = $sql->fetch_row();
                                      
                                    // Create tool tip
                                    echo "<div class='tooltip'><span class='tooltiptext'>Hold 'Ctrl' to select multiple players</span>
                                           <select style='height: 200px; width: 180px' id='ddlMth' name='dropouts' multiple='multiple'>";
                                        
                                        // loop through results and output
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
                    
                    <!--Button to submit data to database-->
                    <div class="publish_gry">
                        <div class="tooltip">
                            <span class="tooltiptext">Submit results of match</span>
                            <button type="submit" name="update_results" onclick="playerGoals()" class="publish">Submit <i class="far fa-edit"></i></button>
                        </div>
                    </div>
                    
                </div>
            </div>
      </form>
    </div> <!-- /form -->
    
</body>
</html>