<?php 
/*-------------------------------*/
/*-- PHP to control Login page --*/
/*-------------------------------*/

    //Connect to database
    include('server.php') 
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Login</title>
    
    <!-- Styling sheets -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />	
	<link href="admin.css" rel="stylesheet" type="text/css" />
	
	<!-- External sources -->
	<link rel="shortcut icon" type="image/png" href="logo.png"/>                                <!--website favicon-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	<!--menu favicons-->
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>                            <!--jQuery library-->
</head>

<body>
    <!--Display title-->
    <h1 class="title">CRSL Login Dashboard</h1>
    <!--Container to display session username-->
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
    
    <!--Load navigation bar-->
    <div class="navbar">
        <!--Web pages cannot be accessed until logged in-->
        <a href="https://slr46.brighton.domains/Registration/index.php">Home <i class="fas fa-home"></i></a>
        <a href="https://slr46.brighton.domains/Registration/profile.php">My Profile <i class="fas fa-id-card"></i></a>
        <a href="https://slr46.brighton.domains/Registration/profile_gallery.php">Gallery <i class="fas fa-users"></i></a>
        <a href="https://slr46.brighton.domains/Registration/matchDay.php">Match Day <i class="fas fa-futbol"></i></a>
        
        <?php  if (!empty($exists)) : ?>
        	<!--Display admin button for users with permission-->
        	<div class="dropdown">
                <button class="dropbtn">Admin <i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="https://slr46.brighton.domains/Registration/newMatch.php">New Match</a>
                    <a href="https://slr46.brighton.domains/Registration/results.php">Results</a>
                </div>
            </div>
        <?php endif ?> 
        
        <!--Align logout button to the right-->
        <div class ="lgn">
            <!-- Display button -->
            <?php  if (isset($_SESSION['username'])) : ?>
                <a href="index.php?logout='1'">Logout <i class="fas fa-power-off"></i></a>
            <?php endif ?>
            <!--Change text depending if user is logged in-->
            <?php  if (!isset($_SESSION['username'])) : ?>
                <a href="index.php?logout='1'">Login <i class="fas fa-power-off"></i></a>
            <?php endif ?>
        </div>
    </div>
    
    <!--Login form - main content-->
    <div class="form">
          <div class="login-wrap">
              <div class="login-html">
                  <div class="login-form">
                      
                      <!--Subtitle-->
                      <div>
                      	<h2 class="caption">Login</h2>
                      </div>
                    	
                      <!--form--> 
                      <form method="post" action="login.php">
                        <!--account for login errors-->
                      	<?php include('errors.php'); ?>
                      	
                      	<!--Data entry -->
                      	<div class="field-wrap">
                      		<label>Username<span class="req"> *</span></label>
                      		<!--Basic data validation-->
                      		<input type="text" name="username" class="input" maxlength="4" required autocomplete="off"/>
                      	</div>
                      	
                      	<div class="field-wrap">
                      		<label>Password<span class="req"> *</span></label>
                      		<!--Basic data validation-->
                      		<input type="password" name="password" class="input" required autocomplete="off"/>
                      	</div>
                      	
                      	<!--Submit valid results to database-->
                      	<button type="submit" name="login_user" class="button button-block">Log In</button>
                      	
                      	<!--Alternative navigation-->
                      	<p class="signLink">Not signed up yet? <a style="margin-left: 3em;" class="tab" href="register.php">Sign up</a></p>
                      	
                      	<div class="hr"></div>
                      	<p><a class="forgotpwd" href="forgot.php">Forgot Password</a></p>
                      </form>
              </div>
          </div>
        </div>
    </div> <!--/form-->

</body>
</html>