<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
}

// initializing variables
$username = ""; // reserved space for username from db

$host = "localhost"; // hostname
$dbname = "happybrides"; // database naem
$dbUN = "root"; // database username
$dbPS = "root"; // database password zet naar root
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

///==================================================================================
// save sequence of gifts to db
///==================================================================================
$jsondata = json_decode( file_get_contents('php://input') );
if(isset($jsondata)) {
  for($i = 0; $i < count($jsondata->SAFESEQUENCE); $i += 2) {
    //plus 1 is id van gift, normal is positie
    echo $jsondata->SAFESEQUENCE[$i+1];
    try {
      $sql = "UPDATE gift SET SEQUENCE = :sequence  WHERE GIFTID = :id";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':id', $jsondata->SAFESEQUENCE[$i+1]);
      $stmt->bindValue(':sequence', $jsondata->SAFESEQUENCE[$i]);
      $stmt->execute();

    }catch(Exception $e) {
      array_push($errors, $e->getMessage()); // output if username or pass
    }
  }

  if($sql != null) // if sql string isnt null
      $sql = null; // set sql string to null
    
    try {
      $sql = "SELECT * FROM gift WHERE GIFTID = :username";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':username', $_SESSION['username']);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode, gets all associated data
      $rows = $stmt->fetchAll(); // gets all data according to fetchmode set above ^

      foreach($rows as $row) {

      }

    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }
}

///==================================================================================
/// check if person is logged in with valid credentials
///==================================================================================
function CheckLogin() {
  if(isset($_SESSION['username'])) {
    global $connectionstring, $dbUN, $dbPS, $sql;

    $db2 = new PDO($connectionstring, $dbUN, $dbPS); // connect to the database
    $db2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // exception mode for db

    if($sql != null) // if sql string isnt null
      $sql = null; // set sql string to null

    try {
      $un = $_SESSION['username'];

      $sql = "SELECT * FROM client WHERE USERNAME=:username LIMIT 1"; // sql to get table where username is the same
      $stmt = $db2->prepare($sql); // prepare sql string for special characters and such
      $stmt->bindValue(':username', $un);
      $stmt->execute(); // execute sql string to database
      $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode, gets all associated data
      $rows = $stmt->fetchAll(); // gets all data according to fetchmode set above ^
      
      if ($rows) { // if user exists 
        
          if ($rows[0]['USERNAME'] != $un) { // check if username lines up
            $_POST = array();
            $_SESSION = array();
            header('location: ../index.php'); // redirect to index.php (login page)
            session_destroy();
            exit();
          }else {
            $_SESSION['sharecode'] = $un;
          }
      }

    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }
  }else {
    $_POST = array();
    $_SESSION[] = array();
    header('location: ../index.php'); // redirect to index.php (login page)
    session_destroy();
    exit();
  }
}


// REGISTER USER
if (isset($_POST['reg_user'])) { // if submit button = reg_user
  $errors = array(); // empty array

  if($sql != null) // if sql is not null make it so, just in case
    $sql = null; // set sql to null


  // receive all input values from the form
  $username = $_POST['username']; // get username from form field
  $password = $_POST['password']; // get password from form field
  $r_password = $_POST['r_password']; // get repeated password from form field

  if (empty($username)) { array_push($errors, "Username is required"); } // check if field isnt empty and output username reqruired if so
  if (empty($password)) { array_push($errors, "Password is required"); } // check if field isnt empty and output password reqruired if so
  
  if ($password != $r_password) { // if password doesnt match repeat password
	  array_push($errors, "The two passwords do not match"); // push error 
  }

  $sql = "SELECT * FROM client WHERE USERNAME=:username LIMIT 1"; // sql to get table where username is the same
  $stmt = $db->prepare($sql); // prepare sql string for special characters and such
  $stmt->bindValue(':username', $username);
  $stmt->execute(); // execute sql string to database
  $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode, gets all associated data
  $rows = $stmt->fetchAll(); // gets all data according to fetchmode set above ^
  
  if ($rows) { // if user exists 
    foreach($rows as $row) { // for each in rows [should be only one]
      if ($row['USERNAME'] == $username) { // check if username lines up
        array_push($errors, "Username already exists"); // throw error if it does
      }
    }
  }

  // register user if there are no errors thrown or pushed
  if (count($errors) == 0) {
  	$s_password = md5($password);//encrypt the password before saving in the database

  	$query = "INSERT INTO client (USERNAME, PASSWORD)  
          VALUES(:username, :password)"; // create new sql query string to set variables
          
    $stmt_2 = $db->prepare($query); // perpare query/sql string
    $stmt_2->bindValue(':username', $username);
    $stmt_2->bindValue(':password', $s_password);
    $stmt_2->execute(); // execute said string ^
  	$_SESSION['username'] = $username; // give username to session
  	$_SESSION['success'] = "You are now logged in"; // give succes
    header('location: index.php'); // redirect to index.php (login page)
    exit();
  }
}

