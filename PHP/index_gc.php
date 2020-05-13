<?php 
/*-----------------------------------*/
/*-- Main Home page - Google Charts--*/
/*-----------------------------------*/

    // Connect to database
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
    
    // Top 10 Goal Scorers with Average
    $result = $db->query("SELECT * FROM view_totalGoals LIMIT 10");
    $avg = $db->query("SELECT * FROM view_averageGoalsScored");
    $res = mysqli_fetch_row($avg);
    $avgGoals = $res[0];
    
    // Top 10 Goal Scorers
    $result2 = $db->query("SELECT * FROM view_totalGoals LIMIT 10");
    // Top 10 appearances 
    $result3 = $db->query("SELECT * FROM view_totalApps LIMIT 10");
    // Top 10 games won 
    $result4 = $db->query("SELECT * FROM view_totalWins LIMIT 10");
    
    // Top 10 appearances with Average
    $avg2 = $db->query("SELECT * FROM view_averageGoalsScored");
    $res2 = mysqli_fetch_row($avg2);
    $avgApps = $res2[0];
    
    // Department Appearances
    $result5 = $db->query("SELECT * FROM view_departmentApps");
    
    // Most Active users of website
    $result6 = $db->query("SELECT * FROM view_activityLog");
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Google Charts</title>
	
    <!--Styling sheets -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />	
	
	<!--External sources -->
	<link rel="shortcut icon" type="image/png" href="logo.png"/>            <!--website favicon-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>   <!--menu favicons-->
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>                            <!--jQuery library-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>     <!--Google Charts library-->
    <script src="myjs.js"></script>                                         <!--my JS file-->
    
    <!--Google charts JS-->
    <script>
      google.charts.load('current', {'packages': ['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);
      
      // VERTICAL BAR CHARTS
      function drawVisualization() {
        var data = google.visualization.arrayToDataTable([
          ['Name', 'Total Goals', 'Average'],
          // Top goal scorers with average
          <?php
              if($result->num_rows > 0){
                  while($row = $result->fetch_assoc()){
                    echo "['".$row['Player']."', ".$row['ScoredGoals'].", ".$avgGoals."],";
                  }
              }
          ?>
        ]);
    
        // Styling options for graphs
        var options = {
          title: 'Goals Scored v Average',
          legend: 'out',
          //align axes
          vAxis: {
            title: 'Goals Scored'
          },
          hAxis: {
            title: 'Player'
          },
          seriesType: 'bars',
          series: {
            1: {
              type: 'area'
            }
          }
        };
    
        // Draw and display charts
        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
      
      // HORIZONTAL BAR CHARTS
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
            
        // Top 5 goal scorers
        var data = google.visualization.arrayToDataTable([
          ['Name', 'Total Goals'],
          <?php
          if($result2->num_rows > 0){
              while($row = $result2->fetch_assoc()){
                echo "['".$row['Player']."', ".$row['ScoredGoals']."],";
              }
          }
          ?>
        ]);
        
        // Top 5 Appearances
        var data2 = google.visualization.arrayToDataTable([
          ['Player', 'Appearances'],
          <?php
          if($result3->num_rows > 0){
              while($row = $result3->fetch_assoc()){
                echo "['".$row['Player']."', ".$row['Apps']."],";
              }
          }
          ?>
        ]);
        
        // Top 5 winning players 
        var data3 = google.visualization.arrayToDataTable([
          ['Player', 'No Of Wins'],
          <?php
          if($result4->num_rows > 0){
              while($row = $result4->fetch_assoc()){
                echo "['".$row['Player']."', ".$row['TotalWins']."],";
              }
          }
          ?>
        ]);
        
        // Override options
        var options = {};
        
        // Top Goalscorers Bar Chart
        var topGoalsBar_options = {
            title:'Top Goal Scorers (goals)',
            //modify axes
            vAxis: {
                title: 'Player'
            },
            height: 450,
            legend: 'out',
            hAxis: {
                minValue: 0,
                title: 'Goals Scored'
            }
        };
        
        // Most Appearances Bar Chart
        var mostAppsBar_options = {
            title:'Most Appearances (no.)',
            height: 450,
            legend: 'out',
            hAxis: {
            minValue: 0
            }
        };
        
        // Most Wins Bar Chart
        var mostWinsBar_options = {
            title:'Most Wins (no.)',
            height: 450,
            legend: 'out',
            hAxis: {
            minValue: 0
            }
        };
        
        // Draw Bar Chart - Top 5 Goalscorers
        var barchart = new google.visualization.BarChart(document.getElementById('barchart'));
        barchart.draw(data, topGoalsBar_options);
        
        // Draw Bar Chart - Top 5 Appearances 
        var barchart2 = new google.visualization.BarChart(document.getElementById('barchart2'));
        barchart2.draw(data2, mostAppsBar_options);
        
        // Draw Bar Chart - Top 5 Winners 
        var barchart3 = new google.visualization.BarChart(document.getElementById('barchart3'));
        barchart3.draw(data3, mostWinsBar_options);
    }
    </script>
    
    <!--PIE CHARTS-->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // Load data for department apps
        var data = google.visualization.arrayToDataTable([
          ['Department', 'Appearances'],
          <?php
          if($result5->num_rows > 0){
              while($row = $result5->fetch_assoc()){
                echo "['".$row['Department']."', ".$row['Total_Apps']."],";
              }
          }
          ?>
        ]);
        
        // Load data for website usage
        var data2 = google.visualization.arrayToDataTable([
          ['Username', 'Points'],
          <?php
          if($result6->num_rows > 0){
              while($row = $result6->fetch_assoc()){
                echo "['".$row['Username']."', ".$row['Points']."],";
              }
          }
          ?>
        ]);

        // Styling options for graphs
        var options = {
          title: 'Number of Appearances by Department',
          legend: { position: 'right' }
        };
        var options2 = {
          title: 'Most Active Users of this site',
          legend: { position: 'right' }
        };
        
        // Pie Chart - Department Apps
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
        // Pie Chart - Website Usage
        var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart2.draw(data2, options2);
      }
    </script>
</head>

<body>
    <h1 class="title">CRSL Football 5 a-side</h1>
    
    <!--Navigation bar with username-->
    <div id="nav-placeholder"></div>
    <script>
    /*Load navigation bar from nav.php*/
        $(function(){
          $("#nav-placeholder").load("nav.php");
        });
    </script>
    
    <!--Main webpage content-->    
    <div class="profile_row">
        <div class="profile_row_2">
            <h2 class="stats-title">Google Charts</h2>
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
        <div class="profile_row">
            <!--Load QR code functionality from displayQR.php-->
            <div id="imgQR" class="QR"></div>
        </div>
    </div>
    
    <!--Responsive grid-->
    <div class="grid_index">
        <div class="responsive">
            <div class="gallery">
                <!--Top goal scorers with average-->
                <div id="chart_div" class="gc_graph"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <!--Top goal scorers-->
                <div id="barchart" class="gc_graph"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <!--Top appearances-->
                <div id="barchart2" class="gc_graph"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <!--Top wins-->
                <div id="barchart3" class="gc_graph"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <!--Pie chart showing Department split-->
                <div id="piechart" class="gc_graph"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <!--Pie chart showing most active users on site-->
                <div id="piechart2" class="gc_graph"></div>
            </div>
        </div>
         
    </div>
</body>
</html>