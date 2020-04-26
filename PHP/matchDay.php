<?php 
  include('server.php');
  session_start(); 
  
  $yellowTeam = array(); 
  $orangeTeam = array(); 
  
  // Check to see if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  } else {
      
      // Check if new match has been created
      $sql = $db->query("SELECT * FROM view_futureMatch LIMIT 1");
      $match = $sql->fetch_row();
      
      if (!empty($match)) {
          // Get future match ID and date
          $nextMatchID = $match[0];
          $nextMatchDate = $match[1];
          $nextMatchDate = date("l d M Y", strtotime($nextMatchDate));
      } else {
          $nextMatchDate = "No upcoming fixtures";
      }
      
      // Get last match information
      $sql2 = $db->query("SELECT * FROM view_lastMatch");
      $match2 = $sql2->fetch_row();
      
      if (!empty($match2)) {
          $lastMatchID = $match2[0];
          $lastMatchDate = $match2[1];
          $lastMatchDate = date("l d M Y", strtotime($lastMatchDate));
          $yellowGoals = $match2[2];
          $orangeGoals = $match2[3];
      } else {
          echo "error loading last match info!";
      }
      
      // Check to see if yellow goals equals sum of yellow goals
      $check = $db->query("SELECT SUM(GoalsScored) AS TotalYellow 
                FROM tbl_UserGame ug 
                INNER JOIN tbl_User u 
                ON u.Username = ug.Username 
                WHERE MatchID = $lastMatchID 
                    AND GoalsScored <> 0 
                    AND Yellow <> 0
                GROUP BY ug.matchID");
      $check2 = $check->fetch_row(); 
      $totalYellow = $check2[0];
      
      if($totalYellow == $yellowGoals) {
          // They match, all is good
          $valid = true;
      } else {
          // Don't match, swith scores over
          $valid = false;
      }
  }
  
  // Destroy session and log the user out
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
  
  // Top Goal Scorers
  $result = $db->query("SELECT * FROM view_topGoalScorers");
  
  // Most Active users of website
  $result6 = $db->query("SELECT * FROM view_activityLog");

