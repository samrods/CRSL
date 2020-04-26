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
      
      // Initialise variables
      $nickname  = "";
      $position  = "";
      $favteam   = "";
      $favplayer = "";
      $strength  = "";
      $weakness  = "";
      $profile   = "";
      $pic       = "";
      $index     = "";
      $noOfWins      = 0;
      $noOfApps      = 0;
      $noOfGoals     = 0;
      $noOfTeamGoals = 0;
      $noOfConceded  = 0;
      
      $winRatio = 0;
      $goalsPerGame = 0;
      $teamGoalsPerGame = 0;
      $teamGoalsConcededPerGame = 0;
      
      // Get user's department 
      $getDept = $db->query("SELECT * FROM view_userDepartment WHERE Username='$username'");
      $rowz = mysqli_fetch_row($getDept);
      $department = $rowz[1];
      
      // Collect user's information
      $sql = $db->query("SELECT * FROM tbl_UserExtraInfo WHERE username='$username'");
      
      /* Get field information for all fields */
      while ($userInfo = mysqli_fetch_assoc($sql)) {
          $nickname = $userInfo['Nickname'];
          $position = $userInfo['Position'];
          $favteam = $userInfo['Team'];
          $favplayer = $userInfo['FavPlayer'];
          $strength = $userInfo['Strength'];
          $weakness = $userInfo['Weakness'];
          $profile = $userInfo['AddProfile'];
          $pic = $userInfo['PicLocation'];
      }
      
    // Number of appearances
    $result4 = $db->query("SELECT Apps FROM view_totalApps WHERE Username LIKE '$username'");
    $info = mysqli_fetch_assoc($result4);
    $noOfApps = $info['Apps'];
    
    // Number of goals scored
    $result5 = $db->query("SELECT ScoredGoals FROM view_totalGoals WHERE Username LIKE '$username'");
    $info = mysqli_fetch_assoc($result5);
    $noOfGoals = $info['ScoredGoals'];
    
    // Number of wins
    $result6 = $db->query("SELECT TotalWins FROM view_totalWins WHERE Username LIKE '$username'");
    $info = mysqli_fetch_assoc($result6);
    $noOfWins = $info['TotalWins'];
    
    // Number of team goals
    $result7 = $db->query("SELECT TotalTeamGoals FROM view_totalTeamGoals WHERE Username LIKE '$username'");
    $info = mysqli_fetch_assoc($result7);
    $noOfTeamGoals = $info['TotalTeamGoals'];
    
    // Number of team goals conceded
    $result8 = $db->query("SELECT TotalGoalsConceded FROM view_totalGoalsConceded WHERE Username LIKE '$username'");
    $info = mysqli_fetch_assoc($result8);
    $noOfConceded = $info['TotalGoalsConceded'];
    
    if (empty($noOfApps)) {
       $noOfApps = '0';
    } 
    
    if (empty($noOfGoals)) {
       $noOfGoals = '0';
       $goalsPerGame = 0;
    } else {
       // Goals per game
       $goalsPerGame = ($noOfGoals/$noOfApps);
       $goalsPerGame = number_format($goalsPerGame, 2);
    }
    
    if (empty($noOfWins)) {
       $noOfWins = '0';
       $winRatio = '0';
    } else {
       // Win %
       $winRatio = ($noOfWins/$noOfApps);
       $winRatio = number_format($winRatio, 2);
    }
    
    if (empty($noOfTeamGoals)) {
       $noOfTeamGoals = '0';
    } else {
       // Team goals per game
       $teamGoalsPerGame = ($noOfTeamGoals/$noOfApps);
       $teamGoalsPerGame = number_format($teamGoalsPerGame, 2);
    }
    
    if (empty($noOfConceded)) {
       $noOfConceded = '0';
    } else {
       // Team goals conceded per game
       $teamGoalsConcededPerGame = ($noOfConceded/$noOfApps);
       $teamGoalsConcededPerGame = number_format($teamGoalsConcededPerGame, 2);
    }
  
    // Check if user is in statistics table
    $search = $db->query("SELECT Username FROM tbl_Statistics WHERE Username = '$username'");
    $info = mysqli_fetch_assoc($search);
    $searchname = $info['Username'];
    
    // Insert user to statistics table
    if(empty($searchname)) {
       $sql = "INSERT INTO tbl_Statistics (Username, WinRatio, GoalsPerGame, TeamGoalsPerGame, TeamConcededPerGame)
               VALUES('$username', '$winRatio', '$goalsPerGame', '$teamGoalsPerGame', '$teamGoalsConcededPerGame')";
           
       mysqli_query($db, $sql);
    } else {
       // Update table with user stats
       $update ="UPDATE tbl_Statistics SET WinRatio = '$winRatio', GoalsPerGame = '$goalsPerGame', 
       TeamGoalsPerGame = '$teamGoalsPerGame', TeamConcededPerGame = '$teamGoalsConcededPerGame' WHERE Username = '$username'";
       
       mysqli_query($db, $update);
    }
    
    // Get MIN/MAX values 
    $weight1 = 22.5;
    $weight2 = 10;
    
    // Get MAX value of all win ratios 
    $search1 = $db->query("SELECT MAX(WinRatio) AS MaxWin FROM tbl_Statistics");
    $info1 = mysqli_fetch_assoc($search1);
    $maxwin = $info1['MaxWin'];
    $maxwin = number_format($maxwin, 2);
    
    // Get MAX value of goals per game
    $search2 = $db->query("SELECT MAX(GoalsPerGame) AS MaxGoalsPerGame FROM tbl_Statistics");
    $info2 = mysqli_fetch_assoc($search2);
    $maxgoalspergame = $info2['MaxGoalsPerGame'];
    $maxgoalspergame = number_format($maxgoalspergame, 2);
    
    // Get MAX value of TEAM goals per game
    $search3 = $db->query("SELECT MAX(TeamGoalsPerGame) AS MaxTeamGoalsPerGame FROM tbl_Statistics");
    $info3 = mysqli_fetch_assoc($search3);
    $maxteamgoalspergame = $info3['MaxTeamGoalsPerGame'];
    $maxteamgoalspergame = number_format($maxteamgoalspergame, 2);
     
    // Get MIN value of TEAM goals conceded
    $search4 = $db->query("SELECT MIN(TeamConcededPerGame) AS MinTeamGoalsConceded FROM tbl_Statistics");
    $info4 = mysqli_fetch_assoc($search4);
    $mingoalsconceded = $info4['MinTeamGoalsConceded'];
    $mingoalsconceded = number_format($mingoalsconceded, 2);
    
    // Get MAX value of number of appearances
    $search5 = $db->query("SELECT Appearances FROM view_mostAppearances LIMIT 1");
    $info5 = mysqli_fetch_assoc($search5);
    $maxApps = $info5['Appearances'];
    $maxApps = number_format($maxApps, 2);
    
    /* Performance Index
    * 
    * (22.5%) Win points +
    * (22.5%) Goal points + 
    * (22.5%) Team goal points + 
    * (22.5%) Team goals conceded points + 
    * (10%)   Games played points 
    */
    
    // Calcuate Win Points
    $winPoints = ($winRatio/$maxwin) * $weight1;
    $winPoints = number_format($winPoints, 2);
    
    // Calcuate Goal Points
    $goalPoints = ($goalsPerGame/$maxgoalspergame) * $weight1;
    $goalPoints = number_format($goalPoints, 2);
    
    // Calcuate Team Goal Points
    $teamGoalPoints = ($teamGoalsPerGame/$maxteamgoalspergame) * $weight1;
    $teamGoalPoints = number_format($teamGoalPoints, 2);
    
    // Calcuate Team Goals Conceded Points
    $teamGoalsConcededPerGamePoints = ($mingoalsconceded/$teamGoalsConcededPerGame) * $weight1;
    $teamGoalsConcededPerGamePoints = number_format($teamGoalsConcededPerGamePoints, 2);
   
    // Calcuate games played Points
    $gamesplayedPoints = ($noOfApps/$maxApps) * $weight2;
    $gamesplayedPoints = number_format($gamesplayedPoints, 2);
   
    // CALCULATE PERFORMACE INDEX
    $performanceIndex = $winPoints + $goalPoints + $teamGoalPoints + $teamGoalsConcededPerGamePoints + $gamesplayedPoints;
    $performanceIndex = number_format($performanceIndex, 1);
    
    if (empty($performanceIndex)) {
       $performanceIndex = '0';
    }
    
    // Add to statistics table
    $sql = $update ="UPDATE tbl_Statistics SET PerformanceIndex = '$performanceIndex' WHERE Username = '$username'";
    mysqli_query($db, $update);
    
    // Update log table
    //update_log("New user registered (". $username . ")");
    $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$username', 'Performance Index updated', '$date')";
    $result = mysqli_query($db, $log);
    mysqli_free_result($result);
    
    // Get CHART data
    $result5 = $db->query("SELECT Username, MatchID, GoalsConceded FROM tbl_UserGame WHERE Username = '$username' AND !ISNULL(GoalsConceded) ORDER BY MatchID ASC");
    $result7 = $db->query("SELECT Username, MatchID, GoalsScored FROM tbl_UserGame WHERE Username = '$username' AND !ISNULL(GoalsScored) ORDER BY MatchID ASC");
    
    // Department Appearances
    $result6 = $db->query("SELECT * FROM view_departmentApps");
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
	<title>My Profile</title>
	
    <!--External sources -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/png" href="logo.png"/>
    
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="myjs.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // Goals Conceded each match
        var data = google.visualization.arrayToDataTable([
          ['MatchID', 'GoalsConceded'],
          <?php
          if($result5->num_rows > 0){
              while($row = $result5->fetch_assoc()){
                echo "['".$row['MatchID']."', ".$row['GoalsConceded']."],";
              }
          } 
          ?>
        ]);
            
        var options = {
          title: 'Goals Conceded per match',
          curveType: 'function',
          vAxis: {
            title: 'Goals Conceded'
          },
          hAxis: {
            title: 'MatchID'
          },
          legend: { 
              position: 'right' 
          }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
        chart.draw(data, options);
      }
    </script>
    
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Department', 'Appearances'],
          <?php
          if($result6->num_rows > 0){
              while($row = $result6->fetch_assoc()){
                echo "['".$row['Department']."', ".$row['Total_Apps']."],";
              }
          }
          ?>
        ]);
        
        var options = {
          title: 'Number of Appearances by Department',
          legend: { position: 'right' },
          
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
        
      }
    </script>
    
    <script>
      google.charts.load('current', {'packages': ['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      // Top goal scorers with average
      function drawVisualization() {
        var data = google.visualization.arrayToDataTable([
          ['MatchID', 'GoalsScored'],
          
          <?php
              if($result7->num_rows > 0){
                  while($row = $result7->fetch_assoc()){
                    echo "['".$row['MatchID']."', ".$row['GoalsScored']."],";
                  }
              }
          ?>
        ]);
    
        var options = {
          title: 'Number of Goals Scored each week',
          legend: 'out',
          vAxis: {
            title: 'Goals Scored'
          },
          hAxis: {
            title: 'MatchID'
          },
          seriesType: 'bars',
        };
    
        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
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
    <div class="form"> 
        <?php  if (isset($_SESSION['username'])) : ?>
        	<h2 class="caption">Welcome back <?php echo $_SESSION['firstname']; ?></h2>
        <?php endif ?>
    
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <?php include('errors.php'); ?>
            <div class="grid_profile">
                <div class="gallery">
                    <!-- Profile pic -->
                    <div class="profile_row">
                        <img src="images\\<?php echo $pic; ?>" alt="No picture available">
                    </div>
                    <!--User info-->
                    <div class="profile_row">
                        <div class="col_1">Nickname:</div>
                        <div class="col_2"><?php echo $nickname; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Position:</div>
                        <div class="col_2"><?php echo $position; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Supports:</div>
                        <div class="col_2"><?php echo $favteam; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Fav Player:</div>
                        <div class="col_2"><?php echo $favplayer; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Strength:</div>
                        <div class="col_2"><?php echo $strength; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Weakness:</div>
                        <div class="col_2"><?php echo $weakness; ?></div>
                    </div>
                    
                    <div class="publish_gry" style="margin-bottom: 0;">
                        <p><a class="publish" href="editProfile.php">Edit <i class="far fa-edit"></i></a></p>
                    </div>
                    
                    <div class="hr" style="margin-top: 0;"></div>
                    <h2 class="caption" style="font-size: 22px;">Your Stats</h2>
                        
                    <div class="profile_row">
                        <div class="col_3">Department:</div>
                        <div class="col_4"><?php echo $department; ?></div>
                    </div>    
                    <div class="profile_row">
                        <div class="col_3">Appearances:</div>
                        <div class="col_4"><?php echo $noOfApps; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_3">Wins:</div>
                        <div class="col_4"><?php echo $noOfWins; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_3">No Of Goals:</div>
                        <div class="col_4"><?php echo $noOfGoals; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_3">No Of Team Goals:</div>
                        <div class="col_4"><?php echo $noOfTeamGoals; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_3">No Of Goals Conceded:</div>
                        <div class="col_4"><?php echo $noOfConceded; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_3" style="margin-bottom: 1em;">Performance Index:</div>
                        <div class="perform"><?php echo $performanceIndex; ?></div>
                    </div>
                </div>
                
                <div class="publish_gry">
                    <div class="tooltip">
                        <span class="tooltiptext">Submit your profile to the gallery for other players to see!</span>
                        <button type="submit" name="add_profile" class="publish" <?php if ($profile == '1'){ ?> disabled <?php } ?>>Add Profile to Gallery <i class="fas fa-share"></i></button>
                    </div>
                </div>
            
            </div>
      </form>
    </div> <!-- /form -->
    
    <h2 class="caption">Your Stats - Visual</h2>
  
    <div class="grid_index">
        <div class="gallery">
            <!--Top goal scorers with average-->
            <?php 
                if($result5->num_rows > 0){ ?>
                  <div id="chart_div" style="width: 90%; height: 500px; margin: 0.5em;"></div><?php
                }
            ?>
        </div>
    </div>
    <div class="grid_index">
        <div class="gallery">
            <!--Line chart to show Goals Conceded-->
            <?php 
                if($result7->num_rows > 0){ ?>
                  <div id="curve_chart" style="width: 90%; height: 500px; margin: 0.5em;"></div><?php
                }
            ?>
        </div>
    </div>
    <div class="grid_index">
        <div class="gallery">
            <!--Pie chart showing Department split-->
                <div id="piechart" style="width: 90%; height: 500px; margin: 0.5em;"></div>
        </div>
    </div>
    
</body>
</html>