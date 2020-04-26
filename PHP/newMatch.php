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
      
      // Number of players
      $sql = $db->query("SELECT COUNT(*) AS Total FROM view_playerIndexes");
      $res = $sql->fetch_row();
      $noOfPlayers = $res[0];
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
	<title>New Match</title>
	
    <!--External sources -->
    <link href="newMatch.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />	
    <link href="test.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/png" href="logo.png"/>

	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="myjs.js"></script>
    <script>
        function selectTeams(checkbox, noOfPlayers) {
            
            if(checkbox.checked === true) {
                // [1,2,3,...,noOfPlayers]
                array = Array.from({ length: noOfPlayers }, (v, k) => k * 1);
                
                var shuffled = array.sort(function() {
                    return .5 - Math.random()
                });
                
                // Create 5 random numbers (players)
                for(i = 0; i <= 10; i++){
                	if (i <= 5) {
                	    var selected = shuffled.slice(0, i);
                    } else {
                        var selected2 = shuffled.slice(5, i);
                    }
                }
                
                // Select these players (yellow team)
                for (j = 0; j < selected.length; j++) {
                    document.getElementById("yellow" + selected[j]).checked = true;
                    var username = document.getElementById("yellow" + selected[j]).value;
                    var teamCol = "yellow";
                    
                    // HAVE USERNAMES HERE... NEED TO NOW GET IN PHP AND ADD TO DATABASE
                    getPlayers(username, teamCol);
                }
                
                // Select these players (orange team)
                for (j = 0; j < selected2.length; j++) {
                    document.getElementById("orange" + selected2[j]).checked = true;
                    var username = document.getElementById("orange" + selected2[j]).value;
                    var teamCol = "orange";
                    
                    // HAVE USERNAMES HERE... NEED TO NOW GET IN PHP AND ADD TO DATABASE
                    getPlayers(username, teamCol);
                }
                
            } else {
                console.log("Clear All");
                
                for (j = 0; j < noOfPlayers; j++) {
                    document.getElementById("yellow"+j).checked = false;
                }
                for (j = 1; j < noOfPlayers; j++) {
                    document.getElementById("orange"+j).checked = false;
                }
            }
        }
        
        // Add players to database
        function getPlayers(player, team) {
            console.log(player + ": " + team);
        }
    </script>
    
</head>

<body onload="createTable()">
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
        <h2 class="caption">Create New Match</h2>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <?php include('errors.php'); ?>
            <div class="newMatchTbl">
                <div class="gallery">
                    
                    <!--List of players-->
                    <div class="profile_row">
                        <div class="col_1">Select date:</div>
                        <div class="col_2"><input type="date" class="input" name="match_date" value="<?php echo $match_date; ?>" required></input></div>
                    </div>
                    
                    <div class="hr"></div>
                    
                    <div class="profile_row">
                        <div class="graphOfWeek">Select Teams:</div>
                    </div>
                    
                    <div class="profile_row" style="margin-bottom: 0.6em;">
                        <!--Auto generate teams-->
                        <div style="float: left; margin-right: 0.8em; width: 40%;">
                            <label class="gen">Auto Generate Teams</label>
                            <input type="checkbox" class="gen2" onchange="selectTeams(this,<?php echo $noOfPlayers; ?>)"/>
                        </div>
                        
                        <!--Sort table-->
                        <div style="float: right; margin-right: 0.8em;">
                            <select name="users" onchange="showUser(this.value)">
                                <option value="SELECT * FROM view_playerIndexes ORDER BY PerformanceIndex DESC">Order by Performance</option>
                                <option value="SELECT * FROM view_playerIndexes">Order by Player</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="profile_row">
                        <!--Load contents from displayPlayers php-->
                        <div id="txtHint"></div>
                    </div>
                    
                    <!--<button type="submit" name="new_match" class="publish">Submit <i class="far fa-edit"></i></button>-->
                    
                    <div class="publish_gry">
                        <div class="tooltip">
                            <span class="tooltiptext">Submit new match with teams.</span>
                            <button type="submit" name="new_match" class="publish">Submit <i class="far fa-edit"></i></button>
                        </div>
                    </div>
                </div>
            </div>
      </form>
    </div> <!-- form -->
    
    <!--Live Preview of teams-->
    <div class="newMatchForm" style="width: 75%;"> 
        <h2 class="caption">Preview Teams</h2>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <div class="newMatchTbl">
                <div class="gallery" style="width: 100%;">
                    
                     <!--Container to add dynamic table-->
                     <div id="cont"></div>
                     
                </div>
            </div>
      </form>
    </div> <!-- /form -->
    
</body>
</html>