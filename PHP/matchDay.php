<?php 
/*-----------------------------------*/
/*-- PHP to control match day page --*/
/*-----------------------------------*/

    // connect to database
    include('server.php');
    session_start(); 
    
    // initialise arrays to store teams
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
      
      // match is in the future 
      if (!empty($match)) {
          // Get future match ID and date
          $nextMatchID = $match[0];
          $nextMatchDate = $match[1];
          $nextMatchDate = date("l d M Y", strtotime($nextMatchDate));
      } else {
          // no match to show
          $nextMatchDate = "No upcoming fixtures";
      }
      
      // Get information of last match
      $sql2 = $db->query("SELECT * FROM view_lastMatch");
      $match2 = $sql2->fetch_row();
      
      // given there is a previous match...
      if (!empty($match2)) {
          // assign variables
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
      
      // Determine if values need to swap
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
	
    <!-- Styling sheets -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <!--<link href="admin.css" rel="stylesheet" type="text/css" />	-->
    
    <!-- External sources -->
    <link rel="shortcut icon" type="image/png" href="logo.png"/>                                <!--website tab logo-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>   <!--menu favicons-->	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>     <!--google charts-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    <!--load jQuery-->
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>                            <!--jQuery library-->
    
    <!--Google Charts for graph of the week-->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      // Initialise data
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

        // Styling options for graph
        var options = {
          title: 'Most Active Users of this site',
          legend: { position: 'right' }
        };
        // Load data to graph and output to screen
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
            <div class="newMatchTbl">
                <div class="responsive">
                    <div class="gallery">
                        
                        <!--UPCOMING FIXTURE-->
                        <div class="profile_row">
                            <div class="graphOfWeek">Next Fixture:</div>
                        </div>
                        <!--Display data-->
                        <div class="profile_row">
                            <div class="cols">Date:</div>
                            <div class="player_col_1"><?php echo $nextMatchDate; ?></div>
                        </div>
                        <div class="profile_row">
                            <div class="cols">Teams:</div>
                        </div>
                        <!--Team headings-->
                        <div class="profile_row_3">
                            <div class="cols_yellow">Yellow Team:</div>
                            <div class="cols_orange">Orange Team:</div>
                        </div>
                        
                        <!--Container for two teams-->
                        <div class="teamContainer">
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
                                        
                                        // Output individual players
                                        echo '<div class="teams">' . $yellowTeam[$ycounter] . '</div>
                                              <div class="teams"></div>';
                                    
                                        $ycounter++;        // increment players
                                    } else {
                                        // Add to orange team array
                                        array_push($orangeTeam, $name); 
                                        
                                        // Output individual players
                                        echo '<div class="teams"></div>
                                              <div class="teams">' . $orangeTeam[$ocounter] . '</div>';
                                              
                                        $ocounter++;    // increment players
                                    }
                                }
                            ?>
                        </div>
                        
                        <div class="hr"></div>      <!--line break-->
                        
                        <!--PREVIOUS GAME-->
                        <div class="profile_row">
                            <div class="graphOfWeek">Previous Game:</div>
                        </div>
                        <!--display data-->
                        <div class="profile_row">
                            <div class="cols">Date:</div>
                            <div class="player_col_1"><?php echo $lastMatchDate; ?></div>
                        </div>
                        <div class="profile_row">
                            <div class="cols">Result:</div>
                        </div>
                        
                        <!--Team headings-->
                        <div class="profile_row_3">
                            <div class="cols_yellow">Yellow Team:</div>
                            <div class="cols_orange">Orange Team:</div>
                        </div>
                        
                        <!--Determine score and swap values if they don't match-->
                        <div class="profile_row_3">
                            <div class="cols_2" ><?php if($valid == true) { echo $yellowGoals; } else { echo $orangeGoals; } ?></div>
                            <div class="cols_2" ><?php if($valid == true) { echo $orangeGoals; } else { echo $yellowGoals; } ?></div>
                        </div>
                        
                        <!--Load data from previous game-->
                        <?php
                            // Identify goal scorers
                                $sql = $db->query("SELECT concat(u.FirstName,' ', u.Surname) AS Name, Yellow, GoalsScored 
                                FROM tbl_UserGame ug 
                                INNER JOIN tbl_User u 
                                ON u.Username = ug.Username 
                                WHERE MatchID = $lastMatchID 
                                    AND GoalsScored <> 0 
                                ORDER BY Yellow DESC");
                            
                            // Display teams
                            while ($players = $sql->fetch_assoc()) {
                                  // Initialise variables
                                  $name  = $players['Name'];
                                  $yellow = $players['Yellow'];
                                  $goals = $players['GoalsScored'];
                                  
                                  // Yellow team [player and no of goals scored]
                                  if ($yellow === "1") {
                                      echo '<div class="profile_row_3">
                                                <div class="cols_3">' . $name . ' (x' . $goals . ')</div>
                                                <div class="cols_3"></div>
                                        </div>';
                                  } 
                                  // Orange Team [player and no of goals scored]
                                  else {
                                      echo '<div class="profile_row_3">
                                                <div class="cols_3"></div>
                                                <div class="cols_3">' . $name .' (x' . $goals . ')</div>
                                        </div>';
                                  }
                              }
                            ?>
                    </div>
                </div>
            </div>
    </div> <!-- /form -->
    
    <div class="profile_row">
        <!--Display graph of the week-->
        <div class="graphOfWeek">Graph of the Week:</div>
    </div>
    <div class="grid_index">
        <div class="gallery">
            <!--Line chart to show Goals Conceded-->
            <div id="piechart" class="gc_graph"></div>
        </div>
    </div>
    
</body>
</html>