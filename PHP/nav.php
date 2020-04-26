<?php 
  include('server.php');
  session_start(); 
  
  // Check to see if user is logged in
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  } else {
      $user = $_SESSION['username'];
      
      // Check admin permissions
      $check = "SELECT * FROM tbl_Admin WHERE Username = '$user'";
      $result = mysqli_query($db, $check);
      $exists = mysqli_fetch_assoc($result);
      mysqli_free_result($result);
     
      if (isset($_POST['request_admin'])) {
        $user = strtoupper(mysqli_real_escape_string($db, $_SESSION['username']));
      
        // Declare variables for Admin
        $recipient = "samrod09@hotmail.com";
        $subject = "Admin Request";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // Find details of user
        $sql = "SELECT Username, Firstname, Surname, EmailAddress FROM tbl_User WHERE Username = '$user'";
        $query = mysqli_query($db, $sql);
        $address = mysqli_fetch_row($query);
        $fname = $address[1];
        $sname = $address[2];
        $email = $address[3];
        
        $message = "<html>
        <body>
        <h1 style="."color:#002859;".">CRSL 5 a-side Football</h1>
          <h2 style="."color:#002859;".">" . $subject . "</h2>
          <p> " . $fname . ' ' . $sname . " (" . $user . ") has requested Admin permissions. If this is correct, please could you grant them in the database accordingly.</p>
          <p>If this is incorrect, please contact them on: 
              <u><a href="."mailto: . $email . ".">" . $email . "</a></u></p>
          
        </body>
        </html>";
        
        // Update log table
        //update_log("$username". " updated their password");
        $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user', 'Requested admin permission', '$date')";
        $result = mysqli_query($db, $log);
        mysqli_free_result($result);
        
        if (mail($recipient, $subject, $message, $headers)) {
            alert("Email has been to the administrator. They will get back to you shortly");
        } else {
            alert("Failed to send email. Please contact support.");
        }
      }
  }
  
  // Destroy session and log the user out
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>

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
	        <form method="post" action="">
	            <div class="title2">
    	            <button type="submit" name="request_admin" class="title4">Welcome <u><strong><?php echo $_SESSION['username']; ?></strong></u></button>
        	    </div>
	        </form>
        <?php endif ?>
    </div>
</div>

<div class="navbar">
    <div class="dropdown">
        <button class="dropbtn">
            <a href="https://slr46.brighton.domains/Registration/index.php" style="padding: 0.2em;">
                Home <i class="fas fa-home"></i> <i class="fa fa-caret-down"></i>
            </a>
        </button>
        <div class="dropdown-content">
            <a href="https://slr46.brighton.domains/Registration/index_ss.php">Screenshots</a>
            <a href="https://slr46.brighton.domains/Registration/index_pb.php">Power Bi</a>
            <a href="https://slr46.brighton.domains/Registration/index_gc.php">Google Charts</a>
        </div>
    </div>
    <!--<a href="https://slr46.brighton.domains/Registration/index.php">Home <i class="fas fa-home"></i></a>-->
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
            <a href="index.php?logout='1'" style="color: white;">Logout <i class="fas fa-power-off"></i></a>
        <?php endif ?>
        <?php  if (!isset($_SESSION['username'])) : ?>
            <a href="index.php?logout='1'" style="color: white;">Login <i class="fas fa-power-off"></i></a>
        <?php endif ?>
    </div>
</div>