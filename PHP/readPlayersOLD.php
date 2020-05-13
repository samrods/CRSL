<?php include('server.php') ?>
<!doctype html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--External sources -->
    <link href="check.css" rel="stylesheet" type="text/css" />
	<link href="styling.css" rel="stylesheet" type="text/css" />
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>
	<title>CRSL Football 5 a-side</title>
</head>
<body>

<h1 class="title">CRSL Football 5 a-side</h1>

    <div class="topSection">
        <div class="title3">
          	<!-- notification message -->
          	<?php if (isset($_SESSION['success'])) : ?>
              <div class="error success" >
              	<h3>
                  <?php 
                  	echo $_SESSION['success']; 
                  	unset($_SESSION['success']);
                  ?>
              	</h3>
              </div>
          	<?php endif ?>
        
            <!-- logged in user information -->
            <?php  if (isset($_SESSION['username'])) : ?>
            	<p class="title2">Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
            <?php endif ?>
        </div>
    </div>
    
    <div>
        <nav class="topnav">
            <ul>
                <li><a href="https://slr46.brighton.domains/Registration/index.php">Home <i class="fas fa-home"></i></a></li>
                <li><a href="https://slr46.brighton.domains/readPlayers.php">New Match <i class="far fa-file"></i></a></li>
                <li><a href="https://slr46.brighton.domains/Registration/profile.php">My Profile <i class="fas fa-id-card"></i></a></li>
                <li><a href="https://slr46.brighton.domains/Registration/profile_gallery.php">All Profiles <i class="fas fa-users"></i></a></li>
                <div class ="lgn">
                    <!-- Logout button -->
                    <?php  if (isset($_SESSION['username'])) : ?>
                    	<li><a href="index.php?logout='1'" style="color: white;">Logout <i class="fas fa-power-off"></i></a></li>
                    <?php endif ?>
                    <?php  if (!isset($_SESSION['username'])) : ?>
                    	<li><a href="index.php?logout='1'" style="color: white;">Login <i class="fas fa-power-off"></i></a></li>
                    <?php endif ?>
                </div>
            </ul>
        </nav>
    </div>

<div class="form"> 
    <h2 class="caption">Input New Match!</h2>
    
    <form method="post" action="readPlayers.php">
      	<?php include('errors.php'); ?>
      	
      	<div class="top-row-stats">   
            <div class="field-wrap">
                <label>Match Date<span class="req"> *</span></label>
                <input type="date" name="matchDate" class="input" required/>
            </div> 
        
            <!--Only allow numerical values to be entered-->
            <div class="field-wrap"> 
                <label>Home Goals<span class="req"> *</span></label>                     
                <select name="home" class="input" required>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>     
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option> 
                </select> 
            </div>
            
            <div class="field-wrap">                    
                <label>Away Goals<span class="req"> *</span></label>                     
                <select name="away" class="input" required>
                  <option value="0">0</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>     
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option> 
                </select> 
            </div>
        </div> <!-- split row -->  
        
        <button type="submit" name="submit_newMatch" class="btn-input">Submit</button>
        
    </form>

    <form method="post" action="readPlayers.php">

        <div class="field-wrap">					
            <label>List of players:<span class="req"></span></label>
        </div>  
    
    <!--Display tbl_Users to show all players--> 
    <?php
    
    // MySQL connections
    $servername = "brighton";
    $username   = "slr46_select";
    $password   = "Simdur2n";
    $db         = "slr46_CI301";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $db);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);	
    } 
    
    // Return list of player names
    $sql = "SELECT * FROM tbl_User ORDER BY FirstName ASC";
    $result = $conn->query($sql);
    
    // Create table
    echo '<table border="0" cellspacing="2" cellpadding="1" style="color:white; background-color: #708090; width:100%"> 
          <tr> 
              <th style="text-align:left;"> <b>First Name </b></th> 
              <th style="text-align:left;"> <b>Surname </b></th> 
              <th style="text-align:left;"> <b>Home Team </b></th> 
              <th style="text-align:left;"> <b>Away Team </b></th>
              <th style="text-align:left;"> <b>Drop out? </b></th> 
              <th style="text-align:left;"> <b>Goal Scored </b></th>
          </tr>';
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $firstname = $row["FirstName"];
            $surname = $row["Surname"];
            
            echo '<tr> 
                      <td style="width: 12%; height: 100%;">' . $firstname . '</td> 
                      <td style="width: 15%; height: 100%;">' . $surname . '</td>
                      
                      <td style="width: 15%; height: 100%;">
                        <label class="container">
                 		    <input type="checkbox" name="check_list[]" value="Home">
                  			<span class="checkmark"></span>
                		</label>
                      </td>
                      <td style="width: 15%; height: 100%;">
                        <label class="container">
                 		    <input type="checkbox" name="check_list[]" value="Away">
                  			<span class="checkmark"></span>
                		</label>
                      </td>
                      <td style="width: 15%; height: 100%;">
                        <label class="container">
                 		    <input type="checkbox" name="check_list[]" value="Dropped">
                  			<span class="checkmark"></span>
                		</label>
                      </td>
                      <td style="width: 15%; height: 100%;">
                        <input type="number" name="check_list[]" value="noOfGoals" min="0" step="1" style="color:black; background-color: white; width:100%; border-color: black;" />
                      </td>
                  </tr>';
        }
    } else {
       alert("Issue loading tbl_User");
    }
    
    if(isset($_POST['submit_newMatch'])) {    //to run PHP script on submit
            if(!empty($_POST['check_list'])) {
                // Loop to store and display values of individual checked checkbox.
               
                foreach($_POST['check_list'] as $selected){
                     //echo $selected . "<br> ";
                     //alert($selected . "<br> ");
                     alert("Hello");
                }
            }   
        }
    
    // Pop up message box
    function alert($msg) {
        echo "<script type='text/javascript'>alert('$msg');</script>";
    }
    
    $result->free();
    $conn->close();
    ?>
      
    </form>

</div> <!-- /form -->

</body>
</html>

