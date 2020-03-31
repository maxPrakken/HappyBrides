<?php include('server.php')?>
<?php if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="main.css"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 

    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>  

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
        <p style="color:white;">ID: test123</p> 
      </div>

      <main role="main" class="container">
          <table class="table table-striped table-hover">
               <thead class="thead-dark">
                   <tr>
                       <th>Cadeau</th>
                       <th>Bought by whom</th>
                       <th>Buy</th>
                   </tr>
                   
                   
               </thead> 
               <tbody id="tabel">
                  <tr><td>Cadeau1<th>. . .</th> <td><button class= 'blt'>Buy this</button></td></tr>
                  <tr><td>Cadeau2<th>. . .</th> <td><button class= 'blt'>Buy this</button></td></tr>
                  <tr><td>Cadeau3<th>. . .</th> <td><button class= 'blt'>Buy this</button></td></tr>

               </tbody>
           </table>
       
           </main><!-- /.container -->

           <script src="globalAjax.js" type="text/javascript"></script>
       
         </body>
</html>