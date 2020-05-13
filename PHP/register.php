<?php 
/*------------------------------------------------------*/
/*-- PHP to allow new users to sign up to the website --*/
/*------------------------------------------------------*/

    // connect to database
    include('server.php')
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sign Up</title>
    
    <!-- Styling sheets -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />
	<link href="admin.css" rel="stylesheet" type="text/css" />
	
	<!-- External sources -->
	<link rel="shortcut icon" type="image/png" href="logo.png"/>
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>
	<script type="text/javascript" src="myjs.js"></script>
</head>
<body>
    
    <h1 class="title">CRSL Login Dashboard</h1>
    
    <!--Section to determine log in status-->
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
    <!--Navigation menu-->
    <div class="navbar">
        <!--Web pages cannot be accessed until user logs in-->
        <a href="https://slr46.brighton.domains/Registration/index.php">Home <i class="fas fa-home"></i></a>
        <a href="https://slr46.brighton.domains/Registration/profile.php">My Profile <i class="fas fa-id-card"></i></a>
        <a href="https://slr46.brighton.domains/Registration/profile_gallery.php">Gallery <i class="fas fa-users"></i></a>
        <a href="https://slr46.brighton.domains/Registration/matchDay.php">Match Day <i class="fas fa-futbol"></i></a>
        <?php  if (!empty($exists)) : ?>
        	<!--Display admin button for relevant users-->
        	<div class="dropdown">
                <button class="dropbtn">Admin <i class="fa fa-caret-down"></i></button>
                <!--Drop down options-->
                <div class="dropdown-content">
                    <a href="https://slr46.brighton.domains/Registration/newMatch.php">New Match</a>
                    <a href="https://slr46.brighton.domains/Registration/results.php">Results</a>
                </div>
            </div>
        <?php endif ?> 
       
        <!--Align logout button to the right-->
        <div class ="lgn">
            <!-- Logout button -->
            <?php  if (isset($_SESSION['username'])) : ?>
                <a href="index.php?logout='1'">Logout <i class="fas fa-power-off"></i></a>
            <?php endif ?>
            <!--Change text based on login/logged out status-->
            <?php  if (!isset($_SESSION['username'])) : ?>
                <a href="index.php?logout='1'">Login <i class="fas fa-power-off"></i></a>
            <?php endif ?>
        </div>
    </div>
    
    <!--Main Registration form-->
    <div class="form">
        <div class="reg-wrap">
            <div class="login-html">
                <div class="login-form">
                    
                    <!--Heading-->
                    <div><h2 class="caption">Sign Up!</h2></div>
                    
                    <!--form-->
                    <form method="post" action="register.php">
                        <!--Display any errors when creating and submitting form-->
                        <?php include('errors.php'); ?>
                        
                        <!--Tooltip informing users to enter their 3 or 4 letter code-->
                        <div class="top-row">    
                            <div class="tooltip">
                                <span class="tooltiptext">Unique 3 or 4 letter code</span>
                                <label>Username<span class="req"> *</span></label>
                                <!--B asic validation max_length=4 -->
                                <input type="text" name="username" class="input" maxlength="4" onkeyup="emailhelp(this)" value="<?php echo $username; ?>" required>
                            </div>
                            
                            <!--Choose department-->
                            <div class="field-wrap">					
                                <label>Department<span class="req"> *</span></label>                       
                                <select name="dept" value="<?php echo $dept; ?>" required>
                                  <option disabled selected value> -- select an option -- </option>
                                  <option>Development</option>
                                  <option>Machinery</option>
                                  <option>Statistics</option>
                                  <option>Companies</option>
                                  <option>Infrastructure</option>
                                  <option>Valuations</option>
                                  <option>Research Analyst</option>
                                  <option>GIS</option>
                                  <option>Offshore</option>
                                  <option>Sales</option>
                                  <option>Research Services</option>
                                  <option>Other</option>                        
                                </select>                       	
                            </div>  
                        </div> <!-- /top-row --> 
                        
                        <!--Data entry-->
                        <div class="field-wrap">
                            <label>First Name<span class="req"> *</span></label>
                            <input type="text" name="firstname" class="input" value="<?php echo $firstname; ?>"required>	
                        </div>
                        <div class="field-wrap">
                            <label>Last Name<span class="req"> *</span></label>
                            <input type="text" name="surname" class="input" value="<?php echo $surname; ?>" required>
                        </div> 
                        
                        <!--Must be valid clarksons email address-->
                        <div class="field-wrap">
                            <div class="tooltip">
                                <span class="tooltiptext">Clarksons email address</span>
                                <label>Email Address<span class="req"> *</span></label>
                                <input id="val" type="email" name="email" class="input" value="<?php echo $email; ?>" required> 
                            </div>
                        </div>
                        
                        <!--Data validation - passwords must match-->
                        <div class="field-wrap">
                            <label>Password<span class="req"> *</span></label>
                            <input type="password" name="password_1" class="input" required>
                        </div>
                        <div class="field-wrap">
                            <label>Confirm password<span class="req"> *</span></label>
                            <input type="password" name="password_2" class="input" required>
                        </div>
                        
                        <!--Checkbox must be ticked-->
                        <div class="field-wrap">
                            <label for="terms">I agree to the 
                                <a href="terms.html" target="_blank"><u>terms and conditions</u></a><span class="req"> *</span>
                                <input type="checkbox" class="check" name="tandc" value="tandc" required>
                            </label>
                        </div>
                        
                        <!--Submit data to database-->
                        <div class="field-wrap">
                            <button type="submit" name="reg_user" class="button button-block">Register</button>
                        </div>
                        <div class="hr"></div>      <!--line divider-->
                  	    
                  	    <!--Allow navigation to login page-->
                        <p class="signLink">Already a member? <a style="margin-left: 3em;" class="tab" href="login.php">Sign in</a></p>
                        
                    </form>
               </div>
            </div>
        </div>
    </div>  <!--/form-->
    
</body>
</html>