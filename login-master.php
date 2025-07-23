<?php

  include_once 'include/config.php';
  
  include_once 'include/admin-functions.php';
  
  $admin = new AdminFunctions();

  if(!$loggedInUserDetailsArr = $admin->sessionExists()){
    
    header("location: index.php");
    
    exit();
  
  }

  if ($loggedInUserDetailsArr['login_master']  == '0') {
   
    echo "<script>window.location.href='/dashboard.php';</script>";
   
    exit;

  }

  $pageName    = "Login Master";
  
  $pageURL     = 'login-master.php';
  
  $deleteURL   = 'login-master.php';
  
  $tableName   = 'admin';

  $csrf        = new csrf();
  
  $token_id    = $csrf->get_token_id();
  
  $token_value = $csrf->get_token($token_id);

  $vehicleNo    = $admin-> getAllVehicleNos($loggedInUserDetailsArr['company_id']);

  $results     = $admin->query("SELECT * FROM rr_admin WHERE   deleted_time = 0 AND company_id = ".$loggedInUserDetailsArr['company_id']." GROUP BY id DESC");

  if(isset($_GET['delId']) && !empty($_GET['delId'])){
    
    $id     = $admin->escape_string($admin->strip_all($_GET['delId']));
    
    $delete = $admin->query("UPDATE ".PREFIX.$tableName." SET deleted_by='".$loggedInUserDetailsArr['id']."' AND deleted_time='".CURRENTMILIS."' WHERE id = '".$id."'");
    
    header("location:".$pageURL."?deletesuccess");
    
    exit();
  
  }

  if(isset($_POST['register'])){
    
    if($csrf->check_valid('post')) {
    
      $result = $admin->addloginMaster($_POST,$loggedInUserDetailsArr['id'],$loggedInUserDetailsArr['company_id']);
    
      header("location:".$pageURL."?registersuccess");
    
      exit();
    
    }
  
  }

  if(isset($_GET['edit'])){
    
    $id               = $admin->escape_string($admin->strip_all($_GET['id']));
    
    $data             = $admin->getUniqueloginMasterById($id);

  }

  if(isset($_POST['id']) && !empty($_POST['id'])) {
    
    if($csrf->check_valid('post')) {
    
      $id     = trim($admin->escape_string($admin->strip_all($_POST['id'])));
    
      $result = $admin->updateloginMaster($_POST, $loggedInUserDetailsArr['id'],$loggedInUserDetailsArr['company_id']);
    
      header("location:".$pageURL."?updatesuccess");
    
      exit($_POST);
    
    }
  
  }


?>

