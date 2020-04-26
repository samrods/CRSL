<?php
session_start();

// Initialise variables
$username  = "";
$errors    = array(); 
$date      = date("Y-m-d") . " " . date("h:i:s");

// Connect to MySQL database
$db = mysqli_connect('brighton', 'slr46_select', 'Simdur2n', 'slr46_CI301');
   
//--------------------------------------
//---------Register New User------------
//--------------------------------------
if (isset($_POST['reg_user'])) {
    
    // Create new user object
    class user {
		var $username; 
		var $firstname;
		var $surname;
		var $department;
		var $email;
		var $password_1;
		var $password_2;
		var $terms;
		
		// Set variables
		function set_username($new_user) { 
			$this->username = $new_user;  
 		}
 		function set_firstname($new_user) { 
			$this->firstname = $new_user;  
 		}
 		function set_surname($new_user) { 
			$this->surname = $new_user;  
 		}
 		function set_department($new_user) { 
			$this->department = $new_user;  
 		}
 		function set_email($new_user) { 
			$this->email = $new_user;  
 		}
 		function set_password_1($new_user) { 
			$this->password_1 = $new_user;  
 		}
 		function set_password_2($new_user) { 
			$this->password_2 = $new_user;  
 		}
 		function set_terms($new_user) { 
			$this->terms = $new_user;  
 		}
 		
 		// Return variables
   		function get_username() {
			return $this->username;
		}
		function get_firstname() {
			return $this->firstname;
		}
		function get_surname() {
			return $this->surname;
		}
		function get_department() {
			return $this->department;
		}
		function get_email() {
			return $this->email;
		}
		function get_password_1() {
			return $this->password_1;
		}
		function get_password_2() {
			return $this->password_2;
		}
		function get_terms() {
			return $this->terms;
		}
	} 
	
    $new_user = new user();
	$new_user->set_username(strtoupper(mysqli_real_escape_string($db, $_POST['username'])));
	$new_user->set_firstname(ucfirst(mysqli_real_escape_string($db, $_POST['firstname'])));
	$new_user->set_surname(ucfirst(mysqli_real_escape_string($db, $_POST['surname'])));
	$new_user->set_department(mysqli_real_escape_string($db, $_POST['dept']));
	$new_user->set_email(mysqli_real_escape_string($db, $_POST['email']));
	$new_user->set_password_1(mysqli_real_escape_string($db, $_POST['password_1']));
	$new_user->set_password_2(mysqli_real_escape_string($db, $_POST['password_2']));
	$new_user->set_terms(mysqli_real_escape_string($db, $_POST['tandc']));
	
	// Basic validation to ensure form is valid
	if (empty($new_user->get_username())) {
	    array_push($errors, "Username is required"); 
	}
    if (empty($new_user->get_firstname())) { 
      array_push($errors, "First name is required");
    }
    if (empty($new_user->get_surname())) { 
      array_push($errors, "Surname is required");
    }
    if (empty($new_user->get_department())) { 
      array_push($errors, "Select a department");
    }
    if (empty($new_user->get_email())) { 
      array_push($errors, "Email Address is required");
    }
    if (empty($new_user->get_password_1())) { 
      array_push($errors, "Password is required");
    }
    if (empty($new_user->get_terms())) {
        array_push($errors, "You must agree to terms and conditions");
    }
    
    // Passwords must match
	if ($new_user->get_password_1() != $new_user->get_password_2()) {
        array_push($errors, "The two passwords do not match");
    }
    
    // Clarksons email only
    $contain = '@clarksons.com';
    $contain2 = '@clarksons.co.uk';
    if (strpos($new_user->get_email(), $contain) !== false || strpos($new_user->get_email(), $contain2) !== false) {
    } else {
    	array_push($errors, "Email Address must be a valid Clarksons address");
    }
    
    // Check username does not already exist (PROCEDURAL)
    $user_check_query = "SELECT * FROM tbl_User WHERE username='" . $new_user->get_username() . "'";
    $result = mysqli_query($db, $user_check_query);
    $exists = mysqli_fetch_assoc($result);
    
    // If user already exists...
    if (!empty($exists)) {
        array_push($errors, "Username already exists");
    } else {
        // Everything filled out correctly
        if (count($errors) == 0) {
            
            // Get ID for department (PROCEDURAL)
            $getDept = "SELECT DeptID FROM tbl_Department WHERE DeptName LIKE '%" . $new_user->get_department() . "%'";
            $result = mysqli_query($db, $getDept);
            $rowz = mysqli_fetch_row($result);
            $deptID = $rowz[0];
            mysqli_free_result($result);
           
            // Encrypt password
            $password_hash = password_hash($new_user->get_password_1(), PASSWORD_BCRYPT);
            
            // Add new user to login and user tables 
            $query = "INSERT INTO tbl_Login (Username, Password, DateAdded) 
                VALUES('" . $new_user->get_username() . "', '$password_hash', '$date')";
            mysqli_query($db, $query);
          
            $query2 = "INSERT INTO tbl_User (Username, FirstName, Surname, EmailAddress, Department, Retired) 
                    VALUES('" . $new_user->get_username() . "', '" . $new_user->get_firstname() . "', '" . $new_user->get_surname() . "'
                    , '" . $new_user->get_email() . "', '$deptID', 0)";
            mysqli_query($db, $query2);
            
            // Get First name of this user (for profile purposes)
            $get_name = "SELECT FirstName FROM tbl_User WHERE username='" . $new_user->get_username() . "'";
            $result2 = mysqli_query($db, $get_name);
            $row = mysqli_fetch_row($result2);
            $userFirstname = $row[0];
            mysqli_free_result($result2);
            
            // Add session
            $query = "SELECT * FROM tbl_Login WHERE Username='" . $new_user->get_username() . "' AND Password='$password'";
            $results = mysqli_query($db, $query);
            $getID = mysqli_fetch_row($results);
            $loginID = $getID[0];
            
            $sesh = "INSERT INTO tbl_Session (LoginID, LoginTime) VALUES('$loginID', '$date')";
            $results = mysqli_query($db, $sesh);
            mysqli_free_result($results);
            
            // Update log table
            $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('" . $new_user->get_username() . "', 'New user registered', '$date')";
            $result = mysqli_query($db, $log);
            mysqli_free_result($result);
          
            $_SESSION['username'] = $new_user->get_username();
            $_SESSION['firstname'] = $userFirstname;
            $_SESSION['success'] = "You are now logged in";
            header('location: index.php');
        }
    }
}

