<!----------------------------------------------->
<!--PHP to enable players to edit their profile-->
<!----------------------------------------------->

<?php 
    // Connect to database
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
      
      // Get user information for all fields 
      while ($userInfo = mysqli_fetch_assoc($sql)) {
          // Initialise variables
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
	
    <!--Stylesheets-->
    <link href="new_style.css" rel="stylesheet" type="text/css" />
    <link href="img_styling.css" rel="stylesheet" type="text/css" />
    <link href="admin.css" rel="stylesheet" type="text/css" />
    
    <!--External sources-->
    <link rel="shortcut icon" type="image/png" href="logo.png"/>            <!--website tab logo-->
    <script type="text/javascript" src="myjs.js"></script>                  <!--my JS file-->
	<script src="https://kit.fontawesome.com/6241a865f7.js" crossorigin="anonymous"></script>   <!--menu favicons-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>    <!--load jQuery-->
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>                            <!--jQuery library-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/emailjs-com@2.3.2/dist/email.min.js"></script>
</head>

<body>
    <h1 class="title">CRSL Football 5 a-side</h1>
    
    <!--Load navigation bar with username-->
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
        
        <!--Load form to interact with db-->
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <div class="grid_profile">
                <div class="gallery">
                    
                    <!-- Profile pic -->
                    <?php  if (!empty($pic)) : ?>
                    	<img src="images\\<?php echo $pic; ?>" alt="<?php echo $username; ?>">
                    	<div class="profile_row">
                    	    <!--Load file browser-->
                            <div class="col_1">Edit Photo:</div>
                            <div><input type="file" name="img" id="img" style="color: white;" value="images\<?php echo $pic; ?>"></div>
                        </div>
                    <?php endif ?>
                    
                    <!--No image available-->
                    <?php  if (empty($pic)) : ?>
                        <div class="profile_row">
                            <div class="col_1">Photo:</div>
                            <div> <!--Present alternative text-->
                                <input type="file" name="img" id="img" value="<?php echo $pic; ?>" alt="No image available">
                            </div>
                        </div>
                    <?php endif ?>
                    
                    <!--USER INFO section-->
                    <?php  if (isset($_SESSION['username'])) : ?>
                    <!--Display data retrieved from database -->
                    <div class="profile_row">
                        <div class="col_1">Nickname:</div>
                        <div class="col_2"><input class="input" name="nickname" value="<?php echo $nickname; ?>"></input></div>
                    </div>
                    
                    <div class="profile_row">
                        <div class="col_1">Position:</div>
                        <div class="col_2">
                            <!--Users can select pre-defined positions-->
                            <select name="pos" value="<?php echo $position; ?>" required>
                                <option disabled selected value> <?php echo $position; ?> </option>
                                <option>Goalkeeper</option>
                                <option>Left Back</option>
                                <option>Right Back</option>
                                <option>Center Half</option>
                                <option>CDM</option>
                                <option>CAM</option>
                                <option>Left Winger</option>
                                <option>Right Winger</option>
                                <option>Center Mid</option>
                                <option>Number 9</option>
                                <option>Number 10</option>
                                <option>Striker</option>
                            </select>    
                        </div>
                    </div>
                    
                    <!--Continue displaying user data-->
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
                    <?php endif ?>
                </div>
                
                <!--Submit to database-->    
                <div class="publish_gry">
                    <button class="publish" type="submit" name="edit-submit">Submit</button>
                </div>
            </div>
        </div>
      </form>
    </div> <!-- /form -->
    
</body>
</html>