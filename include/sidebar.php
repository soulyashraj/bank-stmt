<?php 

  $basename    = basename($_SERVER['REQUEST_URI']);	
  
  $currentPage = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME);

  include_once 'include/config.php';
  
  include_once 'include/admin-functions.php';
  
  $admin = new AdminFunctions();

  if(!$loggedInUserDetailsArr = $admin->sessionExists()){
    
    header("location: index.php");
    
    exit();
  
  }

 


?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../../index3.html" class="brand-link">
      <img src="<?php echo BASE_URL;?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light" style="font-size: 16px;">Doctor App </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo BASE_URL;?>/dist/img/unnamed.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="dashboard.php" class="d-block"><?php echo $loggedInUserDetailsArr['full_name'] ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
			   with font-awesome or any other icon font library -->
        <?php
					$patientRegistrationForm = array(
            'patient-Registration-Form.php',
					);
          ?>

        <?php
					$patientForm = array(
            'patient-Form.php',
					);
          ?>
			
			  <?php
					$certificatePages = array(
            'medical-certificate.php',
            'medical-certificate1.php',
            'medical-certificate2.php',
					);
				?>
			
			<?php
					$master = array(
            'disease-master.php',
            'medicine-master.php',
					);
				?>
			
			<li class="nav-item <?php if(in_array($currentPage, $master)){ echo 'menu-is-opening menu-open'; } ?>">
            <a href="#" class="nav-link  <?php if(in_array($currentPage, $master)){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Master
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" >
              <li class="nav-item loadstarter">
              <a href="disease-master.php" class="nav-link <?php if($currentPage == 'disease-master.php') { echo 'active'; } ?>">
                  <i class="far fa-circle nav-icon "></i>
                  <p>Disease Master</p>
                </a>
              </li>

              <li class="nav-item loadstarter">
              <a href="medicine-master.php" class="nav-link <?php if($currentPage == 'medicine-master.php') { echo 'active'; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicine Master</p>
                </a>
              </li>


            </ul>
          </li>

           <li class="nav-item">
            <a href="patient-Registration-Form.php" class="nav-link  <?php if(in_array($currentPage,$patientRegistrationForm)){ echo 'active'; } ?>">
               <i class="nav-icon fas fa-bed"></i>
              <p>
                Patient Registration Form
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="patient-Form.php" class="nav-link  <?php if(in_array($currentPage,$patientForm)){ echo 'active'; } ?>">
               <i class="nav-icon fas fa-user-md"></i>
              <p>
                Patient Form
              </p>
            </a>
          </li>
			
			<li class="nav-item <?php if(in_array($currentPage, $certificatePages)){ echo 'menu-is-opening menu-open'; } ?>">
            <a href="#" class="nav-link  <?php if(in_array($currentPage, $certificatePages)){ echo 'active'; } ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Certificate
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" >
              <li class="nav-item loadstarter">
              <a href="medical-certificate.php" class="nav-link <?php if($currentPage == 'medical-certificate.php') { echo 'active'; } ?>">
                  <i class="far fa-circle nav-icon "></i>
                  <p>Medicle Certificate</p>
                </a>
              </li>

              <li class="nav-item loadstarter">
              <a href="medical-certificate.php" class="nav-link <?php if($currentPage == 'medical-certificate.php') { echo 'active'; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicle Certificate1</p>
                </a>
              </li>

              <li class="nav-item loadstarter">
              <a href="medical-certificate.php" class="nav-link <?php if($currentPage == 'medical-certificate.php') { echo 'active'; } ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Medicle Certificate2</p>
                </a>
              </li>

            </ul>
          </li>

          <li class="nav-item loadstarter">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
              Logout
              </p>
            </a>
          </li>
        
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  