<!DOCTYPE html>
<html lang="en">

  <head>
    
    <meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title> Ranveer Rao Transport | <?php echo $pageName; ?></title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome -->
    
    <link rel="stylesheet" href="../../ajay/plugins/fontawesome-free/css/all.min.css">
    
    <!-- DataTables -->
    
    <link rel="stylesheet" href="../../ajay/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    
    <link rel="stylesheet" href="../../ajay/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    
    <link rel="stylesheet" href="../../ajay/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Select2 CSS -->

    <link rel="stylesheet" href="../../ajay/plugins/select2/css/select2.min.css">

    <!-- Boostrap Select CSS -->

    <link rel="stylesheet" href="../../ajay/plugins/bootstrap-select/css/bootstrap-select.css">

    <!-- Theme style -->
    
    <link rel="stylesheet" href="../../ajay/dist/css/adminlte.min.css">
    
    <script src="../../ajay/plugins/notiflix/notiflix-aio-1.5.0.min.js"></script>
  
  </head>

  <style>
    label {
      font-size: 12px;
    }

    .dataTables_wrapper {
      font-size: 14px;
    }

    em {
      color: red;
    }
  
  </style>
  
  <body class="hold-transition sidebar-mini layout-footer-fixed">

    <!-- Site wrapper -->
    
    <div class="wrapper">
 
      <?php 
      
        include('include/header.php');
        
        include('include/sidebar.php');
      
      ?>

      <!-- Content Wrapper. Contains page content -->
      
      <?php if(isset($_GET['registersuccess'])){ ?>
          
        <script>
              
          Notiflix.Notify.Init({});
              
          Notiflix.Notify.Success('<?php echo $pageName; ?> successfully Added');
          
        </script>
      
      <?php } ?>

      <?php if(isset($_GET['updatesuccess'])){ ?>

        <script>
             
          Notiflix.Notify.Init({});
          
          Notiflix.Notify.Warning('<?php echo $pageName; ?> successfully updated');
        
        </script>
      
      <?php } ?>

      <?php if(isset($_GET['deletesuccess'])){ ?>
        
        <script>
          
          Notiflix.Notify.Init({});
          
          Notiflix.Notify.Failure('<?php echo $pageName; ?> successfully deleted');
       
       </script>

      <?php } ?>

      <?php if(isset($_GET['erroremail'])){ ?>
        
        <script>
          
          Notiflix.Notify.Init({});
          
          Notiflix.Notify.Failure('<?php echo $pageName; ?> Duplicate Email');
       
       </script>

      <?php } ?>

      <!-- Content Wrapper. Contains page content -->
      
      <div class="content-wrapper">
        
        <!-- Content Header (Page header) -->
          
        <section class="content-header">
          
          <div class="container-fluid">
          
            <div class="row mb-2">
          
              <div class="col-sm-6">
          
                <h1> <?php echo $pageName; ?></h1>
          
              </div>
          
              <div class="col-sm-6">
          
                <ol class="breadcrumb float-sm-right">
          
                  <li class="breadcrumb-item"><a href="#">Master</a></li>
          
                  <li class="breadcrumb-item active"> <?php echo $pageName; ?></li>
          
                </ol>
          
              </div>
          
            </div>
          
          </div><!-- /.container-fluid -->
        
        </section>
        
        <!-- Main content -->

        <section class="content" >
          
          <?php if(isset($_GET['add']) || isset($_GET['edit'])) { ?>
            
            <form action="" id="form" method="post" enctype="multipart/form-data" autocomplete="off">
              
                <!-- Default box -->
              
              <div id="cardeffect" style="display:none;">
              
              <div class="card" >
              
                <div class="card-header">
              
                  <h3 class="card-title"> <?php echo $pageName; ?></h3>
              
                  <a href="<?php echo $pageURL; ?>" align="right" style="font-size:14px;color:red;float:right;"> <i class="fas fa-hand-point-left"></i> Back</a>
              
                </div>
      
                <div class="card-body">
                  
                  <div class="form-group row">
                  
                    <div class="col-md-3 fromerrorcheck">
                  
                      <label>Full Name<em>*</em> </label>
                  
                      <input type="text" name="full_name"  value="<?php if(isset($_GET['edit'])) { echo $data['full_name']; } ?>" class="form-control form-control-sm rounded-0">
                    
                    </div>
                    
                    <div class="col-md-3 fromerrorcheck">
                    
                      <label>User Name<em>*</em> </label>
                    
                      <input type="text" name="username" value="<?php if(isset($_GET['edit'])) { echo $data['username']; } ?>" class="form-control form-control-sm rounded-0">
                    
                    </div>
                    
                    <div class="col-md-3 fromerrorcheck">
                    
                      <label>Email<em>*</em> </label>
                    
                      <input type="text" name="email" value="<?php if(isset($_GET['edit'])) { echo $data['email']; } ?>"  class="form-control form-control-sm rounded-0">
                    
                    </div>

                    <div class="col-md-3 fromerrorcheck">
                      
                      <label>Mobile No<em>*</em> </label>
                      
                      <input type="text" name="mobile" value="<?php if(isset($_GET['edit'])) { echo $data['mobile']; } ?>" class="form-control form-control-sm rounded-0">
                    
                    </div>

                  </div>
                  
                  <div class="form-group row">

                    <div class="col-md-3 fromerrorcheck">
                        
                      <label>Password<em>*</em> </label>
                      
                      <input type="password" name="password" class="form-control form-control-sm rounded-0">
                      
                    </div>

                    <div class="col-md-3 fromerrorcheck">
                      
                      <label>Role<em>*</em> </label>

                      <select class="form-control form-control-sm rounded-0" name="role">

                        <option value="" >Select Role </option>

                        <option value="admin" <?php if(isset($_GET['edit']) and $data['role']=='admin') { echo 'selected'; } ?>>Admin </option>
                        
                        <option value="manager" <?php if(isset($_GET['edit']) and $data['role']=='manager') { echo 'selected'; } ?>>Manager </option>
                      
                        <option value="supervisor" <?php if(isset($_GET['edit']) and $data['role']=='supervisor') { echo 'selected'; } ?>>Supervisor </option>

                        <option value="other" <?php if(isset($_GET['edit']) and $data['role']=='other') { echo 'selected'; } ?>>Other </option>

                      </select>
                                            
                    </div> 

                    <div class="col-sm-2 fromerrorcheck">
                      
                      <label>Active</label>
                      
                      <select class="form-control form-control-sm " name="active">
                      
                        <option value="1" <?php if(isset($_GET['edit']) and $data['active']=='1') { echo 'selected'; } ?>>Yes </option>
                        
                        <option value="0" <?php if(isset($_GET['edit']) and $data['active']=='0') { echo 'selected'; } ?>>No </option>
                      
                      </select>
                    
                    </div>

                  </div>

                  <div class="form-group row">

                    <div class="col-sm-12 fromerrorcheck">
                      
                      <label>Master Page Access</label>
                    
                    </div>

                    <div class="col-sm-2 fromerrorcheck">

                        <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['customer_master']=='1') { echo 'checked'; }?> name="customer_master">
                        
                        <label for="defaultCheck">Customer Master</label>

                    </div>

                    <div class="col-sm-2 fromerrorcheck">

                        <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['vehicle_master']=='1') { echo 'checked'; }?> name="vehicle_master">
                        
                        <label for="defaultCheck">Vehicle Master</label>

                    </div>

                    <div class="col-sm-2 fromerrorcheck">

                        <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['vehicle_allotment']=='1') { echo 'checked'; }?> name="vehicle_allotment">
                        
                        <label for="defaultCheck">Vehicle Allotment</label>

                    </div>

                    <div class="col-sm-2 fromerrorcheck">

                        <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['supplier_master']=='1') { echo 'checked'; }?> name="supplier_master">
                        
                        <label for="defaultCheck">Supplier Master</label>

                    </div>

                    <div class="col-sm-2 fromerrorcheck">

                        <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['login_master']=='1') { echo 'checked'; }?> name="login_master">
                        
                        <label for="defaultCheck">Login Master</label>

                    </div>

                    <div class="col-sm-2 fromerrorcheck">

                        <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['operation']=='1') { echo 'checked'; }?> name="operation">

                        <label for="defaultCheck">Operations</label>

                    </div>
                  
                    <div class="col-sm-2 fromerrorcheck">

                    <input type="checkbox" id="defaultCheck" <?php if(isset($_GET['edit']) and $data['account']=='1') { echo 'checked'; }?> name="account">

                    <label for="defaultCheck">Accounts</label>

                    </div>
                  
                  </div>

                </div>

                <div class="card-footer">
                  
                  <input type="hidden" name="<?php echo $token_id; ?>" value="<?php echo $token_value; ?>" />

                  <?php if(isset($_GET['edit'])){ ?>

                    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id ?>"/>

                    <button type="submit" name="update" value="update" id="update" class="btn btn-warning"><i class="fas fa-save"></i> Update <?php echo $pageName; ?></button>

                  <?php } else { ?>

                  <button type="submit" name="register" id="register" class="btn btn-success"><i class="fas fa-save"></i> Add <?php echo $pageName; ?></button>

                  <?php } ?>
                  
                  <a class="btn btn-danger" href="javascript:void(0);" onclick="clearall()"><i class="fas fa-broom"></i>Clear All</a>

                </div>
                
                <!-- /.card-footer-->
              </div>
              
              <!-- /.card -->  
              
            </form> 
            
          <?php } ?>

        </section>
        
        <!-- /.content -->
        
        <!-- Main content -->
        
        <section class="content">
        
          <div class="container-fluid">
        
            <div class="row">
        
              <div class="col-12">
        
                <div class="card">
        
                  <div class="card-header">
        
                    <h3 class="card-title"><?php echo $pageName; ?> Details</h3>
        
                  </div>
        
                  <!-- /.card-header -->
        
                  <div class="card-body">
        
                    <table id="example1" class="table table-bordered table-striped">
        
                      <thead>
        
                        <tr>
                          
                          <th>Sr. No.</th>
                          
                          <th>Name</th>
                          
                          <th>User Name</th>
                          
                          <th>Email</th>
                        
                          <th>Contact No</th>
                          
                          <th>Role</th>
                          
                          <th>Status</th>
                          
                          <th>Action</th>
                      
                        </tr>
                      
                      </thead>
                      
                      <tbody>
                        
                        <?php $x=1; while($row = $admin->fetch($results)){  ?>
                          
                          <tr>
                            
                            <td><?php echo $x; ?></td>
                            
                            <td><?php echo $row['full_name']; ?></td>
                            
                            <td><?php echo $row['username']; ?></td>
                            
                            <td><?php echo $row['email']; ?></td>
                            
                            <td><?php echo $row['mobile']; ?></td>
                            
                            <td> <?php echo $row['role']; ?></td>
                            
                            <td><div class="badge badge-<?php echo $row['active'] == '1'?'success':'danger'; ?> ml-2"><?php echo $row['active'] == '1'?'Active':'Inactive'; ?></div></td>
                            
                            <td class="project-actions text-center">
                              <!-- <a class="btn btn-primary btn-sm" href="#">
                                  <i class="fas fa-folder">
                                  </i>
                                  View
                              </a> -->
                              
                              <a class="btn btn-info btn-sm" href="<?php echo $pageURL; ?>?edit&id=<?php echo $row['id']; ?>">
                                
                                <i class="fas fa-pencil-alt"></i>
                                
                                Edit
                              
                              </a>
                              
                              <a class="btn btn-danger btn-sm"  href="<?php echo $pageURL; ?>?delId=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete?');" >
                                
                                <i class="fas fa-trash"></i>
                                
                                Delete
                              
                              </a>
                            
                            </td>                
                      
                          </tr>
                        
                        <?php $x++; } ?>
                      
                      </tfoot>
                    
                    </table>
                  
                  </div>
                  
                  <!-- /.card-body -->
                
                </div>
                
                <!-- /.card -->
              
              
              </div>
              
              <!-- /.col -->
            
            </div>
            
            <!-- /.row -->
          
          </div>
          
          <!-- /.container-fluid -->
        
        </section>
        
        <!-- /.content -->
  
      </div>
      
      <!-- /.content-wrapper -->
      
      <footer class="main-footer">
        
        <div class="float-left d-none d-sm-block">
        
          <a href="<?php echo $pageURL; ?>?add" class="btn btn-primary"> <i class="fas fa-plus-circle"></i> Create New <?php echo $pageName; ?></a>
        
        </div>
        
        <strong class="float-right">Copyright &copy; 2020-2021 <a href="https://sunnytailor.in/">Sunny Tailor</a>. All rights reserved.</strong> 
      
      </footer>

      <!-- Control Sidebar -->
      
      <aside class="control-sidebar control-sidebar-dark">
        
        <!-- Control sidebar content goes here -->
      
      </aside>
      
      <!-- /.control-sidebar -->
    
    </div>
    
    <!-- ./wrapper -->

    <!-- jQuery -->
    
    <script src="../../ajay/plugins/jquery/jquery.min.js"></script>
    
    <!-- Bootstrap 4 -->
    
    <script src="../../ajay/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables  & Plugins -->
    
    <script src="../../ajay/plugins/datatables/jquery.dataTables.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    
    <script src="../../ajay/plugins/jszip/jszip.min.js"></script>
    
    <script src="../../ajay/plugins/pdfmake/pdfmake.min.js"></script>
    
    <script src="../../ajay/plugins/pdfmake/vfs_fonts.js"></script>
    
    <script src="../../ajay/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    
    <script src="../../ajay/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    
    <!-- AdminLTE App -->
    
    <script src="../../ajay/dist/js/adminlte.min.js"></script>
    
    <!-- AdminLTE for demo purposes -->
    
    <!-- jquery-validation -->
    
    <script src="../../ajay/plugins/jquery-validation/jquery.validate.min.js"></script>
    
    <script src="../../ajay/plugins/jquery-validation/additional-methods.min.js"></script>
    
    <script src="../../ajay/dist/js/demo.js"></script>
    
    <!-- Page specific script -->

    <!-- Select2 JS -->

	<script src="../../ajay/plugins/select2/js/select2.min.js"></script>

    <!-- Boostrap Select CSS -->

    <script src="../../ajay/plugins/bootstrap-select/js/bootstrap-select.js"></script>


    <script>

    //   $(document).ready(function() {
      
    //     $('.select2').select2();

    //   });
    
    $(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});

      $(function () {
        $("#example1").DataTable({
          "responsive": true, "lengthChange": false, "autoWidth": false,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false,
          "responsive": true,
        });
      });

      <?php if(isset($_GET['add']) || isset($_GET['edit'])) { ?>
          
          $("#cardeffect").slideDown("slow");
      
      <?php } ?>
      
      $(function () {
    
        $('#form').validate({
          rules: {
            ignore: [],
            debug: false,
            full_name : {
              required: true,
            },
            username : {
              required: true,
            },
            email : {
              required: true,
            },
            mobile : {
              required: true,
            },
            role : {
              required: true,
            },
            
          },
          messages: {
            email: {
              required: "Please enter a email address",
              email: "Please enter a vaild email address"
            },
          },
          errorElement: 'span',
          errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.fromerrorcheck').append(error);
          },
          highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
          },
          unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
          }
        });


        var callToEnhanceValidate = function () {
          $(".details_title").each(function () {
            $(this).rules('remove');
            $(this).rules('add', {
              required: true,
            });
          });
        }

        $(".add-more").on("click", function () {

          var count = $(".clone-row").find(".form-group").length;

          $.ajax({

            type: 'POST',

            data: 'count=' + count,

            url: 'getAjaxAddMoreVehicleDetails.php',

            success: function (services_clone) {

              $(".clone-row").append(services_clone);

            }

          });

          $(".remove-row").on("click", function () {
            $(this).closest(".form-group").remove();
          });
          callToEnhanceValidate();

        });

        $(".remove-row").on("click", function () {

          $(this).closest(".form-group").remove();

        });
        callToEnhanceValidate();

      });

      function clearall(){
       
        $('input').val('');
       
        $('textarea').val('');
      
      }

      $('.loadstarter').on('click',function(){
      
        Notiflix.Loading.Init({});
      
         Notiflix.Loading.Hourglass();

     });


       function vehicleType(e) {


        let vehicleNo   = $(e).val();

        let typeName    = $(e).attr('name');

        let printResult = checkduplicateValue(vehicleNo,typeName);

        let valid       = true;

        if (vehicleNo != '' && printResult == valid) {

          $.ajax({

            type: 'POST',

            data: 'vehicleNo=' + vehicleNo,

            url: 'getAjaxVehicleType.php',

            cache: false,

            success: function (res) {

              if (res.status == 'success') {

                $(e).parent().parent().find('.vehicle_type').val(res.vehicleType);


              } else {

                $(e).parent().parent().find('.vehicle_type').val('');

              }

            }

          });

        } else {

          $(e).parent().parent().find('.vehicle_type').val('');

        }

      }

      
      function checkduplicateValue(e,typeName) {

        let vehicleNo  = e;

        let className  = typeName;
       
        let valid      = true;

        $('.vehicle_no').each(function () {

          if ($(this).val() == vehicleNo && $(this).attr('name') != className) {

            alert('duplicate Vehicle found!');

            return valid = false;

          } 

        });
        
        return valid;

      }
        
        
    </script>

  </body>

</html>