<?php

     include_once 'include/config.php';
    
    include_once 'include/admin-functions.php';
    
    $admin = new AdminFunctions();

    if(!$loggedInUserDetailsArr = $admin->sessionExists()){
        
        header("location: index.php");
        
        exit();
    
    }

    header('content-type: application/json; charset=utf-8');

    header("access-control-allow-origin: *");

    if(isset($_FILES['upload_bill']) && !empty($_FILES['upload_bill'])){

        $user_by    = $loggedInUserDetailsArr['id'];

        $tableName = $_POST['database_name'];

        ini_set('error_reporting', E_ALL);
  
        ini_set('display_errors', true);

        include_once 'include/SimpleXLSX.php';

        if ($xlsx = SimpleXLSX::parse($_FILES['upload_bill']['tmp_name'] ) ) {

    		$dim  = $xlsx->dimension();
		
            $cols = $dim[0];
        
            $x    = 0;
                
            foreach ($xlsx->rows() as $k => $r) {
            
                if($x!=0){

                    $disease_name    = $r[0]; 
                
                    if ($tableName=='medicine_master') {
                        
                        $insertData = $admin->query("INSERT INTO ".PREFIX.$tableName." (medicine_name, created_by, created_time) values ('".$disease_name."', '".$user_by."', '".CURRENTMILIS."')");

                    } else {
                       
                        $insertData = $admin->query("INSERT INTO ".PREFIX.$tableName." (disease_name, created_by, created_time) values ('".$disease_name."', '".$user_by."', '".CURRENTMILIS."')");

                    }
                       
                }
                
                $x++;

            }


        }

    if($insertData) {

        echo '{"status":"success"}';

    } else {

        echo '{"status":"falid1"}';

    }


} 

?>