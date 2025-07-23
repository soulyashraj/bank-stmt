<?php

    include_once 'include/config.php';
    
    include_once 'include/admin-functions.php';
    
    $admin = new AdminFunctions();

    if(!$loggedInUserDetailsArr = $admin->sessionExists()){
        
        header("location: index.php");
        
        exit();
    
    }

    $pageName    = "Medicine Master";
    
    $pageURL     = 'medicine-master.php';
    
    $deleteURL   = 'medicine-master.php';
    
    $tableName   = 'admin';

    $csrf        = new csrf();
    
    $token_id    = $csrf->get_token_id();
    
    $token_value = $csrf->get_token($token_id);

    $allPatient  = $admin-> getAllPatient();

    $results     = $admin->query("SELECT * FROM hp_medicine_master WHERE deleted_time = 0 ORDER BY id DESC");

    if(isset($_GET['delId']) && !empty($_GET['delId'])){
        
        $id     = $admin->escape_string($admin->strip_all($_GET['delId']));
        
        $delete = $admin->query("UPDATE ".PREFIX.$tableName." SET deleted_by='".$loggedInUserDetailsArr['id']."' AND deleted_time='".CURRENTMILIS."' WHERE id = '".$id."'");
        
        header("location:".$pageURL."?deletesuccess");
        
        exit();
    
    }

    if(isset($_POST['register'])){
        
        if($csrf->check_valid('post')) {
        
            $result = $admin->addDiseaseMaster($_POST,$loggedInUserDetailsArr['id'],'medicine_master');
            
            header("location:".$pageURL."?registersuccess");
            
            exit();
        
        }
    
    }

    if(isset($_GET['edit'])){
        
        $id            = $admin->escape_string($admin->strip_all($_GET['id']));
        
        $data          = $admin->getUniqueDiseaseMaster($id,'medicine_master');

    }

    if(isset($_POST['id']) && !empty($_POST['id'])) {
        
        if($csrf->check_valid('post')) {
        
            $id     = trim($admin->escape_string($admin->strip_all($_POST['id'])));
            
            $result = $admin->updateDiseaseMaster($_POST, $loggedInUserDetailsArr['id'],'medicine_master');
            
            header("location:".$pageURL."?updatesuccess");
            
            exit($_POST);
        
        }
    
    }

    $currentDate = date("Y-m-d", intval(CURRENTMILIS/1000) );

  // $currentTime = date("h:i", (CURRENTMILIS/1000) );

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Doctor - Ajay </title>
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome -->
    
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/fontawesome-free/css/all.min.css">
    
    <!-- DataTables -->
    
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- Select2 CSS -->

    <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/select2/css/select2.min.css">

    <!-- Boostrap Select CSS -->

    <link rel="stylesheet" href="<?php echo BASE_URL;?>/plugins/bootstrap-select/css/bootstrap-select.css">

    <!-- Theme style -->
    
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/dist/css/adminlte.min.css">
    
    <script src="<?php echo BASE_URL;?>/plugins/notiflix/notiflix-aio-1.5.0.min.js"></script>

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

        .add-more,
        .add-mores {
            /* margin-top: 30px; */
            /* border-radius : 50px; */
            box-shadow: 0 0 0 4px #eee;
        }

        .remove-rows,
        .remove-row {
            box-shadow: 0 0 0 4px #eee;
        }

    </style>

</head>

