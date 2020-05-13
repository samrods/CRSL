<!--------------------------------------------------->
<!--PHP to load the different QR codes on home page-->
<!--------------------------------------------------->

<!DOCTYPE html>
<html>
<head>
</head>

<body>
    <!--Change QR code based on selection-->
    <?php
        // Get value of drop down box
        $q = $_GET['q'];
      
        // Load image
        switch ($q) {
            case "apps": ?>
                <img src="images\\QR\\Appearances.jpg" style="width: 6em; height: 6em; float: right;" alt="No picture available">
            <?php
                break;
            case "goalsSc": ?>
                <img src="images\\QR\\GoalsScored.jpg" style="width: 6em; height: 6em; float: right;" alt="No picture available">
            <?php
                break;
            case "tgoalsSc": ?>
                <img src="images\\QR\\TeamGoals.jpg" style="width: 6em; height: 6em; float: right;" alt="No picture available">
            <?php    
                break;
            case "tGoalsCo": ?>
                <img src="images\\QR\\GoalsConceded.jpg" style="width: 6em; height: 6em; float: right;" alt="No picture available">
            <?php    
                break;
            case "wins": ?>
                <img src="images\\QR\\TotalWins.jpg" style="width: 6em; height: 6em; float: right;" alt="No picture available">
            <?php    
                break;
            default:
                echo "No picture available"; 
        }
    ?>
</body>
</html>