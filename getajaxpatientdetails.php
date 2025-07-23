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

    if(isset($_POST['patientId'])){
        $patientId  = $admin->escape_string($admin->strip_all($_POST['patientId']));
        $json=$admin->getUniquePatientRegistrationForm($patientId);
        echo json_encode($json);
    
    } else {
        echo '{"status":"falid"}';
    }
?>