<body class="hold-transition sidebar-mini layout-footer-fixed">

    <!-- Site wrapper -->
    
    <div class="wrapper">
 
        <?php  include('include/header.php'); include('include/sidebar.php'); ?>

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
      
        <div class="content-wrapper">
          
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
        
            <?php if(isset($_GET['add']) || isset($_GET['edit'])) { ?>

                <section class="content"><!-- Main content -->
          
                    <form action="" id="form" method="post" enctype="multipart/form-data" autocomplete="off">

                        <div id="cardeffect" style="display:none;">
              
                            <div class="card" >
                            
                                <div class="card-header">
                            
                                    <h3 class="card-title"> <?php echo $pageName; ?></h3>
                                
                                    <a href="<?php echo $pageURL; ?>" align="right" style="font-size:14px;color:red;float:right;"> <i class="fas fa-hand-point-left"></i> Back</a>
                            
                                </div>
      
                                <div class="card-body">
                                
                                    <div class="form-group row">
                                       
                                        <div class="col-md-3 fromerrorcheck">
                                        
                                            <label>Medicine Name<em>*</em> </label>
                                            
                                            <input type="text" name="medicine_name" id="medicineName" value="<?php if(isset($_GET['edit'])) { echo $data['medicine_name']; } ?>" class="form-control form-control-sm rounded-0">
                                        
                                        </div>

                                        <div class="col-md-4 fromerrorcheck" style="margin-top:30px;">
                  
                                            <a class="btn btn-sm btn-block btn-primary" href="<?php echo BASE_URL;?>/example.xlsx" download><b>Sample Download Excel</b></a>
                                        
                                        </div>

                                        <div class="col-md-4 fromerrorcheck">
                
                                            <label>Upload Medicine Excel<em>*</em> </label>

                                            <input type="file" style="padding-top: 1px;" class="form-control form-control-sm rounded-0" name="upload_file" id="uploadFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                        
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

                                    <a href="javascript:void(0);" class="btn btn-primary" id="uploadBill" onclick="uploadBulkExcelFile()"><i class="fas fa-upload"></i> Upload Medicine Excel </a>

                                </div>

                            </div>

                        </div>
                                      
                    </form> 
                        
                </section>
        
            <?php } else { ?>

                <section class="content">

                    <div class="container-fluid">
                    
                        <div class="row">
                    
                            <div class="col-12">
                    
                                <div class="card">
                    
                                    <div class="card-header">
                            
                                        <h3 class="card-title"><?php echo $pageName; ?> Details</h3>
                            
                                    </div>
                
                                    <div class="card-body">
        
                                        <table id="example1" class="table table-bordered table-striped">
                            
                                            <thead>
                                
                                                <tr>
                                                
                                                    <th>Sr. No.</th>
                                                    
                                                    <th>Medicine Name</th>
                                                    
                                                    <th>Action</th>
                                            
                                                </tr>
                                            
                                            </thead>
                                        
                                            <tbody>
                            
                                                <?php $x=1; while($row = $admin->fetch($results)){  
                                                    
                                                ?>
                            
                                                    <tr>
                                                        
                                                        <td><?php echo $x; ?></td>
                                                                                                                
                                                        <td><?php echo $row['medicine_name']; ?></td>
                                                                                                                        
                                                        <td class="project-actions text-center">
                                                    
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
                        
                                            </tbody>
                    
                                        </table>
                  
                                    </div> <!-- /.card-body -->
                
                                </div> <!-- /.card -->
              
                            </div> <!-- /.col -->
            
                        </div> <!-- /.row -->
          
                    </div>  <!-- /.container-fluid -->
        
                </section>

            <?php }?>
      
        </div>
      
        <footer class="main-footer">
            
            <div class="float-left d-none d-sm-block">
            
                <a href="<?php echo $pageURL; ?>?add" class="btn btn-primary"> <i class="fas fa-plus-circle"></i> Create New <?php echo $pageName; ?></a>
            
            </div>
            
            <strong class="float-right">Copyright &copy; 2020-2021 <a href="https://sunnytailor.in">Sunny Tailor</a>. All rights reserved.</strong> 
        
        </footer>
      
        <aside class="control-sidebar control-sidebar-dark">
        
        </aside>
      
    
    </div>
    
</body>
    
<script src="<?php echo BASE_URL;?>/plugins/jquery/jquery.min.js"></script>
    
    <!-- Bootstrap 4 -->
    
    <script src="<?php echo BASE_URL;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables  & Plugins -->
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables/jquery.dataTables.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/jszip/jszip.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/pdfmake/pdfmake.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/pdfmake/vfs_fonts.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    
    <!-- AdminLTE App -->
    
    <script src="<?php echo BASE_URL;?>/dist/js/adminlte.min.js"></script>
    
    <!-- AdminLTE for demo purposes -->
    
    <!-- jquery-validation -->
    
    <script src="<?php echo BASE_URL;?>/plugins/jquery-validation/jquery.validate.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/plugins/jquery-validation/additional-methods.min.js"></script>
    
    <script src="<?php echo BASE_URL;?>/dist/js/demo.js"></script>
    
    <!-- Page specific script -->

    <!-- Select2 JS -->

	<script src="<?php echo BASE_URL;?>/plugins/select2/js/select2.min.js"></script>

    <!-- Boostrap Select CSS -->

    <script src="<?php echo BASE_URL;?>/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>

 
    
    $(function () {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
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
                full_name: {
                    required: true,
                },
                username: {
                    required: true,
                },
                sex: {
                    required: true,
                },
                mobile: {
                    required: true,
                },
                role: {
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


        callToEnhanceValidate();


    });


    function clearall() {

        $('input').val('');

        $('textarea').val('');

    }

    $('.loadstarter').on('click', function () {

        Notiflix.Loading.Init({});

        Notiflix.Loading.Hourglass();

    });


    function uploadBulkExcelFile() {

    var formData = new FormData();

    var valid = true;

    if (document.getElementById('uploadFile').files.length != 0) {
      
      valid = valid*true;

      $('#uploadFile').css('border','none');

    } else {

      valid = valid*false;

      $('#uploadFile').css('border','1px solid red');

    }

    if (valid) {

      $('#uploadBill').hide();
      
      formData.append('upload_bill', $('#uploadFile')[0].files[0]);

      formData.append('database_name', 'medicine_master');

      $.ajax({

        type: "POST",
        
        data : formData,
        
        url: 'update_disease_excel.php',
        
        cache: false,
        
        processData: false,
        
        contentType: false,
        
        success: function (res) {
        
          if (res.status == 'success') {

            window.location.href ="/disease-master.php";
                    
          } else {

            $('#uploadBill').show();

          }
                
        }
      
      });

    } else {

      $('#uploadBill').show();


    }

  }


    </script>

</html>