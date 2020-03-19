<?php
session_start();

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

///==================================================================================
/// check if person is logged in with valid credentials
///==================================================================================
function CheckLogin() {
  if(isset($_SESSION['username'])) {

    if($sql != null) // if sql string isnt null
      $sql = null; // set sql string to null

    try {
      $un = $_SESSION['username'];

      $sql = "SELECT * FROM client WHERE USERNAME='$un'";
      $stmt = $db->prepare($sql); // prepare gift query

      $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode to associate stuff
      $stmt->execute();   

      if($rows[0]['USERNAME'] != $_SESSION['username']) {
        $_POST = array();
        $_SESSION = array();
        header('location: ../index.php'); // redirect to index.php (login page)
        session_destroy();
        exit();
      }else {
        echo "right username";
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

  $sql = "SELECT * FROM client WHERE USERNAME='$username' LIMIT 1"; // sql to get table where username is the same
  $stmt = $db->prepare($sql); // prepare sql string for special characters and such
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
          VALUES('$username', '$s_password')"; // create new sql query string to set variables
          
    $stmt_2 = $db->prepare($query); // perpare query/sql string
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
    $sql = "SELECT * FROM client WHERE USERNAME='$username' LIMIT 1"; // sql to get table where username is the same
    $stmt = $db->prepare($sql); // prepare sql string for special characters and such
    $stmt->execute(); // execute said string to database
  
    $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode to associate stuff
    $rows = $stmt->fetchAll(); // get all data that associates
    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }

    if (count($errors) == 0) { // if there's no errors
      $password = md5($password); // hash pasword
      $query = "SELECT * FROM client WHERE USERNAME='$username' AND PASSWORD='$password'"; // make new query request
      
      $stmt = $db->prepare($query); // perpare query
      $stmt->execute(); // execute query

      $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode
      $rows = $stmt->fetchAll(); // get data according to fetch

      foreach($rows as $row) { // for each row in rows [should be one]
        if($row["USERNAME"] == $username && $row["PASSWORD"] == $password) { // if username and password line up
          $_SESSION['username'] = $username; // give username to session
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


// new gift 
if(isset($_POST['NAME'])) {
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

      $sql = "INSERT INTO gift (NAME, OWNER, GIFTID)  VALUES('$giftname', '$seshUN', $id)"; // create new sql query string to set variables
      
      $stmt_2 = $db->prepare($sql); // perpare query/sql string
      $stmt_2->execute(); // execute said string ^

      echo $id;
    
    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }
  }else {
    alert("you're not logged in");
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

      $stmt->setFetchMode(PDO::FETCH_ASSOC); // set fetch mode to associate stuff
      $stmt->execute();   

    }catch(Exception $e) { // catch if error
      array_push($errors, $e->getMessage()); // output if username or pass
    }
  }else {
    alert("you're not logged in");
  }
}

if(isset($_POST['load'])) {
  if(!empty($_SESSION['username'])) {
    $errors = array();

    if($sql != null) // if sql string isnt null
      $sql = null; // set sql string to null

    // do stuff getting 
  }
}

//close te database
if($db != null) 
$db = null;
?>
