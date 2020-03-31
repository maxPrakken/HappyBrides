<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
  }
  
    // initializing variables
    $username = ""; // reserved space for username from db
    
    $host = "localhost"; // hostname
    $dbname = "happybrides"; // database naem
    $dbUN = "root"; // database username
    $dbPS = "root"; // database password
    $connectionstring = "mysql:host=$host;dbname=$dbname"; // connectionstring, for ease of use
    
    $giftnameRem;
    
    $db = null; // set db to null
    $sql = null; // set sql string to null
    $errors = array();  // make error array
    
    try {
        
    
        $db = new PDO($connectionstring, $dbUN, $dbPS); // connect to the database
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // exception mode for db
    }catch (PDOException $ex) { // catch errors
        echo "PDOException: $ex"; // echo error(s)
    }

    // set new buyer
    if(isset($_POST['id'])) {
        if(!empty($_SESSION['username'])) {
            $errors = array(); // set errors empty
        
            if($sql != null) // if sql string isnt null
              $sql = null; // set sql string to null
        
            $giftID = $_POST['id']; // get giftname from post
            $seshUN = $_SESSION['username'];

            try {
                $sql = "SELECT * FROM gift WHERE GIFTID = '$giftID'";
                $stmt = $db->prepare($sql);
                $stmt->execute(); // execute query

                $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode
                $rows = $stmt->fetchAll(); // get data according to fetch
                $rows[0]['BOUGHTBY'] = $_SESSION['username'];
                $response = json_encode($rows);

                $name = $rows[0]['NAME'];
                $owner = $rows[0]['OWNER'];

                $sharecode = $_SESSION['sharecode'];
                $guestname = $_SESSION['username'];

                $sql2 = "DELETE FROM gift WHERE GIFTID = '$giftID' AND OWNER = '$sharecode'"; // query that gets all gifts
                $stmt2 = $db->prepare($sql2); // prepare gift query
                $stmt2->execute();   

                $sql3 = "INSERT INTO gift (NAME, OWNER, BOUGHTBY, GIFTID)  VALUES('$name', '$owner', '$guestname', '$giftID')"; // create new sql query string to set variables
                $stmt3 = $db->prepare($sql3); // prepare gift query
                $stmt3->execute();  

                echo $response;

            }catch(Exception $e) {
                array_push($errors, $e->getMessage()); // output if username or pass
            }
        }
    }

    if(isset($_POST['reg_guest'])) {
        if(!empty($_POST['usernameguest'])) {
        if(!empty($_POST['sharecode'])) {
            $code  = $_POST['sharecode'];

            $errors = array(); // set errors empty

            if($sql != null) // if sql string isnt null
            $sql = null; // set sql string to null

            try {
            $sql = "SELECT * FROM client WHERE USERNAME = '$code'";
            $stmt = $db->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode to associate stuff
            $stmt->execute();
            $rows = $stmt->fetchAll(); // get all data that associates

            if($rows != null) {
                $_SESSION['username'] = $_POST['usernameguest'];
                $_SESSION['sharecode'] = $_POST['sharecode'];
                $_SESSION['success'] = "You are now logged in"; // set session success
                header('location: mainGuest.php'); // redirect to mainguest.php
                $_POST = array(); // empty post
                exit();
            }
            }catch(Exception $e) {// catch if error
            array_push($errors, $e->getMessage()); // output if username or pass
            }
        }else 
        alert('please put in a sharecode');
        }else 
        alert('please put in a username');
    }

//close te database
if($db != null) 
$db = null;
?>