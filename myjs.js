/* JavaScript file */

/*-------Create new match - Select player---------*/ 
function selectTeams(checkbox, noOfPlayers) {
            
    // click on player
    if(checkbox.checked === true) {
        // [1,2,3,...,noOfPlayers]
        array = Array.from({ length: noOfPlayers }, (v, k) => k * 1);
        
        // initialise shuffle function
        var shuffled = array.sort(
            function() { return .5 - Math.random() }
        );
        
        // Create 10 random numbers (players)
        for(i = 0; i <= 10; i++){
        	if (i <= 5) {
        	    // assign first numbers 0-4 
        	    var selected = shuffled.slice(0, i);
            } else {
                // assign numbers 5-9
                var selected2 = shuffled.slice(5, i);
            }
        }
        
        // Select these players (yellow team)
        for (j = 0; j < selected.length; j++) {
            // check box 
            document.getElementById("yellow" + selected[j]).checked = true;
            var username = document.getElementById("yellow" + selected[j]).value;
            var teamCol = "yellow";
            
            // identify username and team
            getPlayers(username, teamCol);
        }
        
        // Select these players (orange team)
        for (j = 0; j < selected2.length; j++) {
            // check box
            document.getElementById("orange" + selected2[j]).checked = true;
            var username = document.getElementById("orange" + selected2[j]).value;
            var teamCol = "orange";
            
            // identify username and team
            getPlayers(username, teamCol);
        }
        
    } else {
        // Click auto-generate box again to remove all selections
        console.log("Clear All");
        
        for (j = 0; j < noOfPlayers; j++) {
            document.getElementById("yellow"+j).checked = false;
        }
        for (j = 1; j < noOfPlayers; j++) {
            document.getElementById("orange"+j).checked = false;
        }
    }
}

// Write to console player and team
function getPlayers(player, team) {
    console.log(player + ": " + team);
}
    

