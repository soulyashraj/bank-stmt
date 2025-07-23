<?php
    include_once 'include/config.php';
    include_once 'include/admin-functions.php';
    
    $admin    = new AdminFunctions();
    
    if(!$loggedInUserDetailsArr = $admin->sessionExists()){
    
        header("location: index.php");
    
        exit();
    }

    if (isset($_POST['search']) && !empty($_POST['search'])) {
    
        $arr=[];

        $search    = $admin->escape_string($admin->strip_all($_POST['search']));

        $stateDate = $admin->query("SELECT id,patient_name FROM ".PREFIX."patient_info where patient_name LIKE '%".$search."%'");
    
        if($stateDate->num_rows > 0) { 
    
            while ($row = $admin->fetch($stateDate)) {

                array_push($arr,$row);

            }

            $obj["items"] = $arr;
           
            echo json_encode($obj);
            
        }
    
    }

?>
