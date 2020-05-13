<!-------------------------------->
<!--PHP to enable password reset-->
<!-------------------------------->

<?php 
    // Connect to database
    include('server.php') 
?>

<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Forgot Password</title>
    
    <!-- Styling sheets -->
	<link href="new_style.css" rel="stylesheet" type="text/css" />
	<link href="admin.css" rel="stylesheet" type="text/css" />	
	
	<!--External sources -->
	<link rel="shortcut icon" type="image/png" href="logo.png"/>            <!--Website favicon-->
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>        <!--my JS file-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>	<!--menu favicons-->	
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2.3.2/dist/email.min.js"></script>    <!--email library functionality-->
	
    <!--Function to enable emails to be sent-->
    <script type="text/javascript">
        (function(){
            emailjs.init("user_5jYdOwHXveNPedmgnFPp2");
        })();
    </script> 
</head>

<body>
    <h1 class="title">CRSL Login Dashboard</h1>
    
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
    
    <!--Navigation bar-->
    <div class="navbar">
        <!--Display buttons, but cannot be accessed from this webpage -->
        <a href="https://slr46.brighton.domains/Registration/index.php">Home <i class="fas fa-home"></i></a>
        <a href="https://slr46.brighton.domains/Registration/profile.php">My Profile <i class="fas fa-id-card"></i></a>
        <a href="https://slr46.brighton.domains/Registration/profile_gallery.php">Gallery <i class="fas fa-users"></i></a>
        <a href="https://slr46.brighton.domains/Registration/matchDay.php">Match Day <i class="fas fa-futbol"></i></a>
        <?php  if (!empty($exists)) : ?>
        	<!--Display admin button for relevant users-->
        	<div class="dropdown">
                <button class="dropbtn">Admin <i class="fa fa-caret-down"></i></button>
                <div class="dropdown-content">
                    <a href="https://slr46.brighton.domains/Registration/newMatch.php">New Match</a>
                    <a href="https://slr46.brighton.domains/Registration/results.php">Results</a>
                </div>
            </div>
        <?php endif ?> 
        
        <div class ="lgn">
            <!-- Logout button -->
            <?php  if (isset($_SESSION['username'])) : ?>
                <a href="index.php?logout='1'">Logout <i class="fas fa-power-off"></i></a>
            <?php endif ?>
            <!--Change text based on login status-->
            <?php  if (!isset($_SESSION['username'])) : ?>
                <a href="index.php?logout='1'">Login <i class="fas fa-power-off"></i></a>
            <?php endif ?>
        </div>
    </div>
    
    <!--Create form to reset password-->
    <div class="form">
        <div class="login-wrap">
            <div class="login-html">
                <div class="login-form">
                    
                    <h2 class="caption">Reset Password!</h2>
                    
                    <form method="post" action="login.php">
                        <!--Log any errors-->
                        <?php include('errors.php'); ?>
                        
                            <!--Username identifies the user-->
                            <div class="tooltip">
                                <span class="tooltiptext">Your 3-letter code</span>
                                <label>Username<span class="req"> *</span></label>
                                <input type="text" name="username" class="input" maxlength="4" value="<?php echo $username; ?>" required>
                            </div>
                            <div class="field-wrap"></div>
                            
                            <!--Email address (does not have to be Clarksons for testing purposes)-->
                            <div class="field-wrap">
                                <label>Email<span class="req"> *</span></label>
                                <input type="email" name="email" class="input" value="<?php echo $email; ?>" required>
                            </div>
                            
                            <!--Submit to database-->
                            <button type="submit" name="send_message_btn" class="button button-forget">Send New Password</button>
                            
                            <div class="hr"></div>  <!--line divider-->
                            
                      	    <!--Webpage navigation-->
                            <p style="width: 100%">
                                <a class="navbtn-left" href="login.php">Sign in</a>
                                <a class="navbtn-right" href="register.php">Sign up</a>
                            </p>
                            
                        </form>
                </div>
            </div>
        </div>
    </div> <!-- /form -->

</body>
</html>