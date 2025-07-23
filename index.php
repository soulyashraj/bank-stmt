<?php
	include 'include/admin-functions.php';
	$admin = new AdminFunctions();
	if($admin->sessionExists()){
		header("location: dashboard.php");
		exit();
	}

	$csrf = new csrf();
	$token_id = $csrf->get_token_id();
	$token_value = $csrf->get_token($token_id);
	
	if(isset($_POST['signin'])){
		if($csrf->check_valid('post')) {
      
      $admin->adminLogin($_POST, "dashboard.php");
      
		}
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor - Ajay </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo BASE_URL;?>/dist/css/adminlte.min.css">
  <script src="<?php echo BASE_URL;?>/plugins/notiflix/notiflix-aio-1.5.0.min.js"></script>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
 
  <div class="card card-outline card-primary">
  <?php if(isset($_GET['failed'])){ ?>
   
       <script>
       Notiflix.Report.Init({});
Notiflix.Report.Failure('failure','"Failure is simply the opportunity to begin again, this time more intelligently."','');
       </script>
      <?php	} ?>
    <div class="card-header text-center">
      <a href="index.php" class="h1"><b>Login Now </b></a>
    </div>
    <div class="card-body">
   
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="username" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" autocomplete="off" name="password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />
        <div class="row">
       
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block" name="signin"><i class="fa fa-key"></i> Sign in</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

    
      <p class="mb-1">
        <a href="forgot-password.php">I forgot my password</a>
      </p>
    
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo BASE_URL;?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo BASE_URL;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo BASE_URL;?>/dist/js/adminlte.min.js"></script>


</body>
</html>