//--------------------------------------
//---------User Login-------------------
//--------------------------------------
if (isset($_POST['login_user'])) {
    
    // Create new login object
    class login {
		var $username; 
		var $password;
		
		// Set variables
		function set_username($new_login) { 
			$this->username = $new_login;  
 		}
 		function set_password($new_login) { 
			$this->password = $new_login;  
 		}
 		
 		// Return variables
   		function get_username() {
			return $this->username;
		}
		function get_password() {
			return $this->password;
		}
	} 
	
    $new_login = new login();
	$new_login->set_username(strtoupper(mysqli_real_escape_string($db, $_POST['username'])));
	$new_login->set_password(mysqli_real_escape_string($db, $_POST['password']));
    
    // Basic validation
    if (empty($new_login->get_username())) {
      array_push($errors, "Username is required");
    }
    if (empty($new_login->get_password())) {
      array_push($errors, "Password is required");
    }
    
    if (count($errors) == 0) {
      // Get hashed password from database
      $query = "SELECT Password FROM tbl_Login WHERE Username='" . $new_login->get_username() . "'";
      $results = mysqli_query($db, $query);
      $getPs = mysqli_fetch_row($results);
      $loginPs = $getPs[0];
      
      // Verify password SECURELY
      if (password_verify($new_login->get_password(), $loginPs)) {
        // Correct password
        
        // Get First name of this user (for profile purposes)
        $get_name = "SELECT FirstName FROM tbl_User WHERE Username='" . $new_login->get_username() . "'";
        $result = mysqli_query($db, $get_name);
        $row = mysqli_fetch_row($result);
        $userFirstname = $row[0];
        mysqli_free_result($result);
          
        // Add session
        $sesh = "INSERT INTO tbl_Session (LoginID, LoginTime) VALUES('$loginID', '$date')";
        $results = mysqli_query($db, $sesh);
        mysqli_free_result($results);
        
        // Update log table
        $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('" . $new_login->get_username() . "', 'Logged in', '$date')";
        $result = mysqli_query($db, $log);
        mysqli_free_result($result);
        
        $_SESSION['username'] = $new_login->get_username();
        $_SESSION['firstname'] = $userFirstname;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
        
      } else {
        // Incorrect password
        array_push($errors, "Wrong username/password combination");
      }
    }
}

