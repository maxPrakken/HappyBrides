<?php include('serverGuest.php')?>
<?php if (session_status() == PHP_SESSION_NONE) {
  session_start(); 
} ?>

<!DOCTYPE html>
<html>
<head>
<title>Insert Your Name</title>

<!--Bootsrap 4 CDN-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<!--Fontawesome CDN-->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

<!--Custom styles-->
<link rel="stylesheet" type="text/css" href="login.css">

<!-- Latest compiled and minified CSS -->

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>  
<!-- <script src="https://getbootstrap.com/dist/js/bootstrap.min.js"></script> -->
</head>
<body>
<div class="container">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Guest login</h3>
				<div class="card-body">
					<form action="guestregister.php" method="post">
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" class="form-control" placeholder="Username" name="usernameguest">
							
						</div>
						<div class="input-group form-group">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="text" class="form-control" placeholder="Share code" name="sharecode">
						</div>
						
						<div class="form-group"> 
							<input type="submit" value="enter" name="reg_guest" class="btn float-right register_btn">
						</div>
					</form>
				</div>
		</div>
	</div>
</div>

<script src="globalAjax.js" type="text/javascript"></script>

<?php
	
?>	

</body>
</html>