// LOGIN USER
if (isset($_POST['login_user'])) { // if submit button equals login_user
  $errors = array(); // set errors empty

  if($sql != null) // if sql string isnt null
  $sql = null; // set sql string to null

  // receive all input values from the form
  $username = $_POST['username']; // get username from form fields
  $password = $_POST['password']; // get password from form fields

  if (empty($username)) { array_push($errors, "Username is required"); } // check if form fields werent empty and output if they did
  if (empty($password)) { array_push($errors, "Password is required"); } // cehck if form fields werent empty and output if they did

  try {
  $sql = "SELECT * FROM client WHERE USERNAME=:username LIMIT 1"; // sql to get table where username is the same
  $stmt = $db->prepare($sql); // prepare sql string for special characters and such
  $stmt->bindValue(':username', $username);
  $stmt->execute(); // execute said string to database

  }catch(Exception $e) { // catch if error
    array_push($errors, $e->getMessage()); // output if username or pass
  }

  if (count($errors) == 0) { // if there's no errors
    $password = md5($password); // hash pasword
    $query = "SELECT * FROM client WHERE USERNAME=:username AND PASSWORD=:password"; // make new query request
    
    $stmt = $db->prepare($query); // perpare query
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':password', $password);
    $stmt->execute(); // execute query

    $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode
    $rows = $stmt->fetchAll(); // get data according to fetch

    foreach($rows as $row) { // for each row in rows [should be one]
      if($row["USERNAME"] == $username && $row["PASSWORD"] == $password) { // if username and password line up
        $_SESSION['username'] = $username; // give username to session
        $_SESSION['sharecode'] = $username;
        $_SESSION['success'] = "You are now logged in"; // set session success
        header('location: main.php'); // redirect to main.php
        $_POST = array(); // empty post
        exit();
      }else {
        array_push($errors, "Wrong username/password combination"); // output wrong password/username combination
        $_POST = array(); // empty post
      }
    }
  }
}

//redirect login guest to guest login page
if(isset($_POST['guest_login'])) {
  header('location: guestregister.php'); // redirect to main.php
}

// new gift 
if(isset($_POST['NAME'])) {
  if(!empty($_POST['NAME'])) {
    if(!empty($_SESSION['username'])) {
      $errors = array(); // set errors empty

      if($sql != null) // if sql string isnt null
        $sql = null; // set sql string to null

      $giftname = $_POST['NAME']; // get giftname from post

      try {
        $query = "SELECT MAX(GIFTID) FROM gift"; // query that gets all gifts
        $stmt = $db->prepare($query); // prepare gift query

        $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode to associate stuff
        $stmt->execute();
        $rows = $stmt->fetchAll(); // get all data that associates

        $id = 1;

        if(sizeof($rows) != 0) 
          $id = $rows[0]["MAX(GIFTID)"] + 1; // size is sizeof rows(amount of gifts)

        $seshUN = $_SESSION['username'];

        $sql = "INSERT INTO gift (NAME, OWNER, GIFTID)  VALUES(:giftname, :seshUN, :id)"; // create new sql query string to set variables
        
        $stmt_2 = $db->prepare($sql); // perpare query/sql string
        $stmt_2->bindValue(':giftname', $giftname);
        $stmt_2->bindValue(':seshUN', $seshUN);
        $stmt_2->bindValue(':id', $id);
        $stmt_2->execute(); // execute said string ^

        echo $id;
      
      }catch(Exception $e) { // catch if error
        array_push($errors, $e->getMessage()); // output if username or pass
      }
    }else {
      alert("you're not logged in");
    }
  }else {
    alert("please give the gift a name");
  }
}

// verwijder gift 
if(isset($_POST['id'])) {
  if(!empty($_SESSION['username'])) {
    $errors = array(); // set errors empty

    if($sql != null) // if sql string isnt null
      $sql = null; // set sql string to null

    $giftID = $_POST['id']; // get giftname from post
    $seshUN = $_SESSION['username'];

    try {
      $sql = "DELETE FROM gift WHERE GIFTID = '$giftID' AND OWNER = '$seshUN'"; // query that gets all gifts
      $stmt = $db->prepare($sql); // prepare gift query
      $stmt->bindValue(':giftID', $giftID);
      $stmt->bindValue(':seshUN', $seshUN);
      $stmt->execute();   

    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }
  }else {
    alert("you're not logged in");
  }
}

// load gifts from ajax request from db
if(isset($_POST['load'])) {
  if(!empty($_SESSION['username'])) {
    $errors = array();

    if($sql != null) // if sql string isnt null
      $sql = null; // set sql string to null
    
    if(empty($_SESSION['sharecode']))
      $seshUN = $_SESSION['username'];
    else 
      $seshUN = $_SESSION['sharecode'];

    try {
      $sql = "SELECT * FROM gift WHERE OWNER = '$seshUN'";
      $stmt = $db->prepare($sql);
      $stmt->bindValue(':seshUN', $seshUN);
      $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode to associate stuff
      $stmt->execute(); 

      $rows = $stmt->fetchAll(); // get data according to fetch

      usort($rows, function($a, $b) { // sort data
        return $a['SEQUENCE'] <=> $b['SEQUENCE'];
      });

      $response = json_encode($rows);

      //$testresponse = json_decode
      echo $response;

    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }    
  }
}

if(isset($_GET['logout'])) {
  if($_GET['logout'] == true) {
    session_unset();
    session_destroy();

    header('location: ../index.php'); // redirect to index.php (login page)
  }
}

//close te database
if($db != null) 
$db = null;
?>
