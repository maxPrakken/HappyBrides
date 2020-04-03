<?php include('server.php')?>
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">

    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>  

    <!--Custom styles-->
	  <link rel="stylesheet" type="text/css" href="main.css">
    <title>Happy Brides</title>
    <style>
        td:hover{
        cursor:move;
        }
        
        .jumbotron{
          background-image: url("https://previews.123rf.com/images/macrovector/macrovector1410/macrovector141000284/32133439-vintage-nostalgic-beautiful-roses-bunches-composition-romantic-floral-wedding-gift-wrapping-paper-se.jpg");
        }
    </style>
</head>

<body>
    <div class="jumbotron text-center">
        <h1>Gift list </h1> 
        <p style="color:black;">ID: <?= $_SESSION['username']?></p> 
        <a id="uitlog" href="?logout=true"> UITLOGGEN </a>
    </div>

      <main role="main" class="container">
          <th>
            <input id = "name" style="width: 250px " placeholder="Cadeau. . ." value=""><button name="addgiftbtn" class="btnadd">Toevoegen</button><br>
            <textarea id = "beschrijvingID" style="width: 350px; height: 100px; resize: none;" placeholder="Beschrijving..." value=""></textarea>
          </th><br>
            <div id="dialog" style="display: none; width= 700px; height= 300px;">
              <div id="dialogtext"></div>
          </div>

          <table class="table table-striped table-hover">
               <thead class="thead-dark">
                   <tr>
                       <th>Cadeau</th>
                       <th>Bought by whom</th>
                       <th>Verwijder Cadeau</th>
                       <th>ID</th>
                   </tr>
               </thead>
               <form action="register.php" method="post">
                <tbody id="tabel" name="gifts">
                </tbody>
               </form>
           </table>
       
           </main><!-- /.container -->
       
           <script src="globalAjax.js" type="text/javascript"></script>
       
</body>

<?php
CheckLogin(); 
$gettables = true;
?>
</html>