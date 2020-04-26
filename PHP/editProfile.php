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
      
      // Collect user's information
      $sql = $db->query("SELECT * FROM tbl_UserExtraInfo WHERE username='$username'");
      
      /* Get field information for all fields */
      while ($userInfo = mysqli_fetch_assoc($sql)) {
          $nickname  = $userInfo['Nickname'];
          $position  = $userInfo['Position'];
          $favteam   = $userInfo['Team'];
          $favplayer = $userInfo['FavPlayer'];
          $strength  = $userInfo['Strength'];
          $weakness  = $userInfo['Weakness'];
          $profile   = $userInfo['AddProfile'];
          $pic       = $userInfo['PicLocation'];
      }
      mysqli_free_result($result);
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
	<title>CRSL Football 5 a-side</title>
	
    <!--External sources -->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/png" href="logo.png"/>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2.3.2/dist/email.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="myjs.js"></script>
    
    <script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    
    function drawChart() {
        
        // Top goal scorers
        var data = google.visualization.arrayToDataTable([ ['Name', 'TotalGoals'],
          <?php
          if($result1->num_rows > 0){
              while($row = $result1->fetch_assoc()){
                echo "['".$row['Name']."', ".$row['TotalGoals']."],";
              }
          }
          ?>
        ]);
        
        // Override options
        var options = {};
        
        // Top Goalscorers Pie Chart
        var topGoalsPie_options = {
            title:'Top Goal Scorers (%)',
            width: 650,
            height: 450
        };
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
        
        // Draw Pie Chart - Top Goalscorers
        var piechart = new google.visualization.PieChart(document.getElementById('piechart'));
        piechart.draw(data, topGoalsPie_options);
        
        // Draw Bar Chart - Top Goalscorers
        var barchart = new google.visualization.BarChart(document.getElementById('barchart'));
        barchart.draw(data, topGoalsBar_options);
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
            <div class="grid_profile">
                <div class="gallery">
                    <!-- Profile pic -->
                    <?php  if (!empty($pic)) : ?>
                    	<img src="images\\<?php echo $pic; ?>" alt="<?php echo $username; ?>">
                    	
                    	<div class="profile_row">
                            <div class="col_1">Edit Photo:</div>
                            <div><input type="file" name="img" id="img" style="color: white;" value="images\<?php echo $pic; ?>" onchange="document.getElementById('file_name').value = this.value"></div>
                        </div>
                    	
                    <?php endif ?>
                    
                    <?php  if (empty($pic)) : ?>
                        <div class="profile_row">
                            <div class="col_1">Photo:</div>
                            <div>
                                <input type="file" name="img" id="img" style="color: white;" value="<?php echo $pic; ?>" onchange="document.getElementById('file_name').value = this.value">
                            </div>
                            
                        </div>
                    <?php endif ?>
                    
                    <!--<img src="<?php echo $pic; ?>" alt="No image available">-->
                    <?php  if (isset($_SESSION['username'])) : ?>
                    
                    <!--User info-->
                    <div class="profile_row">
                        <div class="col_1">Nickname:</div>
                        <div class="col_2"><input class="input" name="nickname" value="<?php echo $nickname; ?>"></input></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Position:</div>
                        <div class="col_2">
                            <select name="pos" value="<?php echo $position; ?>" required>
                                <option disabled selected value> <?php echo $position; ?> </option>
                                <option>Goalkeeper</option>
                                <option>Full Back</option>
                                <option>Center Back</option>
                                <option>Utility Defender</option>
                                <option>CDM</option>
                                <option>CAM</option>
                                <option>Winger</option>
                                <option>Center Mid</option>
                                <option>Number 9</option>
                                <option>Striker</option>
                            </select>    
                        </div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Supports:</div>
                        <div class="col_2"><input class="input" name="support" value="<?php echo $favteam; ?>"></input></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Fav Player:</div>
                        <div class="col_2"><input class="input" name="favplayer" value="<?php echo $favplayer; ?>"></input></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Strength:</div>
                        <div class="col_2"><input class="input" name="strength" value="<?php echo $strength; ?>"></input></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Weakness:</div>
                        <div class="col_2"><input class="input" name="weakness" value="<?php echo $weakness; ?>"></input></div>
                    </div>
                    
                    <!--<div class="hr"></div>
                    
                    <div class="profile_row">
                        <div class="col_1">Appearances:</div>
                        <div class="col_2"><?php echo $noOfApps; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Wins:</div>
                        <div class="col_2"><?php echo $noOfWins; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">No Of Goals:</div>
                        <div class="col_2"><?php echo $noOfGoals; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">No Of Team Goals:</div>
                        <div class="col_2"><?php echo $noOfTeamGoals; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">No Of Goals Conceded:</div>
                        <div class="col_2"><?php echo $noOfConceded; ?></div>
                    </div>
                    <div class="profile_row">
                        <div class="col_1">Performance Index:</div>
                        <div class="col_2"><?php echo $index; ?></div>
                    </div>-->
                    <?php endif ?>
                </div>
                    
                <div class="publish_gry">
                    <button class="publish" type="submit" name="edit-submit">Submit</button>
                </div>
            </div>
                
        </div>
      </form>
    </div> <!-- /form -->
    
    
    <!--Table and divs that hold the charts-->
    <!--<table class="columns">
      <tr>
        <td><div id="piechart" style="border: 1px solid #ccc; margin: 0.5em;"></div></td>
        <td><div id="barchart" style="border: 1px solid #ccc; margin: 0.5em;"></div></td>
      </tr>
    </table>-->
    		
</body>
</html>