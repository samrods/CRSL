<!-------------------->
<!-- Main Home page -->
<!-------------------->

<?php 
    //Connect to database
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
    
    /*Retrieve data from relevant views*/
    
    // Top 10 Goal Scorers with Average
    $result = $db->query("SELECT * FROM view_totalGoals LIMIT 10");
    $avg = $db->query("SELECT * FROM view_averageGoalsScored");
    $res = mysqli_fetch_row($avg);
    $avgGoals = $res[0];
    
    // Top 5 appearances 
    $result2 = $db->query("SELECT * FROM view_totalApps LIMIT 5");
    
    // Most games won 
    $result3 = $db->query("SELECT * FROM view_totalWins LIMIT 5");
    
    // Department Appearances
    $result5 = $db->query("SELECT * FROM view_departmentApps");
    
    // Most Active users of website
    $result6 = $db->query("SELECT * FROM view_activityLog");
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>CRSL Football 5 a-side</title>
	
    <!--Styling sheets-->
	<link href="new_style.css" rel="stylesheet" type="text/css" />	
	<link href="admin.css" rel="stylesheet" type="text/css" />
	
	<!--External sources -->
	<link rel="shortcut icon" type="image/png" href="logo.png"/>            <!--website favicon-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>       <!--menu favicons-->
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>                                <!--jQuery library-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>         <!--Google charts library-->
    <script src="myjs.js"></script>                                         <!--my JS file-->
    
    <!--Google Charts JS-->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      // Output graphs
      function drawChart() {
          // Get Department Appearances 
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
        
        // Pie chart showing Department split
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

        // Draw graphs with styling options using JS libs
        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
        var chart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
        chart2.draw(data2, options2);
      }
    </script>
    
    <!--2nd Google Chart - Bar Chart-->
    <script>
      google.charts.load('current', {'packages': ['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      // Top goal scorers with average
      function drawVisualization() {
        var data = google.visualization.arrayToDataTable([
          ['Name', 'Total Goals', 'Average'],
          <?php
              if($result->num_rows > 0){
                  while($row = $result->fetch_assoc()){
                    echo "['".$row['Player']."', ".$row['ScoredGoals'].", ".$avgGoals."],";
                  }
              }
          ?>
        ]);
        
        // Styling options
        var options = {
          title: 'Goals Scored v Average',
          legend: 'out',
          // rename axes
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
    
        // Draw data using JS libs
        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
</head>

<body>
    <h1 class="title">CRSL Football 5 a-side</h1>
    <!--Load Navigation bar with username-->
    <div id="nav-placeholder"></div>
    <script>
        $(function(){
          $("#nav-placeholder").load("nav.php");
        });
    </script>
 
    <!--Main content-->
    <div class="profile_row">
        <div class="profile_row_2">
            <h2 class="stats-title">Statistics</h2>
        </div>
        
        <!--Drop down box to display QR codes-->
        <select name="qr_codes" class="qr_codes" onchange="showQR(this.value)">
            <option selected disabled hidden>Display QR Code:</option>
            <option value="apps">Most Appearances</option>
            <option value="goalsSc">Goals Scored</option>
            <option value="tgoalsSc">Team Goals Scored</option>
            <option value="tGoalsCo">Team Goals Conceded</option>
            <option value="wins ">Most Wins</option>
        </select>
        <div class="profile_row">
            <!--load content from displayQR.php-->
            <div id="imgQR" class="QR"></div> 
        </div>
    </div>
    
    <!--Responsive grid-->
    <div class="grid_index">
        <div class="responsive">
            <div class="gallery">
                <!--Pie chart showing Department split-->
                <div id="piechart" class="gc_graph"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <!--Pie chart showing Department split-->
                <div id="piechart2" class="gc_graph"></div>
            </div>
        </div>
        
        <div class="responsive">
            <div class="gallery">
                <!--Top goal scorers with average-->
                <div id="chart_div" class="gc_graph"></div>
            </div>
        </div>
        
        <!--Display screenshot from Power Bi-->
        <img src="images\\Screenshot\\Department_Goals.jpg" class="img" alt="No picture available">
        
    </div>
</body>
</html>