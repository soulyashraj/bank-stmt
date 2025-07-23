<?php
	include_once 'config.php';
	include_once 'database.php';
	//include_once '../include/classes/Email.class.php';
	include_once 'include/classes/SaveImage.class1.php';
	include_once 'include/classes/CSRF.class.php';
	include_once "include/classes/Pagination.class.php";
	include_once 'include/classes/HelperFunctions.class.php';

	/*
	 * AdminFunctions
	 * v1.0.0 - updated loginSession(), logoutSession(), adminLogin()
	 * v1.1.0 - integrated FCMNotification.class.php class for handling push notifications
	 */
	class AdminFunctions extends Database {
		private $userType = 'admin';

		/*===================== LOGIN BEGINS =====================*/
		function loginSession($userId, $userFirstName, $userLastName, $userType) {
			$_SESSION[SITE_NAME][$this->userType."UserId"] = $userId;
			$_SESSION[SITE_NAME][$this->userType."UserFirstName"] = $userFirstName;
			$_SESSION[SITE_NAME][$this->userType."UserLastName"] = $userLastName;
			$_SESSION[SITE_NAME][$this->userType."UserType"] = $this->userType;
		}
		function logoutSession() {
			if(isset($_SESSION[SITE_NAME])){
				if(isset($_SESSION[SITE_NAME][$this->userType."UserId"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserId"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserFirstName"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserFirstName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserLastName"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserLastName"]);
				}
				if(isset($_SESSION[SITE_NAME][$this->userType."UserType"])){
					unset($_SESSION[SITE_NAME][$this->userType."UserType"]);
				}
				return true;
			} else {
				return false;
			}
		}
		function adminLogin($data, $successURL, $failURL = "index.php?failed") {

			
			$username = $this->escape_string($this->strip_all($data['username']));
			$password = $this->escape_string($this->strip_all($data['password']));
			$query = "select * from ".PREFIX."admin where email='".$username."' AND active=1";
			$result = $this->query($query);

			if($this->num_rows($result) == 1) { // only one unique user should be present in the system
				$row = $this->fetch($result);
				if(password_verify($password, $row['password'])) {
					$this->loginSession($row['id'], $row['fname'], $row['lname'], $this->userType);
					
					if(isset($_POST['fcmtoken'])){
						$fcmtoken = $this->escape_string($this->strip_all($data['fcmtoken']));
						 $this->query("UPDATE ".PREFIX."admin SET fcmtoken='".$fcmtoken."' WHERE id='".$row['id']."'");
					}

					$this->close_connection();
					header("location: ".$successURL);

					exit;


				} else {
					$this->close_connection();
					header("location: ".$failURL);
					exit;
				}
			} else {
				$this->close_connection();
				header("location: ".$failURL);
				exit;
			}
		}
		function sessionExists(){
			if($this->isUserLoggedIn()){
				return $loggedInUserDetailsArr = $this->getLoggedInUserDetails();
				// return true; // DEPRECATED
			} else {
				return false;
			}
		}
		function isUserLoggedIn(){
			if( isset($_SESSION[SITE_NAME]) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserId']) && 
				isset($_SESSION[SITE_NAME][$this->userType.'UserType']) && 
				!empty($_SESSION[SITE_NAME][$this->userType.'UserId']) &&
				$_SESSION[SITE_NAME][$this->userType.'UserType']==$this->userType){
				return true;
			} else {
				return false;
			}
		}

		function getSystemUserType() {
			return $this->userType;
		}

		function getLoggedInUserDetails(){
			$loggedInID = $this->escape_string($this->strip_all($_SESSION[SITE_NAME][$this->userType.'UserId']));
			$loggedInUserDetailsArr = $this->getUniqueAdminById($loggedInID);
			return $loggedInUserDetailsArr;
		}

		function getUniqueAdminById($userId) {
			$userId = $this->escape_string($this->strip_all($userId));
			$query = "select * from ".PREFIX."admin where id='".$userId."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}

		/** * Function to get details of admin */
		function getFirstAdminDetails(){
			$query = "select fname, lname, email from ".PREFIX."admin where user_role = 'super' limit 0, 1";
			$sql = $this->fetch($this->query($query));
			return $sql;
		}
		/*===================== LOGIN ENDS =====================*/


		
		/*===================== EXTRA FUNCTIONS BEGINS =====================*/
		
		/** * Function to create permalink */
		function getValidatedPermalink($permalink){ // v2.0.0
			$permalink = trim($permalink, '()');
			$replace_keywords = array("-:-", "-:", ":-", " : ", " :", ": ", ":",
				"-@-", "-@", "@-", " @ ", " @", "@ ", "@", 
				"-.-", "-.", ".-", " . ", " .", ". ", ".", 
				"-\\-", "-\\", "\\-", " \\ ", " \\", "\\ ", "\\",
				"-/-", "-/", "/-", " / ", " /", "/ ", "/", 
				"-&-", "-&", "&-", " & ", " &", "& ", "&", 
				"-,-", "-,", ",-", " , ", " ,", ", ", ",", 
				" ",
				"---", "--", " - ", " -", "- ",
				"-#-", "-#", "#-", " # ", " #", "# ", "#",
				"-$-", "-$", "$-", " $ ", " $", "$ ", "$",
				"-%-", "-%", "%-", " % ", " %", "% ", "%",
				"-^-", "-^", "^-", " ^ ", " ^", "^ ", "^",
				"-*-", "-*", "*-", " * ", " *", "* ", "*",
				"-(-", "-(", "(-", " ( ", " (", "( ", "(",
				"-)-", "-)", ")-", " ) ", " )", ") ", ")",
				"-;-", "-;", ";-", " ; ", " ;", "; ", ";",
				"-'-", "-'", "'-", " ' ", " '", "' ", "'",
				"-?-", "-?", "?-", " ? ", " ?", "? ", "?",
				'-"-', '-"', '"-', ' " ', ' "', '" ', '"',
				"-!-", "-!", "!-", " ! ", " !", "! ", "!");
			$escapedPermalink = str_replace($replace_keywords, '-', $permalink); 
			return strtolower($escapedPermalink);
		}

		/** * Function to get value in yes/no */
		function getActiveLabel($isActive){
			if($isActive){
				return 'Yes';
			} else {
				return 'No';
			}
		}

		/** * Function to get image url */
		function getImageDir($imageFor){
			switch($imageFor){
				case "banner":
					return "../img/content/banner/"; // add / at end
					break;
				case "amenities":
					return "../img/content/amenities/"; // add / at end
					break;
				case "payment_plan_ad":
					return "../img/content/payment_plan_ad/"; // add / at end
					break;
				case "home_offer":
					return "../img/content/home_offer/"; // add / at end
					break;
				case "listing_offer":
					return "../img/content/listing_offer/"; // add / at end
					break;
				case "property_photographs":
					return "../img/content/property_photographs/"; // add / at end
					break;
				case "property_floor_plans":
					return "../img/content/property_floor_plans/"; // add / at end
					break;
				case "news_events":
					return "../img/content/news_events/"; // add / at end
					break;
				default:
					return false;
					break;
			}
		}

		/** * Function to get image url */
		function getImageUrl($imageFor, $fileName, $imageSuffix, $dirPrefix = ""){
			$fileDir = $this->getImageDir($imageFor, $dirPrefix);
			if($fileDir === false){ // custom directory not found, error!
				$fileDir = "../img/"; // add / at end
				$defaultImageUrl = $fileDir."default.jpg";
				return $defaultImageUrl;
			} else { // process custom directory
				$defaultImageUrl = $fileDir."default.jpg";
				if(empty($fileName)){
					return $defaultImageUrl;
				} else {
					$image_name = strtolower(pathinfo($fileName, PATHINFO_FILENAME));
					$image_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
					if(!empty($imageSuffix)){
						$imageUrl = $fileDir.$image_name."_".$imageSuffix.".".$image_ext;
					} else {
						$imageUrl = $fileDir.$image_name.".".$image_ext;
					}
					if(file_exists($imageUrl)){
						return $imageUrl;
					} else {
						return $defaultImageUrl;
					}
				}
			}
		}

		/** * Function to delete/unlink image file */
		function unlinkImage($imageFor, $fileName, $imageSuffix, $dirPrefix = ""){
			$fileDir = $this->getImageDir($imageFor, $dirPrefix);
			if($fileDir === false){ // custom directory not found, error!
				return false;
			} else { // process custom directory
				$defaultImageUrl = $fileDir."default.jpg";

				$imagePath = $this->getImageUrl($imageFor, $fileName, $imageSuffix, $dirPrefix);
				if($imagePath != $defaultImageUrl){
					$status = unlink($imagePath);
					return $status;
				} else {
					return false;
				}
			}
		}

		/** * Function to get remaining time/ elapsed time */
		function formatTimeRemainingInText($dateTime, $isComplete = false){
			if($isComplete){
				return "<strong>Complete!</strong>";
			} else if(!empty($dateTime)){
				$timestampDiff = strtotime($dateTime) - time();
				if($timestampDiff <=0 ){ // over due
					$then = new DateTime($dateTime);
					$now = new DateTime();
					$sinceThen = $now->diff($then);

					if($sinceThen->y > 0){
						return '<strong class="text-danger">'.$sinceThen->y." year(s) over due</strong>";
					}
					if($sinceThen->m > 0){
						return '<strong class="text-danger">'.$sinceThen->m." month(s) over due</strong>";
					}
					if($sinceThen->d > 0){
						return '<strong class="text-danger">'.$sinceThen->d." day(s) over due</strong>";
					}
					if($sinceThen->h > 0){
						return '<strong class="text-danger">'.$sinceThen->h." hour(s) over due</strong>";
					}
					if($sinceThen->i > 0){
						return '<strong class="text-danger">'.$sinceThen->i." minutes(s) over due</strong>";
					}
				} else { // time remaining
					$then = new DateTime($dateTime);
					$now = new DateTime();
					$sinceThen = $now->diff($then);

					if($sinceThen->y > 0){
						return $sinceThen->y." year(s) left";
					}
					if($sinceThen->m > 0){
						return $sinceThen->m." month(s) left";
					}
					if($sinceThen->d > 0){
						return $sinceThen->d." day(s) left";
					}
					if($sinceThen->h > 0){
						return '<strong class="text-danger">'.$sinceThen->h."</strong> hour(s) remaining";
					}
					if($sinceThen->i > 0){
						return '<strong class="text-danger">'.$sinceThen->i."</strong> minutes(s) remaining";
					}
				}

			} else {
				return "-";
			}
		}

		/** * Function to format date and time */
		function returnFormatTimeRemainingArray($dateTime, $isComplete = false){
			$resultArray = array();
			$resultArray['year'] = "0";
			$resultArray['month'] = "0";
			$resultArray['day'] = "0";
			$resultArray['hour'] = "0";
			$resultArray['minute'] = "0";
			$resultArray['second'] = "0";

			if($isComplete){
				$resultArray['overDue'] = false; // +
				return $resultArray;
			} else if(!empty($dateTime)){
				$then = new DateTime($dateTime);
				$now = new DateTime();
				$sinceThen = $now->diff($then);
				$resultArray['year'] = $sinceThen->y;
				$resultArray['month'] = $sinceThen->m;
				$resultArray['day'] = $sinceThen->d;
				$resultArray['hour'] = $sinceThen->h;
				$resultArray['minute'] = $sinceThen->i;
				$resultArray['second'] = $sinceThen->s;

				$timestampDiff = strtotime($dateTime) - time();
				if($timestampDiff <=0 ){ // over due
					$resultArray['overDue'] = true; // -
				} else { // time remaining
					$resultArray['overDue'] = false; // +
				}
				return $resultArray;
			} else {
				$resultArray['overDue'] = false; // +
				return $resultArray;
			}
		}

		/** * Function to format date and time */
		function formatDateTime($dateTime, $defaultFormat = "d M, Y h:i a T"){
			if(empty($dateTime)){
				return "-";
			} else {
				return date($defaultFormat, strtotime($dateTime));
			}
		}

		/** * Function to format date */
		function formatDate($dateTime, $defaultFormat = "d M, Y T"){
			if(empty($dateTime)){
				return "-";
			} else {
				return date($defaultFormat, strtotime($dateTime));
			}
		}

		/** * Function to format time */
		function formatTime($dateTime, $defaultFormat = "h:i a T"){
			if(empty($dateTime)){
				return "-";
			} else {
				return date($defaultFormat, strtotime($dateTime));
			}
		}

		/** * Function to limit text of description */
		function limitDescText($content, $charLength){
			if(strlen($content) > $charLength){
				return substr($content, 0, $charLength).'...';
			} else {
				return $content;
			}
		}

		/** * Function to format number in amount */
		function formatAmount($amount){
			$amount = (float) $amount;
			return number_format($amount,  2, '.', ',');
		}

		/** * Function to format number as text () */
		function formatNumberAsText($number){
			$numberLength = strlen($number);

			if($numberLength > 3){
				$number = (float) $number;
				$multiplier = 1;
				$suffix = "";
				switch($numberLength){
					case 4:
					case 5:
					case 6:
						$multiplier = 1000;
						$suffix = "K";
						break;
					case 7:
					case 8:
					case 9:
						$multiplier = 1000000;
						$suffix = "M";
						break;
					case 10:
					case 11:
					case 12:
						$multiplier = 1000000000;
						$suffix = "B";
						break;
				}
				$number = $number / $multiplier;
				$number = number_format($number,  1, '.', '');
				$number = $number.$suffix;
			}
			return $number;
		}

		/** * Function to validate numbers */
		function isNumericValue($value){

			return is_numeric($value);
		}

		/** * Function to validate percentage value */
		function isPercentValue($value){

			return ($value >=0 && $value <= 100);
		}

		/** * Function to check whether user has certain permissions as per given */
		function checkUserPermissions($permission,$loggedInUserDetailsArr) {
			$userPermissionsArray = explode(',',$loggedInUserDetailsArr['permissions']);
			if(!in_array($permission,$userPermissionsArray) and $loggedInUserDetailsArr['user_role']!='super') {
				header("location: index.php");
				exit;
			}
		}

		/** * Function to generate random unique number for particular column of particular table */
		function generate_id($prefix, $randomNo, $tableName, $columnName){
			$chkprofile=$this->query("select ".$columnName." from ".PREFIX.$tableName." where ".$columnName." = '".$prefix.$randomNo."'");
			if($this->num_rows($chkprofile)>0){
				$randomNo = str_shuffle('1234567890123456789012345678901234567890');
				$randomNo = substr($randomNo,0,8);
				$this->generate_id($prefix, $randomNo, $tableName, $columnName);
			}else{
				return  $prefix.$randomNo;
			}
		}

		/** * Function to get title of youtube video by its video id */
		function get_youtube_title($ref) {
	      	$json = file_get_contents('http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=' . $ref . '&format=json'); //get JSON video details
	      	$details = json_decode($json, true); //parse the JSON into an array
	      	return $details['title']; //return the video title
	    }

		/** * Function to get ordinal with number */
	    function ordinal($number) {
		    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
		    if ((($number % 100) >= 11) && (($number%100) <= 13))
		        return $number. 'th';
		    else
		        return $number. $ends[$number % 10];
		}

		function clean($string) {
			$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		 
			return strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
		 }

		/*===================== EXTRA FUNCTIONS ENDS =====================*/


		function getListOfCities(){
			$query = "select distinct districtname from ".PREFIX."pincode order by districtname asc";
			return $this->query($query);
		}
		function getListOfStates(){
			$query = "select distinct statename from ".PREFIX."pincode order by statename asc";
			return $this->query($query);
		}


		/* ============================= ENTITY MODULE STARTS===================================*/

		function getUniqueEntityById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."entity_master where id='".$id."'";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}


		function addEntity($data) {
			$name 			= $this->escape_string($this->strip_all($data['name']));
			$active 		= $this->escape_string($this->strip_all($data['active']));
			$created		= date("Y-m-d H:i:s");

			$query = "insert into ".PREFIX."entity_master(name, active, created) values ('".$name."', '".$active."', '".$created."')";
			return $this->query($query);
		}

		function updateEntity($data){
			$id 				= $this->escape_string($this->strip_all($data['id']));
			$name		= $this->escape_string($this->strip_all($data['name']));
			$active 			= $this->escape_string($this->strip_all($data['active']));

			$query = "update ".PREFIX."entity_master set name = '".$name."' , active = '".$active."' where id='".$id."'";
			return $this->query($query);
		}

		function getCmsHeaderCol($col,$databaseName){
			
			$dataval = $this->fetch($this->query("SELECT ".$col." x FROM ".PREFIX."".$databaseName.""))['x'];
			
			return $dataval;
			
		}

		/* ============================= ENTITY MODULE ENDS===================================*/


		function addPatientRegistrationForm($data,$user_by) {

			$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
		
			$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
			
			$sex               = $this->escape_string($this->strip_all($data['sex']));
			
			$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
			
			$address           = $this->escape_string($this->strip_all($data['address']));
			
			$fees              = $this->escape_string($this->strip_all($data['fees']));
			
			if($fees == ''){
			    
			    $fees = 0;
			    
			} else {
			    $fees = $this->escape_string($this->strip_all($data['fees']));
			}

			//$phone_no          = $this->fetch($this->query("select contact_no x from ".PREFIX."patient_info where contact_no='".$contact_no."' AND deleted_time = 0"))['x'];

			//if ($contact_no == $phone_no) {

				//return  header("location:patient-Registration-Form.php?erroremail");
				

			//} else {
                

				$insertDestination = $this->query("insert into ".PREFIX."patient_info (patient_name, patient_age, sex, contact_no, address, fees, created_by, created_time) VALUES ('".$patient_name."', '".$patient_age."', '".$sex."', '".$contact_no."', '".$address."' , '".$fees."' , '".$user_by."','".CURRENTMILIS."')");

				return $insertDestination;
				
			//}

		}

		function getUniquePatientRegistrationForm($id) {
			
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."patient_info where id='".$id."' AND deleted_time = 0";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
			
		}

		function getAllPatient() {
						
			$query = "select * from ".PREFIX."patient_info where deleted_time = 0";
			
			$sql   = $this->query($query);
			
			return $sql;
			
		}

		function updatePatientRegistrationForm($data,$user_by) {

			$id                = $this->escape_string($this->strip_all($data['id']));

			$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
		
			$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
			
			$sex               = $this->escape_string($this->strip_all($data['sex']));
			
			$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
			
			$address           = $this->escape_string($this->strip_all($data['address']));
			
			$fees              = $this->escape_string($this->strip_all($data['fees']));

			//$phone_no          = $this->fetch($this->query("select contact_no x from ".PREFIX."patient_info where contact_no='".$contact_no."' AND deleted_time = 0 AND id !='".$id."'"))['x'];

			//if ($contact_no == $phone_no) {

				//return  header("location:patient-Registration-Form.php?erroremail");
				
			//} else {

				$updatePatientInfo = $this->query("update ".PREFIX."patient_info set patient_name = '".$patient_name."', patient_age = '".$patient_age."', sex = '".$sex."', contact_no = '".$contact_no."', address = '".$address."', fees = '".$fees."',updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."' WHERE id = '".$id."' ");

				return $updatePatientInfo;
			//}	

		}

		function addPatientForm($data,$user_by) {
			
			$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
			
			$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
			
			$sex               = $this->escape_string($this->strip_all($data['sex']));
			
			$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
			
			$address           = $this->escape_string($this->strip_all($data['address']));
			
			$lmp               = $this->escape_string($this->strip_all($data['lmp']));
			
			$registration_date = $this->escape_string($this->strip_all($data['registration_date']));
			
			$registration_time = $this->escape_string($this->strip_all($data['registration_time']));
			
			$temperature       = $this->escape_string($this->strip_all($data['temperature']));
			
			$p                 = $this->escape_string($this->strip_all($data['p']));
			
			$bp                = $this->escape_string($this->strip_all($data['bp']));
			
			$sp02              = $this->escape_string($this->strip_all($data['sp02']));
			
			$rs                = $this->escape_string($this->strip_all($data['rs']));
			
			$cvs               = $this->escape_string($this->strip_all($data['cvs']));
			
			$cns               = $this->escape_string($this->strip_all($data['cns']));
			
			$pa                = $this->escape_string($this->strip_all($data['pa']));
			
			$htn               = $this->escape_string($this->strip_all($data['htn']));
			
			$dm                = $this->escape_string($this->strip_all($data['dm']));
			
			$thyroad           = $this->escape_string($this->strip_all($data['thyroad']));
			
			$other_description = $this->escape_string($this->strip_all($data['other_description']));
									
			$insertDestination = $this->query("insert into ".PREFIX."patient_registration (patient_name, patient_age, sex, contact_no, address, lmp, registration_date, registration_time, temperature, p, bp , sp02, rs, cvs, cns, pa, htn, dm, thyroad, other_description, created_by, created_time) VALUES ('".$patient_name."', '".$patient_age."', '".$sex."', '".$contact_no."', '".$address."', '".$lmp."', '".$registration_date."', '".$registration_time."', '".$temperature."', '".$p."', '".$bp ."', '".$sp02."', '".$rs."', '".$cvs."', '".$cns."', '".$pa."', '".$htn."', '".$dm."', '".$thyroad."', '".$other_description."', '".$user_by."','".CURRENTMILIS."')");

			$last_id           = $this->last_insert_id();
			
			if(isset($data['disease_name'])) {
			
				$disease_name = $data['disease_name'];
			
				$disease_days = $data['disease_days'];

				for ($i = 0; $i < count($data['disease_name']); $i++) {
			
					$disease_name_value = $this->escape_string($this->strip_all($disease_name[$i]));
			
					$disease_days_value = $this->escape_string($this->strip_all($disease_days[$i]));

					$insertCasePaper = $this->query("INSERT INTO ".PREFIX."patient_info_case_paper (patient_registration_id, patient_id, disease_name, disease_days) values ('".$last_id."','".$patient_name."','".$disease_name_value."', '".$disease_days_value."')");
			
				}
			
			}

			if(isset($data['medicine_name'])) {
			
				$medicine_name          = $data['medicine_name'];
			
				$medicine_taken_process = $data['medicine_taken_process'];

				$medicine_af_bf         = $data['medicine_af_bf'];

				$medicine_days          = $data['medicine_days'];

				for ($i = 0; $i < count($data['medicine_name']); $i++) {
			
					$medicine_name_value          = $this->escape_string($this->strip_all($medicine_name[$i]));
			
					$medicine_taken_process_value = $this->escape_string($this->strip_all($medicine_taken_process[$i]));

					$medicine_af_bf_value         = $this->escape_string($this->strip_all($medicine_af_bf[$i]));

					$medicine_days_value          = $this->escape_string($this->strip_all($medicine_days[$i]));

					$insertMedicineInfo       = $this->query("INSERT INTO ".PREFIX."patient_info_medicine_info (patient_registration_id, patient_id, medicine_name, medicine_taken_process, medicine_af_bf, medicine_days) values ('".$last_id."','".$patient_name."','".$medicine_name_value."', '".$medicine_taken_process_value."', '".$medicine_af_bf_value."', '".$medicine_days_value."')");
			
				}
			
			}

			return $last_id;

		}

		function getUniquePatientForm($id) {
			
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."patient_registration where id='".$id."' AND deleted_time = 0";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
			
		}

		function getUniqueCasePaperById($id){

			$query = $this->query("SELECT * FROM ".PREFIX."patient_info_case_paper where patient_registration_id = '".$id."' ORDER BY id ASC");
			
			return $query;

		}

		function getUniqueMedicineById($id){

			$query = $this->query("SELECT * FROM ".PREFIX."patient_info_medicine_info where patient_registration_id = '".$id."' ORDER BY id ASC");
			
			return $query;

		}

		function updatePatientForm($data,$user_by) {
			
			$id                = $this->escape_string($this->strip_all($data['id']));

			$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
			
			$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
			
			$sex               = $this->escape_string($this->strip_all($data['sex']));
			
			$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
			
			$address           = $this->escape_string($this->strip_all($data['address']));
			
			$lmp               = $this->escape_string($this->strip_all($data['lmp']));
			
			$registration_date = $this->escape_string($this->strip_all($data['registration_date']));
			
			$registration_time = $this->escape_string($this->strip_all($data['registration_time']));
			
			$temperature       = $this->escape_string($this->strip_all($data['temperature']));
			
			$p                 = $this->escape_string($this->strip_all($data['p']));
			
			$bp                = $this->escape_string($this->strip_all($data['bp']));
			
			$sp02              = $this->escape_string($this->strip_all($data['sp02']));
			
			$rs                = $this->escape_string($this->strip_all($data['rs']));
			
			$cvs               = $this->escape_string($this->strip_all($data['cvs']));
			
			$cns               = $this->escape_string($this->strip_all($data['cns']));
			
			$pa                = $this->escape_string($this->strip_all($data['pa']));
			
			$htn               = $this->escape_string($this->strip_all($data['htn']));
			
			$dm                = $this->escape_string($this->strip_all($data['dm']));
			
			$thyroad           = $this->escape_string($this->strip_all($data['thyroad']));
			
			$other_description = $this->escape_string($this->strip_all($data['other_description']));
			
			$updatePatientRegistration = $this->query("update ".PREFIX."patient_registration set patient_name = '".$patient_name."', patient_age = '".$patient_age."', sex = '".$sex."', contact_no = '".$contact_no."', address = '".$address."', lmp = '".$lmp."', registration_date = '".$registration_date."', registration_time = '".$registration_time."', temperature = '".$temperature."', p = '".$p."', bp  = '".$bp ."', sp02 = '".$sp02."', rs = '".$rs."', cvs = '".$cvs."', cns = '".$cns."', pa = '".$pa."', htn = '".$htn."', dm = '".$dm."', thyroad = '".$thyroad."', other_description = '".$other_description."', updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."' WHERE id = '".$id."'");
			
			$deleteQuery               = $this->query("DELETE FROM ".PREFIX."patient_info_case_paper WHERE patient_registration_id = '".$id."' AND patient_id = '".$patient_name."' ");

			if(isset($data['disease_name'])) {
			
				$disease_name = $data['disease_name'];
			
				$disease_days = $data['disease_days'];

				for ($i = 0; $i < count($data['disease_name']); $i++) {
			
					$disease_name_value = $this->escape_string($this->strip_all($disease_name[$i]));
			
					$disease_days_value = $this->escape_string($this->strip_all($disease_days[$i]));

					$insertCasePaper = $this->query("INSERT INTO ".PREFIX."patient_info_case_paper (patient_registration_id, patient_id, disease_name, disease_days) values ('".$id."','".$patient_name."','".$disease_name_value."', '".$disease_days_value."')");
			
				}
			
			}

			$deleteQuery = $this->query("DELETE FROM ".PREFIX."patient_info_medicine_info WHERE patient_registration_id = '".$id."' AND patient_id = '".$patient_name."' ");

			if(isset($data['medicine_name'])) {
			
				$medicine_name          = $data['medicine_name'];
			
				$medicine_taken_process = $data['medicine_taken_process'];

				$medicine_af_bf         = $data['medicine_af_bf'];

				$medicine_days          = $data['medicine_days'];

				for ($i = 0; $i < count($data['medicine_name']); $i++) {
			
					$medicine_name_value          = $this->escape_string($this->strip_all($medicine_name[$i]));
			
					$medicine_taken_process_value = $this->escape_string($this->strip_all($medicine_taken_process[$i]));

					$medicine_af_bf_value         = $this->escape_string($this->strip_all($medicine_af_bf[$i]));

					$medicine_days_value          = $this->escape_string($this->strip_all($medicine_days[$i]));

					$insertMedicineInfo           = $this->query("INSERT INTO ".PREFIX."patient_info_medicine_info (patient_registration_id, patient_id, medicine_name, medicine_taken_process, medicine_af_bf, medicine_days) values ('".$id."','".$patient_name."','".$medicine_name_value."', '".$medicine_taken_process_value."', '".$medicine_af_bf_value."', '".$medicine_days_value."')");
			
				}
			
			}

			return $id;
		}
		
	function addDiseaseMaster($data,$user_by,$tableName) {
		
			if ($tableName=='medicine_master') {

				$medicine_name = $this->escape_string($this->strip_all($data['medicine_name']));
                        
				$insertData = $this->query("INSERT INTO ".PREFIX.$tableName." (medicine_name, created_by, created_time) values ('".$medicine_name."', '".$user_by."', '".CURRENTMILIS."')");

			} else {
			   
				$disease_name = $this->escape_string($this->strip_all($data['disease_name']));

				$insertData = $this->query("INSERT INTO ".PREFIX.$tableName." (disease_name, created_by, created_time) values ('".$disease_name."', '".$user_by."', '".CURRENTMILIS."')");

			}

			return $insertData;
				

		}

		function getUniqueDiseaseMaster($id,$tableName) {
			
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX.$tableName." where id='".$id."' AND deleted_time = 0";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
			
		}

		function updateDiseaseMaster($data,$user_by,$tableName) {

			$id                = $this->escape_string($this->strip_all($data['id']));

			if ($tableName=='medicine_master') {

				$medicine_name = $this->escape_string($this->strip_all($data['medicine_name']));
                        
				$updatePatientInfo = $this->query("update ".PREFIX.$tableName." set medicine_name = '".$medicine_name."', updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."' WHERE id = '".$id."' ");

			} else {
			   
				$disease_name = $this->escape_string($this->strip_all($data['disease_name']));

				$updatePatientInfo = $this->query("update ".PREFIX.$tableName." set disease_name = '".$disease_name."', updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."' WHERE id = '".$id."' ");

			}		

			return $updatePatientInfo;
			

		}


	}
?>