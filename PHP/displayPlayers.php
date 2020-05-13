<!----------------------------------->
<!--PHP to load the list of players-->
<!----------------------------------->

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <?php
        // Connect to database
        include('server.php');
        
        // Get value of drop down box (sort by player name or performance index)
        $q = $_GET['q'];
        $sql = " $q ";
        $result = mysqli_query($db, $sql);
        
        // Display headings
        echo '<div class="profile_row">
                <div class="match_col_1" style="font-weight: bold;">Player:</div>
                <div class="match_col_2" style="font-weight: bold;">Performance Index:</div>
                <div class="match_col_3" style="font-weight: bold; color: yellow;">Yellow Team:</div>
                <div class="match_col_3" style="font-weight: bold; color: orange;">Orange Team:</div>
            </div>';
        
        $localCount = 0;
        
        // Read results & Loop through each player           
        while($row = mysqli_fetch_assoc($result)) {
            $username  = $row['Username'];
            $name = $row['Player'];
            $p_index   = $row['PerformanceIndex'];
            
            // Output to screen
            echo '<div class="profile_row2">
                    <ul class="player-row">
                        <li style="margin-right: 2em;">
                            <div>' . $name . '</div>
                        </li>
                        <li style="margin-right: 4.2em; text-align: center;">
                            <div class="match_col_2">' . $p_index .'</div>
                        </li>
                        <li>
                            <input type="radio" id="yellow' . $localCount . '" class="yellow" name="' . $username .'" value="yellow" onchange="getVals(this)"/>
                            <label for="yellow">' . $username .'</label>
                        </li>
                        <li>
                            <input type="radio" id="orange' . $localCount . '" class="orange" name="' . $username .'" value="orange" onclick="getVals(this)"/>
                            <label for="orange">' . $username .'</label>
                        </li>
                    </ul>
                  </div>';
            // Increment to next player
            $localCount++;
        }
        mysqli_close($db);
    ?>
</body>
</html>