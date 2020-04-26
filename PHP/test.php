<?php 
  include('server2.php');
  session_start(); 
  
?>

<html>
<head>

</head>
<body>
    
    <h1>Display Radio Buttons</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <p>Select which team:</p>
      <div style="display:inline-block">
        <label for="other">Player 1:</label>
      </div>
      <div style="display:inline-block">
      	<input type="radio" id="TPL" name="TPL" value="yel">
        <label for="yellow0">Yellow</label><br>
      </div>
      <div style="display:inline-block">
        <input type="radio" id="TPL" name="TPL" value="ora">
        <label for="orange0">Orange</label><br>
      </div>
      
      <br>
      
      <div style="display:inline-block">
        <label for="other">Player 2:</label>
      </div>
      <div style="display:inline-block">
      	<input type="radio" id="SRZ" name="SRZ" value="yel">
        <label for="yellow1">Yellow</label><br>
      </div>
      <div style="display:inline-block">
        <input type="radio" id="SRZ" name="SRZ" value="ora">
        <label for="orange1">Orange</label><br>
      </div>
      
       <br>
    
      <button type="submit" name="new_match_2">Submit</button>
      <!--<input type="submit" name="new_match" value="Submit">-->
    </form>


</body>
</html>