?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Match Day</title>
	
    <!--External sources -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />	
    <link rel="shortcut icon" type="image/png" href="logo.png"/>
    
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Username', 'Points'],
          <?php
          if($result6->num_rows > 0){
              while($row = $result6->fetch_assoc()){
                echo "['".$row['Username']."', ".$row['Points']."],";
              }
          }
          ?>
        ]);

        var options = {
          title: 'Most Active Users of this site',
          legend: { position: 'right' }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
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
    <div class="matchDay"> 
        <h2 class="caption">Match Day</h2>
                
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <?php include('errors.php'); ?>
            <div class="newMatchTbl">
                <div class="responsive">
                    <div class="gallery">
                        
                        <!--UPCOMING FIXTURE-->
                        <div class="profile_row">
                            <div class="graphOfWeek">Next Fixture:</div>
                        </div>
                        <div class="profile_row">
                            <div class="cols" style="font-weight: bold;">Date:</div>
                            <div class="player_col_1"><?php echo $nextMatchDate; ?></div>
                        </div>
                        <div class="profile_row">
                            <div class="cols" style="font-weight: bold;">Teams:</div>
                        </div>
                        
                        <div class="profile_row" style="text-align: center;">
                            <div class="cols3" style="color: yellow; font-weight: bold;">Yellow Team:</div>
                            <div class="cols3" style="color: orange; font-weight: bold;">Orange Team:</div>
                        </div>
                        
                        <div class="teamContainer">
                            <!--Container for two teams-->
                            <?php
                                // Identify who is playing in upcoming fixture
                                $sql = $db->query("SELECT u.Username, concat(u.FirstName, ' ', u.Surname) AS Name, MatchID, Yellow 
                                    FROM tbl_UserGame ug 
                                    INNER JOIN tbl_User u 
                                    ON u.Username = ug.Username 
                                    WHERE MatchID = $nextMatchID 
                                    ORDER BY Yellow DESC");
                                
                                // Player count 
                                $ycounter = 0;
                                $ocounter = 0;
                                    
                                // Store teams in arrays
                                while ($players = $sql->fetch_assoc()) {
                                    $username  = $players['Username'];
                                    $name  = $players['Name'];
                                    $yellow = $players['Yellow'];
                                    
                                    if ($yellow === "1") {
                                        // Add to yellow team array
                                        array_push($yellowTeam, $name);
                                        
                                        echo '<div class="cols2">' . $yellowTeam[$ycounter] . '</div>
                                              <div class="cols2"></div>';
                                    
                                        $ycounter++;        
                                    } else {
                                        // Add to orange team array
                                        array_push($orangeTeam, $name); 
                                        
                                        echo '<div class="cols2"></div>
                                              <div class="cols2">' . $orangeTeam[$ocounter] . '</div>';
                                              
                                        $ocounter++;
                                    }
                                }
                            ?>
                        </div>
                        
                        <div class="hr"></div>
                        
                        <!--PREVIOUS GAME-->
                        <div class="profile_row">
                            <div class="graphOfWeek">Previous Game:</div>
                        </div>
                        <div class="profile_row">
                            <div class="cols" style="font-weight: bold;">Date:</div>
                            <div class="player_col_1"><?php echo $lastMatchDate; ?></div>
                        </div>
                        <div class="profile_row">
                            <div class="cols" style="font-weight: bold;">Result:</div>
                        </div>
                        
                        <div class="profile_row" style="text-align: center;">
                            <div class="cols3" style="color: yellow; font-weight: bold;">Yellow Team:</div>
                            <div class="cols3" style="color: orange; font-weight: bold;">Orange Team:</div>
                        </div>
                        
                        <div class="profile_row" style="text-align: center;">
                            <div class="cols3" style="text-align: center; font-size: 18px;"><?php if($valid == true) { echo $yellowGoals; } else { echo $orangeGoals; } ?></div>
                            <div class="cols3" style="text-align: center; font-size: 18px;"><?php if($valid == true) { echo $orangeGoals; } else { echo $yellowGoals; } ?></div>
                        </div>
                        
                        <?php
                            // Identify goal scorers from previous match 
                            $sql = $db->query("SELECT concat(u.FirstName,' ', u.Surname) AS Name, Yellow, GoalsScored 
                                FROM tbl_UserGame ug 
                                INNER JOIN tbl_User u 
                                ON u.Username = ug.Username 
                                WHERE MatchID = $lastMatchID 
                                    AND GoalsScored <> 0 
                                ORDER BY Yellow DESC");
                            
                            // Display teams
                            while ($players = $sql->fetch_assoc()) {
                                  $name  = $players['Name'];
                                  $yellow = $players['Yellow'];
                                  $goals = $players['GoalsScored'];
                                  
                                  // Yellow team
                                  if ($yellow === "1") {
                                      echo '<div class="profile_row" style="text-align: center;">
                                                <div class="cols3" style="text-align: center;">' . $name . ' (x' . $goals . ')</div>
                                                <div class="cols3" style="text-align: center;"></div>
                                        </div>';
                                  } 
                                  // Orange Team
                                  else {
                                      echo '<div class="profile_row" style="text-align: center;">
                                                <div class="cols3" style="text-align: center;"></div>
                                                <div class="cols3" style="text-align: center;">' . $name .' (x' . $goals . ')</div>
                                        </div>';
                                  }
                              }
                            ?>
                        </div>  
                        
                    </div>
                </div>
                
            </div>
      </form>
    </div> <!-- /form -->
    
    <div class="profile_row">
        <div class="graphOfWeek">Graph of the Week:</div>
    </div>
    <div class="grid_index">
        <div class="gallery">
            <!--Line chart to show Goals Conceded-->
            <div id="piechart" style="width: 90%; height: 500px; margin: 0.5em;"></div>
        </div>
    </div>
    
</body>
</html>