// Behaviour for sorting the player's table when Creating a New Match 
// No need to reload whole page when choosing sorting players 
function showUser(str) {
    if (str === "") {
        // Error loading players...
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        // XMLHttpRequest object used to exchange data with server behind the scenes
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            // valid status code 
            if (this.readyState == 4 && this.status == 200) {
                // set text to be the value returned from server
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","displayPlayers.php?q="+str,true);
        xmlhttp.send();     // send data to web page
    }
}

//----------------------------------------------------------------------------------

// Behaviour to show different QR codes (same principle as loading players)
function showQR(str) {
    if (str === "") {
        // Error loading QR code...
        document.getElementById("imgQR").innerHTML = "No QR code to display";
        return;
    } else {
        // No need to reload whole page when choosing QR code 
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for old IE browsers
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            // valid status code 
            if (this.readyState == 4 && this.status == 200) {
                // set text to be the value returned from server
                document.getElementById("imgQR").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","displayQR.php?q="+str,true);
        xmlhttp.send();         // send data to web page
    }
}

//----------------------------------------------------------------------------------

// Behaviour for 'Live Preview' functionality - based on radio button selection [not fully functional]

// Hashmap to store (player, team) pairs
var hashMap = new Map();
// Initialise arrays to store teams
var yellow = new Array();
var orange = new Array();

// get value of the chosen player
function getVals(e) {
    var team = e.id;
    var player = e.name;
    var user = e.value;
    
    // Add key to value
    hashMap.set(user, team);
    var team_colour = hashMap.get(user);
    
    // Add players to the yellow team array
    if (team_colour.includes('yellow')) {
        yellow.push(user);
        addRow(player, team_colour);
    } //add players to orange team array
    else {
        orange.push(user);
        addRow(player, team_colour);
    }
    
    // Yellow team is unique! 
    checkIfArrayIsUnique(yellow);
    if (true) {
        // Split the string to find each username
        var uniqueYel = removeDuplicates(yellow).toString();
        yelArr = uniqueYel.split(',');
        
        // Display list of unique names
        console.log(yelArr);
        
        // Yellow team all good. Now check orange team. 
        checkIfArrayIsUnique(orange);
        if (true) {
            // Split the string to find each username
            var uniqueOra = removeDuplicates(orange).toString();
            oraArr = uniqueOra.split(',');
            console.log(oraArr);
        }
    } else {
        console.log("Error with duplicate names!")
    }
}

function checkIfArrayIsUnique(arr) {
    // initialise variables
    var map = {}, i, size;
    
    // Loop through elements in the array
    for (i = 0, size = arr.length; i < size; i++){
        // Duplicate players in same team 
        if (map[arr[i]]){
            console.log("Removed duplicate player");
            removeDuplicates(arr);
        }
        map[arr[i]] = true;
    }
    return true;
}

// remove any duplicates (in case user clicks player twice)
function removeDuplicates(arr){
    val={};
    arr.forEach(function(e){
        val[e]=true;
    });
    
    return Object.keys(val);
}

/*------------------------------------------------------------*/

// Load and display players when NewMatch.php loads

// Array to store headers
var arrHead = new Array();
arrHead = ['', 'Yellow Team:', 'Orange Team:'];

var teamtble = new Array();
teamtble = ['Yellow:', 'Orange:'];

function createTable() {
    // initialise table
    var tblPlayer = document.createElement('table');
    tblPlayer.setAttribute('id', 'tblPlayer');     
    tblPlayer.setAttribute('width', '100%');      

    var tr = tblPlayer.insertRow(-1);

    for (var h = 0; h < arrHead.length; h++) {
        // Table header
        var th = document.createElement('th');
        
        // Style headers
        if (h==1) {
            th.style.color = "yellow";
        } else {
            th.style.color = "orange";
        }
        
        /*append text to header*/
        th.innerHTML = arrHead[h];
        tr.appendChild(th);
    }
    
    // Add table to web page
    var div = document.getElementById('cont');
    div.appendChild(tblPlayer);    
    
    // Load players
    showUser("SELECT * FROM view_playerIndexes ORDER BY PerformanceIndex DESC");
}

// Add rows to table
function addRow(player, team) {
    // initialise variables
    var playerTab = document.getElementById('tblPlayer');
    var rowCnt = playerTab.rows.length;
    
    // Table row
    var tr = playerTab.insertRow(rowCnt);
    tr = playerTab.insertRow(rowCnt);
    
    // loop through cells in row
    for (var c = 0; c < arrHead.length; c++) {
        var td = document.createElement('td');
        td = tr.insertCell(c);
        
        // First column
        if (c == 0) {
            // Add button with attributes and event handler
            var button = document.createElement('input');
            button.setAttribute('type', 'button');
            button.setAttribute('value', 'Remove');
            button.setAttribute('onclick', 'removeRow(this)');

            // append button to row
            td.appendChild(button);
            
        } else if ((c == 1) && (team.includes("yellow"))){
            // Create and add text box to each cell
            var ele = document.createElement('input');
            ele.setAttribute('type', 'text');
            ele.setAttribute('value', player);
            ele.setAttribute('disabled', true);
            
            // append cell to row
            td.appendChild(ele);
            
        } else if ((c == 2) && (team.includes("orange"))) {
            // Create and add text box to each cell
            var ele = document.createElement('input');
            ele.setAttribute('type', 'text');
            ele.setAttribute('value', player);
            ele.setAttribute('disabled', true);
            
            // append cell to row
            td.appendChild(ele);
        }
    }
}

// Delete table row
function removeRow(oButton) {
    var playerTab = document.getElementById('tblPlayer');
    // Btn -> td -> tr
    playerTab.deleteRow(oButton.parentNode.parentNode.rowIndex);
}

// Auto-populate email address in registration page based on username
function emailhelp(e) {
    document.getElementById('val').value = e.value + "@clarksons.com";
}