//--------------------------------------
//------Create new email object---------
//--------------------------------------
class email {
	var $email;
	var $username; 
    var $subject;
	var $headers;
	var $message;
	
	// Set variables
	function set_email($new_email) { 
		$this->email = $new_email;  
 	}
 	function set_username($new_email) { 
		$this->username = $new_email;  
 	}
 	function set_subject($new_email) { 
		$this->subject = $new_email;  
 	}
 	function set_header($new_email) { 
		$this->headers = $new_email;  
 	}
 	function set_message($new_email) { 
		$this->message = $new_email;  
 	}
 	
 	// Return variables
   	function get_email() {
		return $this->email;
	}
   	function get_username() {
		return $this->username;
	}
	function get_subject() {
		return $this->subject;
	}
	function get_header() {
		return $this->headers;
	}
	function get_message() {
		return $this->message;
	}
} 

//--------------------------------------
//---------Password Reset---------------
//--------------------------------------
if (isset($_POST['send_message_btn'])) {
    
    // Create a random password
    $gen_pass = generate_password(rand(8,12));
    
    // Encrypt password
    $password_hash = password_hash($gen_pass, PASSWORD_BCRYPT);
   
    // Create new email
    $new_email = new email();
    $new_email->set_username(strtoupper(mysqli_real_escape_string($db, $_POST['username'])));
    $new_email->set_email($_POST['email']);
    $new_email->set_subject("Password Reset");
    $new_email->set_header("MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n");
    $new_email->set_message("<html>
                            <body>
                            <h1 style="."color:#002859;".">CRSL 5 a-side Football</h1>
                              <h2 style="."color:#002859;".">" . $new_email->get_subject() . "</h2>
                              <p>You recently requested to reset your password, which you can find below.</p>
                              <p>Your new password is: <b>" . $gen_pass . " </b></p>
                              
                              <p>If you did not request a password reset, please ignore this email or 
                                  <a href="."mailto:S.Rodriguez3@uni.brighton.ac.uk".">contact support</a> if you have any questions.</p>
                                  </br>
                              <p style="."font-size:11px".">Do not share your password with others. At your earliest convenience please change your password to something you will remember. 
                                  For any issues or technical difficulties, please <a href="."mailto:S.Rodriguez3@uni.brighton.ac.uk".">contact support</a></p>
                            </body>
                            </html>");
                            
    
    // Update password in database
    $update = "UPDATE tbl_Login SET Password = '$password_hash' WHERE Username = '" . $new_email->get_username() . "'";
    $result = mysqli_query($db, $update);
    mysqli_free_result($result);
    
    // Update log table
    $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('" . $new_email->get_username() . "', 'Reset password', '$date')";
    $result = mysqli_query($db, $log);
    mysqli_free_result($result);
    
    if (mail($new_email->get_email(), $new_email->get_subject(), $new_email->get_message(), $new_email->get_header())) {
        alert("Email has been sent! Please check your junk mail.");
    } else {
        alert("Failed to send email. Please contact support.");
    }
}

//--------------------------------------
//------Request Admin permission--------
//--------------------------------------
if (isset($_POST['request_admin'])) {
    
    // Create new email
    $new_email = new email();
    $new_email->set_username(mysqli_real_escape_string($db, $_SESSION['username']));
    $new_email->set_email("samrod09@hotmail.com");
    $new_email->set_subject("Admin Request");
    $new_email->set_header("MIME-Version: 1.0" . "\r\n" . "Content-type:text/html;charset=UTF-8" . "\r\n");
    
    // Find details of user
    $sql = "SELECT Username, Firstname, Surname, EmailAddress FROM tbl_User WHERE Username = '" . $new_email->get_username() . "'";
    $query = mysqli_query($db, $sql);
    $address = mysqli_fetch_row($query);
    $fname = $address[1];
    $sname = $address[2];
    $email = $address[3];
    
    $new_email->set_message("<html>
                            <body>
                            <h1 style="."color:#002859;".">CRSL 5 a-side Football</h1>
                              <h2 style="."color:#002859;".">" . $new_email->get_subject() . "</h2>
                              <p> " . $fname . ' ' . $sname . " (" . $new_email->get_username() . ") has requested Admin permissions. 
                              If this is correct, please could you grant them in the database accordingly.</p>
                              <p>If this is incorrect, please contact them on: 
                                  <u><a href="."mailto: . $email . ".">" . $email . "</a></u></p>
                            </body>
                            </html>");
                            
    // Update log table
    //update_log("$username". " updated their password");
    $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('" . $new_email->get_username() . "', 'Requested admin permission', '$date')";
    $result = mysqli_query($db, $log);
    mysqli_free_result($result);
    
    if (mail($new_email->get_email(), $new_email->get_subject(), $new_email->get_message(), $new_email->get_header())) {
        alert("Email has been to the administrator. They will get back to you shortly");
    } else {
        alert("Failed to send email. Please contact support.");
    }
}

//--------------------------------------
//---------Edit User Profile------------
//--------------------------------------
if (isset($_POST['edit-submit'])) {
    
    $user = $_SESSION['username'];
    $nickname  = mysqli_real_escape_string($db, $_POST['nickname']);
    $position  = mysqli_real_escape_string($db, $_POST['pos']);
    $supports  = mysqli_real_escape_string($db, $_POST['support']);
    $favplayer = mysqli_real_escape_string($db, $_POST['favplayer']);
    $strength  = mysqli_real_escape_string($db, $_POST['strength']);
    $weakness  = mysqli_real_escape_string($db, $_POST['weakness']);
    $pic       = mysqli_real_escape_string($db, $_POST['img']);
    
    if (empty($pic)) {
        $pic = "$user" . ".jpg";
    }
    
    // Check whether user has any information
    $check = "SELECT * FROM tbl_UserExtraInfo WHERE Username = '$user'";
    $result = mysqli_query($db, $check);
    $exists = mysqli_fetch_assoc($result);
    
    // No extra info added yet 
    if (!$exists > 0) {
        // Insert to tbl_UserExtraInfo
        $query = "INSERT INTO tbl_UserExtraInfo (Username, Nickname, Position, Team, FavPlayer, Strength, Weakness, AddProfile, PicLocation)
                  VALUES('$user', '$nickname', '$position', '$supports', '$favplayer', '$strength', '$weakness', 0, '$pic')";
        mysqli_query($db, $query);
        
        // Update log table
        //update_log("$user" . " added information to their profile");
        $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user', 'Added information to profile', '$date')";
        $result = mysqli_query($db, $log);
        mysqli_free_result($result);
        
    } else {
        // Update user information
        $update = "UPDATE tbl_UserExtraInfo SET 
            Nickname  = '$nickname',
            Position  = '$position', 
            Team      = '$supports', 
            FavPlayer = '$favplayer',
            Strength  = '$strength',
            Weakness  = '$weakness',
            PicLocation = '$pic'
        WHERE username= '$user'";
       
        $result = mysqli_query($db, $update);
        mysqli_free_result($result);
        
        // Update log table
        //update_log($user . " edited information on their profile");
        $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user', 'Edited information on profile', '$date')";
        $result = mysqli_query($db, $log);
        mysqli_free_result($result);
    }
    header('location: profile.php');
}

//--------------------------------------
//--------Add profile to gallery--------
//--------------------------------------
if (isset($_POST['add_profile'])) {
    $user = $_SESSION['username'];
    
    // Set boolean field to true
    $update = "UPDATE tbl_UserExtraInfo SET AddProfile = 1 WHERE Username = '$user'";
    $result = mysqli_query($db, $update);
    mysqli_free_result($result);
    
    // Add profile to gallery table
    $insert = "INSERT INTO tbl_Gallery(Username, DateTimeAdded) VALUES('$user', '$date')";
    $result = mysqli_query($db, $insert);
    mysqli_free_result($result);
    
    // Update log table
    //update_log("$user" . " profile has been added to gallery");
    $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user', 'Added profile to gallery', '$date')";
    $result = mysqli_query($db, $log);
    mysqli_free_result($result);
        
    header('location: profile_gallery.php');
    
}

//--------------------------------------
//----------Create New Match------------
//--------------------------------------
if (isset($_POST['new_match'])) {
    $user_admin = $_SESSION['username'];
    $players = array();
    
    // Read date
    $match_date = mysqli_real_escape_string($db, $_POST['match_date']);
    $match_date = date("Y-m-d", strtotime($match_date));
    
    // Get today's date
    $today = date("Y-m-d");
    
    if ($match_date < $today) {
        array_push($errors, "* Match cannot be in the past");
    } else {
        // Add match to tbl_Macth
        $newMatch = "INSERT INTO tbl_Match (MatchDate) VALUES('$match_date')";
        $result = mysqli_query($db, $newMatch);
        mysqli_free_result($result);
        
        // Get ID of newly created match
        $get_match = "SELECT MatchID FROM tbl_Match ORDER BY MatchID DESC LIMIT 1";
        $results = mysqli_query($db, $get_match);
        $row = mysqli_fetch_row($results);
        $macthID = $row[0];
        
        // Get list of all players
        $sql = $db->query("SELECT Username FROM tbl_User ORDER by Username ASC");
        while ($users = mysqli_fetch_assoc($sql)) {
            
            // Identify username
            $user = $users['Username'];
            
            // Check to see if they have been selected
            $team = $_POST[$user];
            
            if (!empty($team)) {
                // Identify which team
                if (strpos($team, 'yellow') !== false) {
                    $insert_team = 1;
                } else {
                    $insert_team = 0;
                }
                
                // Insert to database
                $newUserGame = "INSERT INTO tbl_UserGame(Username, MatchID, Yellow) VALUES ('$user', '$macthID', '$insert_team')";
                $result2 = mysqli_query($db, $newUserGame);
                mysqli_free_result($result2);
            } 
        }
        
        // Update log table
        //update_log("$user_admin" . " profile has been added to gallery");
        $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user_admin', 'Created a new match', '$date')";
        $result = mysqli_query($db, $log);
        mysqli_free_result($result);
        
        header('location: matchDay.php');
    }
}

//--------------------------------------
//-------Add results from match---------
//--------------------------------------
if (isset($_POST['update_results'])) {
    $user_admin = $_SESSION['username'];
   
    // Get values from form
    $matchID = mysqli_real_escape_string($db, $_POST['matchID']);
    $matchDate = mysqli_real_escape_string($db, $_POST['matchDate']);
    $yellowGoals = $_COOKIE["yellowGoals"];
    $orangeGoals = $_COOKIE["orangeGoals"];
    
    // Add score to tbl_Match
    $sql = "UPDATE tbl_Match SET YellowGoals = $yellowGoals, OrangeGoals = $orangeGoals WHERE MatchID = $matchID";
    $result = mysqli_query($db, $sql);
    mysqli_free_result($result);
    
    // Get list of players
    $find = $db->query("SELECT u.Username, concat(u.FirstName, ' ', u.Surname) AS Name, MatchID, Yellow 
                        FROM tbl_UserGame ug 
                        INNER JOIN tbl_User u 
                        ON u.Username = ug.Username 
                        WHERE MatchID = $matchID 
                        ORDER BY Yellow DESC");
    
    while ($players = $find->fetch_assoc()) {
        $username  = $players['Username'];
        $name  = $players['Name'];
        $yellow = $players['Yellow'];  
        
        // Get number of goals scored by user
        $goals = $_COOKIE[$username];
       
        // Yellow team AND win 
        if (($yellow === "1") && ($yellowGoals > $orangeGoals)) {
            
            $updateYelWin = "UPDATE tbl_UserGame 
                          SET WinningTeam = '1', DropOut = '0', GoalsScored = '$goals', GoalsConceded = '$orangeGoals', TeamGoalsScored = '$yellowGoals' 
                          WHERE Username = '$username' AND MatchID = '$matchID' AND Yellow = 1";
            $result = mysqli_query($db, $updateYelWin);
            mysqli_free_result($result);
            
            //echo "Yellow team AND win!! " . $username . ": (" . $goals . ")<br>";
            
        } 
        // Yellow team AND lose
        elseif (($yellow === "1") && ($yellowGoals < $orangeGoals)) {
            
            $updateYelLose = "UPDATE tbl_UserGame 
                          SET WinningTeam = '0', DropOut = '0', GoalsScored = '$goals', GoalsConceded = '$orangeGoals', TeamGoalsScored = '$yellowGoals' 
                          WHERE Username = '$username' AND MatchID = '$matchID' AND Yellow = 1";
            $result = mysqli_query($db, $updateYelLose);
            mysqli_free_result($result);
            
            //echo " Yellow team AND lose!! " . $username . ": (" . $goals . ")<br>";
        } 
        // Orange team AND win 
        elseif (($yellow === "0") && ($yellowGoals < $orangeGoals)) {
            
            $updateOrangeWin = "UPDATE tbl_UserGame 
                                SET WinningTeam = '1', DropOut = '0', GoalsScored = '$goals', GoalsConceded = '$yellowGoals', TeamGoalsScored = '$orangeGoals' 
                                WHERE Username = '$username' AND MatchID = '$matchID' AND Yellow = 0";
            $result = mysqli_query($db, $updateOrangeWin);
            mysqli_free_result($result);
            
            //echo " Orange team AND win!!  " . $username . ": (" . $goals . ")<br>";
        } 
        // Orange team AND lose
        elseif (($yellow === "0") && ($yellowGoals > $orangeGoals)) {
            
            $updateOrangeLose = "UPDATE tbl_UserGame 
                                 SET WinningTeam = '0', DropOut = '0', GoalsScored = '$goals', GoalsConceded = '$yellowGoals', TeamGoalsScored = '$orangeGoals' 
                                 WHERE Username = '$username' AND MatchID = '$matchID' AND Yellow = 0";
            $result = mysqli_query($db, $updateOrangeLose);
            mysqli_free_result($result);
            
            //echo " Orange team AND lose!! " . $username . ": (" . $goals . ")<br>";
        } 
        // Yellow team AND DRAW
        elseif (($yellow === "1") && ($yellowGoals = $orangeGoals)) {
            
            $updateYelDraw = "UPDATE tbl_UserGame 
                              SET WinningTeam = '0', DropOut = '0', GoalsScored = '$goals', GoalsConceded = '$yellowGoals', TeamGoalsScored = '$orangeGoals' 
                              WHERE Username = '$username' AND MatchID = '$matchID' AND Yellow = 1";
            $result = mysqli_query($db, $updateYelDraw);
            mysqli_free_result($result);
            
            //echo " Yellow and DRAW!! " . $username . ": (" . $goals . ")<br>";
        }
        // Orange team AND DRAW
        elseif (($yellow === "0") && ($yellowGoals = $orangeGoals)) {
            
            $updateOraDraw = "UPDATE tbl_UserGame 
                              SET WinningTeam = '0', DropOut = '0', GoalsScored = '$goals', GoalsConceded = '$yellowGoals', TeamGoalsScored = '$orangeGoals' 
                              WHERE Username = '$username' AND MatchID = '$matchID' AND Yellow = 0";
            $result = mysqli_query($db, $updateOraDraw);
            mysqli_free_result($result);
            
            //echo " Orange and DRAW!! " . $username . ": (" . $goals . ")<br>";
        }
        
    }
    
    // Update log table
    //update_log("$user_admin" . " profile has been added to gallery");
    $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user_admin', 'Updated results of latest match', '$date')";
    $result = mysqli_query($db, $log);
    mysqli_free_result($result);
    
    header('location: matchDay.php');
    
}


// Update log table
function update_log($msg) {
    $user = $_SESSION['username'];
    
    $log = "INSERT INTO tbl_Log (Username, Message, DateTime) VALUES('$user', '$msg', '$date')";
    $result = mysqli_query($db, $log);
    mysqli_free_result($result);
}

// Create random password
function generate_password($length) {
  $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789-=~!@#$%&*';
  $str = '';
  $max = strlen($chars) - 1;

  for ($i = 0; $i < $length; $i++)
    $str .= $chars[random_int(0, $max)];

  return $str;
}

// Pop up message box
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

?>
