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
                        <div class="col_2"><input type="date" class="input" name="match_date" value="<?php echo $match_date; ?>" ></input></div>
                    </div>
                    
                    <div class="hr"></div>
                        
                    <div class="profile_row">
                        <!--Auto generate teams----- YET TO DO-->
                        <div class="tooltip" style="float: left; margin-right: 0.8em;">
                            <span class="tooltiptext">Currently unavailable!</span>
                             <button type="submit" class="auto-gen" disabled>Auto-generate Teams</button>
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
                        <div class="graphOfWeek">Select Teams:</div>
                        <div id="txtHint"></div>
                    </div>
                    
                    
                    
                    
                    <button type="submit" name="new_match" onclick="sendData()" class="publish">Submit <i class="far fa-edit"></i></button>
                    
                    <!--<div class="publish_gry">
                        <div class="tooltip">
                            <span class="tooltiptext">Submit new match with teams.</span>
                            <button type="submit" name="new_match" class="publish">Submit <i class="far fa-edit"></i></button>
                        </div>
                    </div>-->
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
                    
                     <!--THE CONTAINER WHERE WE'll ADD THE DYNAMIC TABLE-->
                     <div id="cont"></div>
                     
                </div>
            </div>
      </form>
    </div> <!-- /form -->
    
    <!--Live Preview of teams-->
    <div class="newMatchForm" style="width: 75%;"> 
        <h2 class="caption">Live</h2>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            
            <div class="newMatchTbl">
                <div class="gallery" style="width: 100%;">
                    
                     <!--THE CONTAINER WHERE WE'll ADD THE DYNAMIC TABLE-->
                     <div id="cont2"></div>
                    
                </div>
            </div>
      </form>
    </div> <!-- /form -->
   
     
</body>
</html>