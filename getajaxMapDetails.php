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

    function getFirstDayAndLastDay($year,$month){

        $currentYear      = $year;
        
        $currentMonth     = $month;

        $currentYearArray = array();

        for ($m=1; $m<=$currentMonth; $m++) {

            $getMonthTimeStamp = mktime(0,0,0,$m, 1, $currentYear);
            
            $month             = date('F', $getMonthTimeStamp);
            
            $monthAndYear      = strtotime($month."-".$currentYear);
            
            $currentYearArray[$month]['MonthStartDate'] = date('Y-m-01', $monthAndYear);

            $currentYearArray[$month]['MonthEndDate'] = date('Y-m-t', $monthAndYear);

        }

        
        return $currentYearArray;

    }
 
 
    $firstAndLastDay          = getFirstDayAndLastDay($_POST['currentyear'],$_POST['currentMonth']);

    $json                     = '';
    
    $count                    = 1;
    
    $monthJson                = '';
    
    $labelarry                = array();
    
    $patientregistrationarry  = array();

    $patienttreatmentarry     = array();

    $botharry                 = array();


    foreach($firstAndLastDay as $username) {

        $monthName        = date('F', mktime(0, 0, 0, $count, 10));

        $fristDate        = (strtotime($username['MonthStartDate'])*1000);
        
        $lastDate         = (strtotime($username['MonthEndDate'])*1000);

        $patientId        = $admin->fetch($admin->query("SELECT COUNT(id) x FROM hp_patient_info WHERE created_time BETWEEN '$fristDate' AND '$lastDate'  "))['x'];

        $patienttreatment = $admin->fetch($admin->query("SELECT COUNT(id) x FROM hp_patient_registration WHERE created_time BETWEEN '$fristDate' AND '$lastDate'  "))['x'];

        array_push($labelarry,$monthName);

        array_push($patientregistrationarry,$patientId);

        array_push($patienttreatmentarry,$patienttreatment);

        $count++;

        //   $items['MonthStartDate'] = $username;

    }
 
    $patientIdCount        = $admin->fetch($admin->query("SELECT COUNT(id) x FROM hp_patient_info "))['x'];

    $patienttreatmentCount = $admin->fetch($admin->query("SELECT COUNT(id) x FROM hp_patient_registration "))['x'];

    array_push($botharry,$patienttreatmentCount,$patientIdCount);


    $strobj = '{'.json_encode($labelarry).'}';

    $result = substr($strobj, 1, -1);

    $strobj1 = '{'.json_encode($patientregistrationarry).'}';

    $result1 = substr($strobj1, 1, -1);

    $strobj2 = '{'.json_encode($patienttreatmentarry).'}';

    $result2 = substr($strobj2, 1, -1);

    $strobj3 = '{'.json_encode($botharry).'}';

    $result3 = substr($strobj3, 1, -1);

    echo '{"status":"success","lable":'.$result.',"patient":'.$result1.',"patienttreatment":'.$result2.',"both":'.$result3.'}';



// print_r($abc);


    // if(isset($_POST['patientId'])){
    //     $patientId  = $admin->escape_string($admin->strip_all($_POST['patientId']));
    //     $json=$admin->getUniquePatientRegistrationForm($patientId);
    //     echo json_encode($json);
    
    // } else {
    //     echo '{"status":"falid"}';
    // }
?>