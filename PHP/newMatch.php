<?php 
/*-------------------------------------------*/
/*-- ADMIN PAGE: PHP to create new matches --*/
/*-------------------------------------------*/

    // connect to database
    include('server.php');
    session_start(); 
    
    // Check to see if user is logged in
    if (!isset($_SESSION['username'])) {
      $_SESSION['msg'] = "You must log in first";
      header('location: login.php');
    } else {
      // Identify username
      $username = $_SESSION['username']; 
      
      // Identify how many players are in system
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
	
    <!-- Styling sheets -->
    <link href="newMatch.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />	
    
    <!-- External sources -->
    <link rel="shortcut icon" type="image/png" href="logo.png"/>                                <!--website tab logo-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>   <!--menu favicons-->	
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>     <!--google charts-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    <!--load jQuery-->
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>                            <!--jQuery library-->
    <script src="myjs.js"></script>                 <!--my JS file-->
   
</head>

<body onload="createTable()">   <!-- create table at runtime of players -->
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
            <!--Display errors when submitting form-->
            <?php include('errors.php'); ?>
            <div class="newMatchTbl">
                <div class="gallery">
                    
                    <!--Data entry-->
                    <div class="profile_row">
                        <div class="col_1">Select date:</div>
                        <div class="col_2"><input type="date" class="input" name="match_date" value="<?php echo $match_date; ?>" required></input></div>
                    </div>
                    
                    <div class="hr"></div><!--line divider-->
                    
                    <div class="profile_row">
                        <div class="graphOfWeek">Select Teams:</div>
                    </div>
                    
                    <div class="profile_row" style="margin-bottom: 0.6em;">
                        <!--Auto generate teams-->
                        <div class="auto-gen">
                            <label class="gen">Auto Generate Teams</label>
                            <input type="checkbox" class="gen2" onchange="selectTeams(this,<?php echo $noOfPlayers; ?>)"/>
                        </div>
                        
                        <!--Sort table-->
                        <div class="sort-tbl">
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
                    
                    <!--Submit data to database-->
                    <div class="publish_gry">
                        <div class="tooltip">
                            <!--Add tooltip to help usability-->
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