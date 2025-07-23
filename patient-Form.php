<?php

    include_once 'include/config.php';
    
    include_once 'include/admin-functions.php';
    
    $admin = new AdminFunctions();

    if(!$loggedInUserDetailsArr = $admin->sessionExists()){
        
        header("location: index.php");
        
        exit();
    
    }

    $pageName    = "Patient Form";
    
    $pageURL     = 'patient-Form.php';
    
    $deleteURL   = 'patient-Form.php';
    
    $tableName   = 'patient_registration';

    $csrf        = new csrf();
    
    $token_id    = $csrf->get_token_id();
    
    $token_value = $csrf->get_token($token_id);

    $allPatient  = $admin-> getAllPatient();

    $results     = $admin->query("SELECT * FROM hp_patient_registration WHERE deleted_time = 0 ORDER BY id DESC");

    if(isset($_GET['delId']) && !empty($_GET['delId'])){
        
        $id     = $admin->escape_string($admin->strip_all($_GET['delId']));

       $delete = $admin->query("UPDATE ".PREFIX.$tableName." SET deleted_by='".$loggedInUserDetailsArr['id']."', deleted_time='".CURRENTMILIS."' WHERE id = '".$id."'");
        
        header("location:".$pageURL."?deletesuccess");
        
        exit();
    
    }

    if(isset($_POST['register'])){
        
        if($csrf->check_valid('post')) {
        
            $result = $admin->addPatientForm($_POST,$loggedInUserDetailsArr['id']);
            
            header("location:".$pageURL."?registersuccess");
            
            exit();
        
        }
    
    }

    if(isset($_GET['edit'])){
        
        $id            = $admin->escape_string($admin->strip_all($_GET['id']));
        
        $data          = $admin->getUniquePatientForm($id);

        $casePaperData = $admin->getUniqueCasePaperById($id);

        $medicineData  = $admin->getUniqueMedicineById($id);

    }

    if(isset($_POST['id']) && !empty($_POST['id'])) {
        
        if($csrf->check_valid('post')) {
        
            $id     = trim($admin->escape_string($admin->strip_all($_POST['id'])));
            
            $result = $admin->updatePatientForm($_POST, $loggedInUserDetailsArr['id']);
            
            header("location:".$pageURL."?updatesuccess");
            
            exit($_POST);
        
        }
    
    }

    $currentDate = date("Y-m-d", (CURRENTMILIS/1000) );

  // $currentTime = date("h:i", (CURRENTMILIS/1000) );

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Doctor - Ajay </title>
    
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
                                    
                                        <div class="col-md-6 fromerrorcheck">
                                    
                                            <label>Patient Name<em>*</em> </label>
                                                        
                                            <select class="form-control form-control-sm rounded-0 select2" name="patient_name" id="patientName" onchange="patientDetails(this)" style="width:100%;">
                        
                                                <option value="" >Select Patient Name</option>

                                                <?php while ($row = $admin->fetch($allPatient)) { ?>
                                                
                                                        <option value="<?php echo $row['id']; ?>" <?php if(isset($_GET['edit']) and $data['patient_name']== $row['id']) { echo 'selected'; }?> ><?php echo $row['patient_name']; ?></option>
                                                
                                                <?php } ?>   

                                            </select>

                                        </div>
                    
                                        <div class="col-md-2 fromerrorcheck">
                                        
                                            <label>Patient Age<em>*</em> </label>
                                            
                                            <input type="text" name="patient_age" id="patientAge" value="<?php if(isset($_GET['edit'])) { echo $data['patient_age']; } ?>" class="form-control form-control-sm rounded-0">
                                        
                                        </div>
                    
                                        <div class="col-md-2 fromerrorcheck">
                                        
                                            <label>Patient Sex<em>*</em> </label>

                                            <select class="form-control form-control-sm " name="sex" id="sex">

                                                <option value="">Select Sex </option>
                                            
                                                <option value="male" <?php if(isset($_GET['edit']) and $data['sex']=='male') { echo 'selected'; } ?>>Male </option>
                                                
                                                <option value="female" <?php if(isset($_GET['edit']) and $data['sex']=='female') { echo 'selected'; } ?>>Female </option>
                                            
                                                <option value="other" <?php if(isset($_GET['edit']) and $data['sex']=='other') { echo 'selected'; } ?>>Other </option>

                                            </select>
                                        
                                        </div>

                                        <div class="col-md-2 fromerrorcheck">
                                        
                                            <label>Mobile Number<em>*</em> </label>
                                            
                                            <input type="text" name="contact_no" id="contactNo" value="<?php if(isset($_GET['edit'])) { echo $data['contact_no']; } ?>" class="form-control form-control-sm rounded-0">
                                        
                                        </div>

                                    </div>
                  
                                    <div class="form-group row">

                                        <div class="col-md-6 fromerrorcheck">
                                            
                                            <label>Address<em>*</em> </label>
                                            
                                            <textarea type="text" name="address" id="address" class="form-control form-control-sm rounded-0"> <?php if(isset($_GET['edit'])) { echo $data['address']; } ?></textarea>                      

                                        </div>

                                        <div class="col-md-2 fromerrorcheck">
                                        
                                            <label>LMP<em>*</em> </label>

                                            <input type="text" name="lmp" value="<?php if(isset($_GET['edit'])) { echo $data['lmp']; } ?>" class="form-control form-control-sm rounded-0">
                            
                                        </div> 

                                        <div class="col-md-2 fromerrorcheck">
                                        
                                            <label>Date<em>*</em> </label>

                                            <input type="date" name="registration_date" value="<?php if(isset($_GET['edit'])) { echo $data['registration_date']; } else { echo $currentDate;} ?>" class="form-control form-control-sm rounded-0" readonly>
                            
                                        </div> 

                                        <div class="col-md-2 fromerrorcheck">
                                        
                                            <label>Time<em>*</em> </label>

                                            <input type="time" name="registration_time" value="<?php if(isset($_GET['edit'])) { echo $data['registration_time']; }?>" class="form-control form-control-sm rounded-0">
                            
                                        </div> 

                                    </div>


                                </div>

                            </div>

                            <div class="card" >

                                <div id="accordion" class="accordion">
                    
                                    <div class="card mb-0">

                                        <div class="card-header">
                                    
                                            <h3 class="card-title"> Case Paper </h3>
                                        
                                            <a href="javascript:void(0)" align="right" style="font-size:14px;color:red;float:right;"> C/O(complaint)</a>
                                    
                                        </div>
                
                                        <div  class="card-body">
                                                            
                                            <table class="table table-bordered" id="casePaperTable">

                                                <thead class="thead-dark">

                                                    <tr>

                                                        <th>Disease Name</th>
                                                        
                                                        <th>Days</th>
                                                        
                                                        <th>
                                                            
                                                            <button type="button" id="add-more" class="btn btn-sm btn-warning add-more"><i class="fa fa-plus"></i></button>
                                                        
                                                        </th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    <?php 
                                                        
                                                        if(isset($_GET['edit'])) { 
                            
                                                        $i = 0;

                                                        while($row = $admin->fetch($casePaperData)) {
                                                    
                                                    ?>
    
                                                        <tr class="casePaperDetails">

                                                            <td>
                                                                                                                                
                                                                <input type="text" name="disease_name[<?php echo $i; ?>]" class="form-control form-control-sm rounded-0 disease_name removeCasePaper" value="<?php echo $row['disease_name']; ?>" required>

                                                            </td>

                                                            <td>
                                                                                                                            
                                                                <input type="text" name="disease_days[<?php echo $i; ?>]" class="form-control form-control-sm rounded-0 removeCasePaper" value="<?php echo $row['disease_days']; ?>" required>

                                                            </td>

                                                            <td>
                                                                    
                                                                <a href="javascript:void(0);" onclick="removeitem(this,'casePaperDetails','removeCasePaper')" class="btn btn-sm btn-danger remove-rows"><i class="fa fa-times"></i></a>
                                                                
                                                            </td>
                                                        
                                                        </tr>
                                        
                                                    <?php $i++; } } else { ?>

                                                        <tr class="casePaperDetails">

                                                            <td>
                                                                                                                                
                                                                <input type="text" name="disease_name[0]" class="form-control form-control-sm rounded-0 removeCasePaper" required>

                                                            </td>

                                                            <td>
                                                                                                                            
                                                                <input type="text" name="disease_days[0]" class="form-control form-control-sm rounded-0 removeCasePaper" required>

                                                            </td>

                                                            <td>
                                                                    
                                                                <a href="javascript:void(0);" onclick="removeitem(this,'casePaperDetails','removeCasePaper')" class="btn btn-sm btn-danger remove-rows"><i class="fa fa-times"></i></a>
                                                                
                                                            </td>
                                                        
                                                        </tr>

                                                    <?php } ?>
                        
                                                </tbody>
                                                    
                                            </table>
                                            
                                        </div>

                                        <div class="card-header">
                                    
                                            <h3 class="card-title"> O/E </h3>
                                                                            
                                        </div>

                                        <div class="card-body" >

                                            <div class="form-group row">

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>Temperature<em>*</em> </label>
                                                    
                                                    <input type="text" maxlength="8" name="temperature" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['temperature']; } else { echo '';} ?>">                     

                                                </div>

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>P<em>*</em> </label>
                                                    
                                                    <input type="text" name="p" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['p']; } else { echo '';} ?>" maxlength="7">                     

                                                </div> 

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>BP<em>*</em> </label>
                                                    
                                                    <input type="text" name="bp" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['bp']; } else { echo '';} ?>" maxlength="12">                     

                                                </div> 

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>SP02<em>*</em> </label>
                                                    
                                                    <input type="text" name="sp02" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['sp02']; } else { echo '98%';} ?>" maxlength="3">                     

                                                </div> 

                                            </div>

                                        </div>

                                        <div class="card-header collapsed">
                                    
                                            <h3 class="card-title"> S/E </h3>
                                                                            
                                        </div>
            
                                        <div  class="card-body" >

                                            <div class="form-group row">

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>RS<em>*</em> </label>
                                                    <select class="form-control form-control-sm rounded-0 removeItem" name="rs" required>

                                                        <option value="">Select RS</option>
                                                        <option value="clear" <?php if(isset($_GET['edit']) and $data['rs']=='clear') { echo 'selected'; } ?>>Clear</option>
                                                        <option value="congestion" <?php if(isset($_GET['edit']) and $data['rs']=='congestion') { echo 'selected'; } ?>>Congestion</option>
                                                                    
                                                    </select>


                                                </div>

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>CVS<em>*</em> </label>
                        
                                                    <select class="form-control form-control-sm rounded-0 removeItem" name="cvs" required>

                                                        <option value="">Select CVS</option>
                                                        <option value="SIS1 normal" <?php if(isset($_GET['edit']) and $data['cvs']=='SIS1 normal') { echo 'selected'; } ?>>SIS1 Normal</option>
                                                        <option value="SIS2 abnormal" <?php if(isset($_GET['edit']) and $data['cvs']=='SIS2 abnormal') { echo 'selected'; } ?>>SIS2 Abnormal</option>
                                                                        
                                                    </select>

                                                </div> 

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>CNS<em>*</em> </label>
                        
                                                    <select class="form-control form-control-sm rounded-0 removeItem" name="cns" required>

                                                        <option value="">Select CVS</option>
                                                        <option value="concious" <?php if(isset($_GET['edit']) and $data['cns']=='concious') { echo 'selected'; } ?>>Concious</option>
                                                        <option value="semi concious" <?php if(isset($_GET['edit']) and $data['cns']=='semi concious') { echo 'selected'; } ?>>Semi Concious</option>
                                                        <option value="unconcious" <?php if(isset($_GET['edit']) and $data['cns']=='unconcious') { echo 'selected'; } ?>>Unconcious</option>
                                                                            

                                                    </select>

                                                </div> 

                                                <div class="col-md-3 fromerrorcheck">
                                                    
                                                    <label>P/A<em>*</em> </label>
                        
                                                    <select class="form-control form-control-sm rounded-0 removeItem" name="pa" required>

                                                        <option value="">Select PA</option>
                                                        <option value="soft" <?php if(isset($_GET['edit']) and $data['pa']=='soft') { echo 'selected'; } ?>>Soft</option>
                                                        <option value="tender" <?php if(isset($_GET['edit']) and $data['pa']=='tender') { echo 'selected'; } ?>>Tender</option>
                                                        <option value="non-tender" <?php if(isset($_GET['edit']) and $data['pa']=='non-tender') { echo 'selected'; } ?>>Non Tender</option>
                                                                            

                                                    </select>


                                                </div> 

                                            </div>

                                        </div>
                    
                                        <div class="card-header">
                                    
                                            <h3 class="card-title"> K/C/O </h3>
                                                                            
                                        </div>
                                
                                        <div class="card-body"  >

                                            <div class="form-group row">

                                                <div class="col-md-4 fromerrorcheck">
                                                    
                                                    <label>HTN<em>*</em> </label>
                        
                                                    <input type="text" name="htn" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['htn']; } else { echo '';} ?>">                     

                                                </div>

                                                <div class="col-md-4 fromerrorcheck">
                          
                                                    <label>DM<em>*</em> </label>
                                                    
                                                    <input type="text" name="dm" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['dm']; } else { echo '';} ?>">                     

                                                </div> 

                                                <div class="col-md-4 fromerrorcheck">
                                                    
                                                    <label>Thyroad<em>*</em> </label>
                                                    
                                                    <input type="text" name="thyroad" class="form-control form-control-sm rounded-0" value = "<?php if(isset($_GET['edit'])) { echo $data['thyroad']; } else { echo '';} ?>">                     

                                                </div> 

                                            </div>

                                        </div>

                                        <div class="card-header">
                                    
                                            <h3 class="card-title"> Other </h3>
                                                                            
                                        </div>
                                
                                        <div  class="card-body" >

                                            <div class="form-group row">

                                                <div class="col-md-12 fromerrorcheck">
                          
                                                    <textarea type="text" name="other_description" class="form-control form-control-sm rounded-0"><?php if(isset($_GET['edit'])) { echo $data['other_description']; } ?></textarea>
                            
                                                </div>

                                            </div>

                                            <div class="form-group row">

                                                <div class="col-md-12 fromerrorcheck">
                          
                                                    <table class="table table-bordered" id="otherTable">

                                                        <thead class="thead-dark">

                                                            <tr>

                                                                <th>Medicine Name</th>
                                                                
                                                                <th>Medicine Schedule</th>
                                                                
                                                                <th>Medicine Schedule</th>
                                                                
                                                                <th>How Many Days Take a Medicine ?</th>
                                                                
                                                                <th>
                                                                    
                                                                    <button type="button" id="add-mores" class="btn btn-sm btn-warning add-mores"><i class="fa fa-plus"></i></button>
                                                                
                                                                </th>

                                                            </tr>

                                                        </thead>

                                                        <tbody>
                                                            
                                                            <?php 
                                                            
                                                                if(isset($_GET['edit'])) { 

                                                                    $i = 0; 
										
                                                                    while($row = $admin->fetch($medicineData)) {

                                                            ?>

                                                                <tr class="supplierDetalis">

                                                                    <td>
                                                                            
                                                                        <input type="text" name="medicine_name[<?php echo $i; ?>]" value="<?php echo $row['medicine_name']; ?>" class="form-control form-control-sm rounded-0 removeItem" required>
                                                                    
                                                                    </td>

                                                                    <td>

                                                                        <select class="form-control form-control-sm rounded-0 removeItem" name="medicine_taken_process[<?php echo $i; ?>]" required>

                                                                            <option value="">Select Medicine Schedule</option>
                                                                        
                                                                            <option value="1-1-1" <?php if($row['medicine_taken_process']=='1-1-1') { echo 'selected'; } ?>>1-1-1</option>
                                                                            
                                                                            <option value="1-0-1" <?php if($row['medicine_taken_process']=='1-0-1') { echo 'selected'; } ?>>1-0-1</option>
                                                                        
                                                                            <option value="0-0-1" <?php if($row['medicine_taken_process']=='0-0-1') { echo 'selected'; } ?>>0-0-1</option>

                                                                            <option value="1-0-0" <?php if($row['medicine_taken_process']=='1-0-0') { echo 'selected'; } ?>>1-0-0</option>

                                                                            <option value="0-1-0" <?php if($row['medicine_taken_process']=='0-1-0') { echo 'selected'; } ?>>0-1-0</option>

                                                                        </select>

                                                                    </td>

                                                                    <td>

                                                                        <input type="text" name="medicine_af_bf[<?php echo $i; ?>]" value="<?php echo $row['medicine_af_bf']; ?>" class="form-control form-control-sm rounded-0 removeItem" required>
                                                                    
                                                                    </td>

                                                                    <td>

                                                                        <input type="text" name="medicine_days[<?php echo $i; ?>]" value="<?php echo $row['medicine_days']; ?>" class="form-control form-control-sm rounded-0 removeItem" required>                     

                                                                    </td>

                                                                    <td>

                                                                        <a href="javascript:void(0);" onclick="removeitem(this,'supplierDetalis','removeItem')" class="btn btn-sm btn-danger remove-rows"><i class="fa fa-times"></i></a>
                                                                    
                                                                    </td>
                                                                
                                                                </tr>

                                                            <?php $i++;} } else { ?>

                                                                <tr class="supplierDetalis">
                                                                    
                                                                    <td>
                                                                        
                                                                        <input type="text" name="medicine_name[0]" class="form-control form-control-sm rounded-0 removeItem" required>
                                                                    
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        
                                                                        <select class="form-control form-control-sm rounded-0 removeItem" name="medicine_taken_process[0]" required>

                                                                            <option value="">Select Medicine Schedule</option>

                                                                            <option value="1-1-1">1-1-1</option>

                                                                            <option value="1-0-1">1-0-1</option>

                                                                            <option value="0-0-1">0-0-1</option>

                                                                            <option value="1-0-0">1-0-0</option>

                                                                            <option value="0-1-0">0-1-0</option>

                                                                        </select>
                                                                    
                                                                    </td>

                                                                    <td>
                                                                        
                                                                        <input type="text" name="medicine_af_bf[0]" class="form-control form-control-sm rounded-0 removeItem" required>
                                                                    
                                                                    </td>

                                                                    <td>
                                                                        
                                                                        <input type="text" name="medicine_days[0]" class="form-control form-control-sm rounded-0 removeItem" required>
                                                                    
                                                                    </td>

                                                                    <td>

                                                                        <a href="javascript:void(0);" onclick="removeitem(this,'supplierDetalis','removeItem')" class="btn btn-sm btn-danger remove-rows"><i class="fa fa-times"></i></a>
                                                                    
                                                                    </td>

                                                                </tr>
                                                               
                                                            <?php } ?>

                                                        </tbody>

                                                    </table>
                            
                                                </div>

                                            </div>

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
                                                    
                                                    <th>Patient Name</th>
                                                    
                                                    <th>Patient Age</th>
                                                    
                                                    <th>Mobile number</th>
                                                    
                                                    <th>Address </th>
                                            
                                                    <th>Action</th>
                                            
                                                </tr>
                                            
                                            </thead>
                                        
                                            <tbody>
                            
                                                <?php $x=1; while($row = $admin->fetch($results)){  
                                                    
                                                    $patientName = $admin-> getUniquePatientRegistrationForm($row['patient_name']);

                                                ?>
                            
                                                    <tr>
                                                        
                                                        <td><?php echo $x; ?></td>
                                                        
                                                        <td><?php echo $patientName['patient_name']; ?></td>
                                                        
                                                        <td><?php echo $row['patient_age']; ?></td>
                                                        
                                                        <td><?php echo $row['contact_no']; ?></td>
                                                        
                                                        <td><?php echo $row['address']; ?></td>
                                                                                                                
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

    $("#patientName").select2({
        escapeMarkup: function (markup) {
            return markup;
        },
        ajax: {
            url: 'getPatientNameList.php',
            dataType: 'json',
            type: 'post',
            data: function (params) {
            return {
                search: params.term, // search term
                page: params.page
            };
            },
            processResults: function (data, params) {
            params.page = params.page || 1;
            return {
                results: data.items,
                pagination: {
                more: (params.page * 30) < data.total_count
                }
            };
            },
            cache: true
        },
        placeholder: 'Search for Patient Name',
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection
    });

function formatRepo (repo) {
  if (repo.loading) {
    return repo.text;
  }

  var $container = $(
    "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__meta'>" +
        "<div class='select2-result-repository__title'></div>" +
        "<div class='select2-result-repository__description'></div>" +
        "</div>" +
      "</div>" +
    "</div>"
  );
  
  $container.find(".select2-result-repository__title").text(repo.patient_name);
  return $container;
}

function formatRepoSelection (repo) {
  return repo.patient_name || repo.text;
}



//--------------------------------------------------------
    
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

        $(".add-more").on("click", function () {

            var count = $("#casePaperTable > tbody > tr").length;

            $.ajax({

                type: 'POST',

                data: 'count=' + count,

                url: 'get-Ajax-Add-More-Disease.php',

                success: function (services_clone) {

                    $("#casePaperTable > tbody").append(services_clone);

                }

            });

        });

        callToEnhanceValidate();

        $(".add-mores").on("click", function () {

            var count = $("#otherTable > tbody > tr").length;

            $.ajax({

                type: 'POST',

                data: 'count=' + count,

                url: 'get-Ajax-Add-More-Medicine.php',

                success: function (services_clone) {

                    $("#otherTable > tbody").append(services_clone);

                }

            });

            //PrathmesH@11121991

        });

        callToEnhanceValidate();

    });

    function removeitem(e, trName, tdName) {

        if (confirm("Are You Sure TO Remove?")) {

            $(e).parent().parent().remove();

            var counter = 0;

            $('.' + trName).each(function () {

                $(this).find('.' + tdName).each(function () {

                    var oldName = $(this).attr('name');

                    var OrginalName = oldName.substr(0, oldName.indexOf('['));

                    var newName = OrginalName + '[' + counter + ']';

                    $(this).attr('name', newName);

                    console.log(oldName);

                })

                counter++;
            });

        }

    }

    function clearall() {

        $('input').val('');

        $('textarea').val('');

    }

    $('.loadstarter').on('click', function () {

        Notiflix.Loading.Init({});

        Notiflix.Loading.Hourglass();

    });

    function patientDetails(e) {

        let patientId = $(e).val();

        if (patientId != '') {

            $.ajax({

                type: 'POST',

                data: 'patientId=' + patientId,

                url: 'getajaxpatientdetails.php',

                cache: false,

                success: function (res) {

                    if (res.length != 0) {

                        $('#patientAge').val(res.patient_age);

                        $('#sex').val(res.sex);

                        $('#contactNo').val(res.contact_no);

                        $('#address').val(res.address);

                    } else {

                        $('#patientAge').val("");

                        $('#sex').val("");

                        $('#contactNo').val("");

                        $('#address').val("");

                    }
                }
            });

        } else {

            $('#patientAge').val("");

            $('#sex').val("");

            $('#contactNo').val("");

            $('#address').val("");

        }

    }

    </script>

</html>
