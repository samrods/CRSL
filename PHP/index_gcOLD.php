<?php 
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
  
  // Top 5 Goal Scorers
  $result = $db->query("SELECT * FROM view_totalGoals LIMIT 5");
    
  // Top 5 appearances 
  $result2 = $db->query("SELECT * FROM view_totalApps LIMIT 5");
    
  // Most games won 
  $result3 = $db->query("SELECT * FROM view_totalWins LIMIT 5");
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>CRSL Football 5 a-side</title>
	
    <!--External sources -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />	
	<link href="admin.css" rel="stylesheet" type="text/css" />	
	<link rel="shortcut icon" type="image/png" href="logo.png"/>
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="myjs.js"></script>
    
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
        
        function drawChart() {
            
            // Top 5 goal scorers
            var data = google.visualization.arrayToDataTable([
              ['Name', 'Total Goals'],
              <?php
              if($result->num_rows > 0){
                  while($row = $result->fetch_assoc()){
                    echo "['".$row['Player']."', ".$row['ScoredGoals']."],";
                  }
              }
              ?>
            ]);
            
            // Top 5 Appearances
            var data2 = google.visualization.arrayToDataTable([
              ['Player', 'Appearances'],
              <?php
              if($result2->num_rows > 0){
                  while($row = $result2->fetch_assoc()){
                    echo "['".$row['Player']."', ".$row['Apps']."],";
                  }
              }
              ?>
            ]);
            
            // Top 5 winning players 
            var data3 = google.visualization.arrayToDataTable([
              ['Player', 'No Of Wins'],
              <?php
              if($result3->num_rows > 0){
                  while($row = $result3->fetch_assoc()){
                    echo "['".$row['Player']."', ".$row['TotalWins']."],";
                  }
              }
              ?>
            ]);
            
            // Override options
            var options = {};
            
            /*// Top Goalscorers Pie Chart
            var topGoalsPie_options = {
                title:'Top Goal Scorers (%)',
                width: 650,
                height: 450
            };*/
            // Top Goalscorers Bar Chart
            var topGoalsBar_options = {
                title:'Top Goal Scorers (goals)',
                width: 650,
                height: 450,
                legend: 'out',
                hAxis: {
                minValue: 0
                }
            };
            /*// Most Appearances Pie Chart
            var mostAppsPie_options = {
                title:'Most Appearances (%)',
                width: 650,
                height: 450
            };*/
            // Most Appearances Bar Chart
            var mostAppsBar_options = {
                title:'Most Appearances (no.)',
                width: 650,
                height: 450,
                legend: 'out',
                hAxis: {
                minValue: 0
                }
            };
            // Most Wins Bar Chart
            var mostWinsBar_options = {
                title:'Most Wins (no.)',
                width: 650,
                height: 450,
                legend: 'out',
                hAxis: {
                minValue: 0
                }
            };
            
            /*// Draw Pie Chart - Top 5 Goalscorers
            var piechart = new google.visualization.PieChart(document.getElementById('piechart'));
            piechart.draw(data, topGoalsPie_options);*/
            
            // Draw Bar Chart - Top 5 Goalscorers
            var barchart = new google.visualization.BarChart(document.getElementById('barchart'));
            barchart.draw(data, topGoalsBar_options);
            
           /* // Draw Pie Chart - Most Appearances
            var piechart2 = new google.visualization.PieChart(document.getElementById('piechart2'));
            piechart2.draw(data2, mostAppsPie_options);*/
            
            // Draw Bar Chart - Top 5 Appearances 
            var barchart2 = new google.visualization.BarChart(document.getElementById('barchart2'));
            barchart2.draw(data2, mostAppsBar_options);
            
            // Draw Bar Chart - Top 5 Winners 
            var barchart3 = new google.visualization.BarChart(document.getElementById('barchart3'));
            barchart3.draw(data3, mostWinsBar_options);
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
        
    <div class="profile_row">
        <div class="profile_row" style="width: 75%;">
            <h2 class="caption2" style="background: #00214d; font-style: italic; text-align: center; font-size: 26px; padding: 0.2em; margin: 0;">Google Charts</h2>
        </div>
        
        <select name="qr_codes" style="float: right; width: 23%; display: inline-block;" onchange="showQR(this.value)">
            <option selected disabled hidden>Display QR Code:</option>
            <option value="apps">Most Appearances</option>
            <option value="goalsSc">Goals Scored</option>
            <option value="tgoalsSc">Team Goals Scored</option>
            <option value="tGoalsCo">Team Goals Conceded</option>
            <option value="wins ">Most Wins</option>
        </select>
        <div class="profile_row">
            <!--Display different QR code at runtime-->
            <div id="imgQR" class="QR"></div>
        </div>
    </div>
    
    <div class="grid_index">
        <div class="responsive">
            <div class="gallery">
                <div id="piechart" style="margin: 0.5em;"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <div id="barchart" style="margin: 0.5em;"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <div id="barchart2" style="margin: 0.5em;"></div>
            </div>
        </div>
        <div class="responsive">
            <div class="gallery">
                <div id="piechart2" style="margin: 0.5em;"></div>
            </div>
        </div>
    </div>
            
        
    
    
    
    
  
    
   
    
    		
</body>
</html>