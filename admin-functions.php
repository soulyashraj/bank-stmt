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

		/* ============================= User Deatils STARTS===================================*/

		function getUniqueUserNameById($id,$company_id) {
			
			$id           = $this->escape_string($this->strip_all($id));
			
			$customerName = $this->fetch($this->query("select full_name x FROM ".PREFIX."admin WHERE deleted_time = 0 AND company_id = '".$company_id."' AND id = '".$id."' "))['x'];
						
			return $customerName;
		
		}
		
		/* ============================= User Deatils ENDS===================================*/

		/* ============================= LOCATION NAME STARTS===================================*/

		function getAllLocation()
		{
			
			$query = "select * from ".PREFIX."destination ";
			
			return $this->query($query);

		}

		/* ============================= LOCATION NAME ENDS===================================*/

		/* ============================= CUSTOMER MASTER STARTS===================================*/

		// function getAllVehicleNo($company_id)
		// {

		// 	$query = $this->query("SELECT rr_vehicle_master.vehicle_no FROM rr_vehicle_master LEFT JOIN rr_customer_vehicle_allot ON rr_vehicle_master.vehicle_no = rr_customer_vehicle_allot.vehicle_no WHERE rr_customer_vehicle_allot.vehicle_no IS NULL AND rr_vehicle_master.company_id = ".$company_id." ");
			
		// 	return $query;

		// }

		function getUniqueCustomerNameById($id) {
			
			$id           = $this->escape_string($this->strip_all($id));
			
			$customerName = $this->fetch($this->query("select customer_name x from ".PREFIX."customer_master where id='".$id."' "))['x'];
						
			return $customerName;
		
		}

		function getUniqueCustomerMasterById($id) {
			
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."customer_master where id='".$id."' ";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
		
		}

		function getUniqueCustomerVehicleAllot($id)
		{
			$id    = $this->escape_string($this->strip_all($id));

			$today = date("Y-m-d"); 
			
			//$query = "select * from ".PREFIX."customer_vehicle_allot where customer_id='".$id."' ORDER BY id DESC LIMIT 1 ";
			
			// $query = "select m1.* from ".PREFIX."customer_vehicle_allot m1 LEFT JOIN ".PREFIX."customer_vehicle_allot m2 ON (m1.vehicle_no = m2.vehicle_no AND m1.id < m2.id) WHERE m2.id IS NULL AND m1.customer_id='".$id."' ";
			
			$query = "select * from ".PREFIX."customer_vehicle_allot WHERE id IN (SELECT MAX(id) FROM ".PREFIX."customer_vehicle_allot WHERE customer_id='".$id."' GROUP BY vehicle_no) AND DATE(vehicle_expired_date) >= DATE('".$today."')";
			
			$sql   = $this->query($query);
			
			return $sql;

		}

		function getAllCustomer($company_id) {

			$query = "select * from ".PREFIX."customer_master where company_id='".$company_id."' AND deleted_time = 0 AND active = 1";
			
			return $this->query($query);
		
		}

		function getAllVehicleMaster($company_id) {
			$query = "select * from ".PREFIX."vehicle_master where company_id='".$company_id."' AND deleted_time = 0  AND active='1' ORDER BY vehicle_name ASC" ;
			return $this->query($query);
		}

		
		function getAllVehicleNos($company_id) {
			$today = date("Y-m-d"); 
			$query = "select * from ".PREFIX."vehicle_master where company_id='".$company_id."' AND deleted_time = 0  AND  vehicle_expired_date <= '".$today."' AND active = 1 " ;
			return $this->query($query);
		}

		
		function getAllVehicleBySupllierId($company_id,$id) {
			$today = date("Y-m-d"); 
			$query = "select * from ".PREFIX."vehicle_master where company_id='".$company_id."' AND deleted_time = 0  AND supplier_id='".$id."' AND supplier_vehicle_expired_date <= '".$today."' AND active = 1" ;
			return $this->query($query);
		}

		function vehicleName($vehicle_no)
		{

			$vehicleNo = $this->fetch($this->query("select vehicle_no x from ".PREFIX."vehicle_master where id = '".$vehicle_no."' "))['x'];
			
			return $vehicleNo;

		}
		

		function addCustomerMaster($data,$user_by,$company_id) {
			
			$customer_name             = $this->escape_string($this->strip_all($data['customer_name']));
			
			$customer_phone            = $this->escape_string($this->strip_all($data['customer_phone']));
			
			$customer_pan              = $this->escape_string($this->strip_all($data['customer_pan']));
			
			$customer_email            = $this->escape_string($this->strip_all($data['customer_email']));
			
			$customer_gst              = $this->escape_string($this->strip_all($data['customer_gst']));
			
			$vendor_code               = $this->escape_string($this->strip_all($data['vendor_code']));
			
			$address                   = $this->escape_string($this->strip_all($data['address']));
			
			$contact_person_name       = $this->escape_string($this->strip_all($data['contact_person_name']));
			
			$department                = $this->escape_string($this->strip_all($data['department']));
			
			$contact_person_contact_no = $this->escape_string($this->strip_all($data['contact_person_contact_no']));
			
			$contact_person_email      = $this->escape_string($this->strip_all($data['contact_person_email']));
			
			$bank_name                 = $this->escape_string($this->strip_all($data['bank_name']));
			
			$bank_address              = $this->escape_string($this->strip_all($data['bank_address']));
			
			$ifsc_code                 = $this->escape_string($this->strip_all($data['ifsc_code']));
			
			$account_no                = $this->escape_string($this->strip_all($data['account_no']));
			$active                    = $this->escape_string($this->strip_all($data['active']));
			$query                     =  $this->query("insert into ".PREFIX."customer_master(company_id,customer_name,customer_phone,customer_pan,customer_email,customer_gst,vendor_code,address,contact_person_name,department,contact_person_contact_no,contact_person_email,bank_name,bank_address,ifsc_code,account_no,active,created_by, created_time) values ('".$company_id."','".$customer_name."','".$customer_phone."','".$customer_pan."','".$customer_email."','".$customer_gst."','".$vendor_code."','".$address."','".$contact_person_name."','".$department."','".$contact_person_contact_no."','".$contact_person_email."','".$bank_name."','".$bank_address."','".$ifsc_code."','".$account_no."','".$active."', '".$user_by."', '".CURRENTMILIS."')");
			$last_id                   = $this->last_insert_id();
			return $last_id;

		  }

		function updateCustomerMaster($data,$user_by,$company_id) {

			$id                        = $this->escape_string($this->strip_all($data['id']));
			
			$customer_name             = $this->escape_string($this->strip_all($data['customer_name']));
			
			$customer_phone            = $this->escape_string($this->strip_all($data['customer_phone']));
			
			$customer_pan              = $this->escape_string($this->strip_all($data['customer_pan']));
			
			$customer_email            = $this->escape_string($this->strip_all($data['customer_email']));
			
			$customer_gst              = $this->escape_string($this->strip_all($data['customer_gst']));
			
			$vendor_code               = $this->escape_string($this->strip_all($data['vendor_code']));
			
			$address                   = $this->escape_string($this->strip_all($data['address']));
			
			$contact_person_name       = $this->escape_string($this->strip_all($data['contact_person_name']));
			
			$department                = $this->escape_string($this->strip_all($data['department']));
			
			$contact_person_contact_no = $this->escape_string($this->strip_all($data['contact_person_contact_no']));
			
			$contact_person_email      = $this->escape_string($this->strip_all($data['contact_person_email']));
			
			$bank_name                 = $this->escape_string($this->strip_all($data['bank_name']));
			
			$bank_address              = $this->escape_string($this->strip_all($data['bank_address']));
			
			$ifsc_code                 = $this->escape_string($this->strip_all($data['ifsc_code']));
			
			$account_no                = $this->escape_string($this->strip_all($data['account_no']));
			
			$active                    = $this->escape_string($this->strip_all($data['active']));

			$query                     = "update ".PREFIX."customer_master set customer_name = '".$customer_name."',customer_phone = '".$customer_phone."',customer_pan = '".$customer_pan."',customer_email = '".$customer_email."',customer_gst = '".$customer_gst."',vendor_code = '".$vendor_code."',address = '".$address."',contact_person_name = '".$contact_person_name."',department = '".$department."',contact_person_contact_no = '".$contact_person_contact_no."',contact_person_email = '".$contact_person_email."',bank_name = '".$bank_name."',bank_address = '".$bank_address."',ifsc_code = '".$ifsc_code."',account_no = '".$account_no."',active = '".$active."', updated_by='".$user_by."', updated_time='".CURRENTMILIS."' WHERE id='".$id."' ";
			
			return $this->query($query);

		}

		/* ============================= CUSTOMER MASTER ENDS===================================*/

		
		/* ============================= Vehicle MASTER STARTS===================================*/


		function getUniqueVehicleNameById($id) {
			
			$id           = $this->escape_string($this->strip_all($id));
			
			$vehicleName = $this->fetch($this->query("select vehicle_no x FROM ".PREFIX."vehicle_master WHERE deleted_time = 0 AND id = '".$id."' "))['x'];
						
			return $vehicleName;
		
		}

		function getUniqueVehicleMasterById($id) {

			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."vehicle_master where id='".$id."' ";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);

		}

		function addVehicleMaster($data,$user_by,$company_id) {

			$vehicle_name          = $this->escape_string($this->strip_all($data['vehicle_name']));
			
			$vehicle_no            = $this->escape_string($this->strip_all($data['vehicle_no']));
			
			$own_fix               = $this->escape_string($this->strip_all($data['own_fix']));

			$supplier_id               = $this->escape_string($this->strip_all($data['supplier_id']));
			
			$insurance_expiry_date = $this->escape_string($this->strip_all($data['insurance_expiry_date']));
			
			$fitness_details       = $this->escape_string($this->strip_all($data['fitness_details']));

			$fitness_validity_date = $this->escape_string($this->strip_all($data['fitness_validity_date']));
			
			$chassis_no            = $this->escape_string($this->strip_all($data['chassis_no']));
			
			$veh_type              = $this->escape_string($this->strip_all($data['veh_type']));
			
			$weight_capacity       = $this->escape_string($this->strip_all($data['weight_capacity']));

			$permit_validity_date  = $this->escape_string($this->strip_all($data['permit_validity_date']));

			$vehilce_lenght        = $this->escape_string($this->strip_all($data['vehilce_lenght']));

			$vehilce_width         = $this->escape_string($this->strip_all($data['vehilce_width']));
			
			$vehilce_height        = $this->escape_string($this->strip_all($data['vehilce_height']));

			$active                = $this->escape_string($this->strip_all($data['active']));
			
			$query                 = "insert into ".PREFIX."vehicle_master(supplier_id,company_id, vehicle_name, vehicle_no, own_fix, insurance_expiry_date, fitness_details, fitness_validity_date, chassis_no, veh_type, weight_capacity, permit_validity_date, vehilce_lenght, vehilce_width, vehilce_height, active, created_by, created_time) values ('".$supplier_id."','".$company_id."', '".$vehicle_name."', '".$vehicle_no."', '".$own_fix."', '".$insurance_expiry_date."', '".$fitness_details."', '".$fitness_validity_date."', '".$chassis_no."', '".$veh_type."', '".$weight_capacity."', '".$permit_validity_date."', '".$vehilce_lenght."', '".$vehilce_width."', '".$vehilce_height."', '".$active."', '".$user_by."', '".CURRENTMILIS."')";
			
			return $this->query($query);
		
		}

		function updateVehicleMaster($data,$user_by,$company_id){
				
			$id                    = $this->escape_string($this->strip_all($data['id']));
				
			$vehicle_name          = $this->escape_string($this->strip_all($data['vehicle_name']));
			
			$vehicle_no            = $this->escape_string($this->strip_all($data['vehicle_no']));
			
			$own_fix               = $this->escape_string($this->strip_all($data['own_fix']));

			$supplier_id               = $this->escape_string($this->strip_all($data['supplier_id']));

			$insurance_expiry_date = $this->escape_string($this->strip_all($data['insurance_expiry_date']));
			
			$fitness_details       = $this->escape_string($this->strip_all($data['fitness_details']));
			
			$fitness_validity_date = $this->escape_string($this->strip_all($data['fitness_validity_date']));
			
			$chassis_no            = $this->escape_string($this->strip_all($data['chassis_no']));
			
			$veh_type              = $this->escape_string($this->strip_all($data['veh_type']));
			
			$weight_capacity       = $this->escape_string($this->strip_all($data['weight_capacity']));

			$permit_validity_date  = $this->escape_string($this->strip_all($data['permit_validity_date']));

			$vehilce_lenght        = $this->escape_string($this->strip_all($data['vehilce_lenght']));

			$vehilce_width         = $this->escape_string($this->strip_all($data['vehilce_width']));
			
			$vehilce_height        = $this->escape_string($this->strip_all($data['vehilce_height']));

			$active                = $this->escape_string($this->strip_all($data['active']));
				
			$query                 = "update ".PREFIX."vehicle_master SET vehicle_name='".$vehicle_name."', vehicle_no='".$vehicle_no."', own_fix='".$own_fix."', supplier_id='".$supplier_id."', insurance_expiry_date='".$insurance_expiry_date."', fitness_details='".$fitness_details."', fitness_validity_date='".$fitness_validity_date."', chassis_no='".$chassis_no."', veh_type='".$veh_type."', weight_capacity='".$weight_capacity."', permit_validity_date='".$permit_validity_date."', vehilce_lenght='".$vehilce_lenght."', vehilce_width='".$vehilce_width."', vehilce_height='".$vehilce_height."', updated_by='".$user_by."',updated_time='".CURRENTMILIS."',active='".$active."'  WHERE id='".$id."'";
				
			return $this->query($query);
		
		}

		/* ============================= Vehicle MASTER ENDS===================================*/


		/* ============================= SUPPLIER MASTER STARTS===================================*/


		function getAllSupplierMaster($company_id) {
			$query = "select * from ".PREFIX."supplier_master where company_id = '".$company_id."' AND deleted_time = 0 AND active = 1" ;			
			return $this->query($query);
		}

		function getAllSupplierVehicleNos($company_id) {

			$today = date("Y-m-d"); 

			$query = "select * from ".PREFIX."vehicle_master where company_id = '".$company_id."' AND supplier_vehicle_expired_date < '".$today."' AND own_fix <> 'Own' AND deleted_time = 0 AND active = 1" ;
			
			return $this->query($query);
		
		}


		function addSupplierMaster($data,$user_by,$company_id) {
			
			$supplier_name             = $this->escape_string($this->strip_all($data['supplier_name']));
			$supplier_phone            = $this->escape_string($this->strip_all($data['supplier_phone']));
			$supplier_pan              = $this->escape_string($this->strip_all($data['supplier_pan']));
			$supplier_email            = $this->escape_string($this->strip_all($data['supplier_email']));
			$supplier_gst              = $this->escape_string($this->strip_all($data['supplier_gst']));
			$vendor_code               = $this->escape_string($this->strip_all($data['vendor_code']));
			$address                   = $this->escape_string($this->strip_all($data['address']));
			$contact_person_name       = $this->escape_string($this->strip_all($data['contact_person_name']));
			$department                = $this->escape_string($this->strip_all($data['department']));
			$contact_person_contact_no = $this->escape_string($this->strip_all($data['contact_person_contact_no']));
			$contact_person_email      = $this->escape_string($this->strip_all($data['contact_person_email']));
			$bank_name                 = $this->escape_string($this->strip_all($data['bank_name']));
			$bank_address              = $this->escape_string($this->strip_all($data['bank_address']));
			$ifsc_code                 = $this->escape_string($this->strip_all($data['ifsc_code']));
			$account_no                = $this->escape_string($this->strip_all($data['account_no']));
			$active                    = $this->escape_string($this->strip_all($data['active']));
			$query                     =  $this->query("insert into ".PREFIX."supplier_master(company_id,supplier_name,supplier_phone,supplier_pan,supplier_email,supplier_gst,vendor_code,address,contact_person_name,department,contact_person_contact_no,contact_person_email,bank_name,bank_address,ifsc_code,account_no,active,created_by, created_time) values ('".$company_id."','".$supplier_name."','".$supplier_phone."','".$supplier_pan."','".$supplier_email."','".$supplier_gst."','".$vendor_code."','".$address."','".$contact_person_name."','".$department."','".$contact_person_contact_no."','".$contact_person_email."','".$bank_name."','".$bank_address."','".$ifsc_code."','".$account_no."','".$active."', '".$user_by."', '".CURRENTMILIS."')");
			$last_id                   = $this->last_insert_id();
			return $last_id;
		}


		function getUniqueSupplierMasterById($id) {
			
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."supplier_master where id='".$id."' ";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
		
		}

		function getUniqueSupplierVehicleAllot($id) {

			$id    = $this->escape_string($this->strip_all($id));
			
			$today = date("Y-m-d"); 
			
			$query = "select * from ".PREFIX."supplier_vehicle_allot WHERE id IN (SELECT MAX(id) FROM ".PREFIX."supplier_vehicle_allot WHERE supplier_id='".$id."' GROUP BY vehicle_no) AND DATE(vehicle_expired_date) >= DATE('".$today."')";

			$sql   = $this->query($query);
			
			return $sql;

		}

		
		function updateSupplierMaster($data,$user_by,$company_id) {

			$id                        = $this->escape_string($this->strip_all($data['id']));
			
			$supplier_name             = $this->escape_string($this->strip_all($data['supplier_name']));
			
			$supplier_phone            = $this->escape_string($this->strip_all($data['supplier_phone']));
			
			$supplier_pan              = $this->escape_string($this->strip_all($data['supplier_pan']));
			
			$supplier_email            = $this->escape_string($this->strip_all($data['supplier_email']));
			
			$supplier_gst              = $this->escape_string($this->strip_all($data['supplier_gst']));
			
			$vendor_code               = $this->escape_string($this->strip_all($data['vendor_code']));
			
			$address                   = $this->escape_string($this->strip_all($data['address']));
			
			$contact_person_name       = $this->escape_string($this->strip_all($data['contact_person_name']));
			
			$department                = $this->escape_string($this->strip_all($data['department']));
			
			$contact_person_contact_no = $this->escape_string($this->strip_all($data['contact_person_contact_no']));
			
			$contact_person_email      = $this->escape_string($this->strip_all($data['contact_person_email']));
			
			$bank_name                 = $this->escape_string($this->strip_all($data['bank_name']));
			
			$bank_address              = $this->escape_string($this->strip_all($data['bank_address']));
			
			$ifsc_code                 = $this->escape_string($this->strip_all($data['ifsc_code']));
			
			$account_no                = $this->escape_string($this->strip_all($data['account_no']));
			
			$active                    = $this->escape_string($this->strip_all($data['active']));

			$query                     = "update ".PREFIX."supplier_master set supplier_name = '".$supplier_name."',supplier_phone = '".$supplier_phone."',supplier_pan = '".$supplier_pan."',supplier_email = '".$supplier_email."',supplier_gst = '".$supplier_gst."',vendor_code = '".$vendor_code."',address = '".$address."',contact_person_name = '".$contact_person_name."',department = '".$department."',contact_person_contact_no = '".$contact_person_contact_no."',contact_person_email = '".$contact_person_email."',bank_name = '".$bank_name."',bank_address = '".$bank_address."',ifsc_code = '".$ifsc_code."',account_no = '".$account_no."',active = '".$active."', updated_by='".$user_by."', updated_time='".CURRENTMILIS."' WHERE id='".$id."' ";
			
			return $this->query($query);

		}

		/* ============================= SUPPLIER MASTER ENDS===================================*/


		/* ============================= Customer Vehicle Allot STARTS===================================*/


		function getAllcustomerName($company_id, $customerId)
		{
			
			$query = "select * from ".PREFIX."customer_master where company_id ='".$company_id."' AND id <> '".$customerId."' AND active = 1 AND  deleted_time = 0";

			return $this->query($query);

		}

		function moveVehicleNewCustomer($data,$user_by,$company_id,$oldCustomerId,$vehicleNo)
		{

			$customer_id          = $this->escape_string($this->strip_all($data['customer_id']));
						
			$vehicle_type         = $this->escape_string($this->strip_all($data['vehicle_type']));
			
			$vehicle_hours        = $this->escape_string($this->strip_all($data['vehicle_hours']));
			
			$fix_kilometers       = $this->escape_string($this->strip_all($data['fix_kilometers']));
			
			$fix_rate             = $this->escape_string($this->strip_all($data['fix_rate']));
			
			$extra_km_rate        = $this->escape_string($this->strip_all($data['extra_km_rate']));
			
			$vehicle_allot_date   = $this->escape_string($this->strip_all($data['vehicle_allot_date']));
			
			$vehicle_expired_date = $this->escape_string($this->strip_all($data['vehicle_expired_date']));

			$insertNewCustomerVehicleAllotTable = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot (company_id, customer_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$customer_id."','".$vehicleNo."', '".$vehicle_type."', '".$vehicle_hours."', '".$fix_kilometers."', '".$fix_rate."','".$extra_km_rate."', '".$vehicle_allot_date."','".$vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");
				
			$insertNewCustomerVehicleHistoryTable = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot_history (company_id, customer_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$customer_id."','".$vehicleNo."', '".$vehicle_type."', '".$vehicle_hours."', '".$fix_kilometers."', '".$fix_rate."','".$extra_km_rate."', '".$vehicle_allot_date."','".$vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");

			$updateVehicleMaster = $this->query("update ".PREFIX."vehicle_master set customer_id = '".$customer_id."', vehicle_allot_date = '".$vehicle_allot_date."', vehicle_expired_date = '".$vehicle_expired_date."' WHERE id = '".$vehicleNo."' ");

			$old_customer_vehicle_type               = $this->escape_string($this->strip_all($data['old_customer_vehicle_type']));

			$old_customer_vehicle_hours              = $this->escape_string($this->strip_all($data['old_customer_vehicle_hours']));

			$old_customer_fix_kilometers             = $this->escape_string($this->strip_all($data['old_customer_fix_kilometers']));

			$old_customer_fix_rate                   = $this->escape_string($this->strip_all($data['old_customer_fix_rate']));

			$old_customer_extra_km_rate              = $this->escape_string($this->strip_all($data['old_customer_extra_km_rate']));

			$old_customer_vehicle_allot_date         = $this->escape_string($this->strip_all($data['old_customer_vehicle_allot_date']));

			$old_customer_vehicle_expired_date       = $this->escape_string($this->strip_all($data['old_customer_vehicle_expired_date']));

			$old_cutomer_latest_vehicle_expired_date = date("Y-m-d");

			$insertMovingCustomerHistoryTable  = $this->query("INSERT INTO ".PREFIX."moving_customer_history ( company_id, old_customer_id, old_customer_vehicle_no, old_customer_vehicle_type, old_customer_vehicle_hours, old_customer_fix_kilometers, old_customer_fix_rate, old_customer_extra_km_rate, old_customer_vehicle_allot_date, old_customer_vehicle_expired_date, old_cutomer_latest_vehicle_expired_date, new_customer_id, new_customer_vehicle_no, new_customer_vehicle_type, new_customer_vehicle_hours, new_customer_fix_kilometers, new_customer_fix_rate, new_customer_extra_km_rate, new_customer_vehicle_allot_date, new_customer_vehicle_expired_date, created_by, created_time) values 
			('".$company_id."','".$oldCustomerId."', '".$vehicleNo."','".$old_customer_vehicle_type."','".$old_customer_vehicle_hours."','".$old_customer_fix_kilometers."','".$old_customer_fix_rate."','".$old_customer_extra_km_rate."','".$old_customer_vehicle_allot_date."','".$old_customer_vehicle_expired_date."', '".$old_cutomer_latest_vehicle_expired_date."','".$customer_id."','".$vehicle_no."', '".$vehicle_type."', '".$vehicle_hours."', '".$fix_kilometers."', '".$fix_rate."','".$extra_km_rate."', '".$vehicle_allot_date."','".$vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");

			$insertOldCustomerVehicleAllotTable = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot (company_id, customer_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$oldCustomerId."', '".$vehicleNo."','".$old_customer_vehicle_type."','".$old_customer_vehicle_hours."','".$old_customer_fix_kilometers."','".$old_customer_fix_rate."','".$old_customer_extra_km_rate."','".$old_customer_vehicle_allot_date."','".$old_cutomer_latest_vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");
				
			$insertOldCustomerVehicleHistoryTable = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot_history (company_id, customer_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$oldCustomerId."', '".$vehicleNo."','".$old_customer_vehicle_type."','".$old_customer_vehicle_hours."','".$old_customer_fix_kilometers."','".$old_customer_fix_rate."','".$old_customer_extra_km_rate."','".$old_customer_vehicle_allot_date."','".$old_cutomer_latest_vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");

			return $oldCustomerId;
		}

		function updateVehicleAllotment($data,$user_by,$company_id) {
			
			$customer_id  = $this->escape_string($this->strip_all($data['customer_supplier_name']));

			$select_type  = $this->escape_string($this->strip_all($data['select_type']));

			if(isset($data['vehicle_no'])) {

				$vehicle_no          = $data['vehicle_no'];

				$vehicle_id          = $data['vehicle_id'];
				
				$vehicle_type        = $data['vehicle_type'];
				
				$vehicle_hours       = $data['vehicle_hours'];
				
				$fix_kilometers      = $data['fix_kilometers'];
				
				$fix_rate            = $data['fix_rate'];
				
				$extra_km_rate       = $data['extra_km_rate'];
				
				$vehicle_allot_date  = $data['vehicle_allot_date'];
				
				$vehicle_expired_date = $data['vehicle_expired_date'];

				for ($i = 0; $i < count($data['vehicle_no']); $i++) {
					
					$vehicle_no_value           = $this->escape_string($this->strip_all($vehicle_id[$i]));

					$vehicle_type_value         = $this->escape_string($this->strip_all($vehicle_type[$i]));
					
					$vehicle_hours_value        = $this->escape_string($this->strip_all($vehicle_hours[$i]));
					
					$fix_kilometers_value       = $this->escape_string($this->strip_all($fix_kilometers[$i]));
					
					$fix_rate_value             = $this->escape_string($this->strip_all($fix_rate[$i]));

					$extra_km_rate_value        = $this->escape_string($this->strip_all($extra_km_rate[$i]));
					
					$vehicle_allot_date_value   = $this->escape_string($this->strip_all($vehicle_allot_date[$i]));
					
					$vehicle_expired_date_value = $this->escape_string($this->strip_all($vehicle_expired_date[$i]));

					$resultsss                  = $this->fetch($this->query("select * from ".PREFIX."".$select_type."_vehicle_allot where  ".$select_type."_id = '".$customer_id."' AND vehicle_no = '".$vehicle_no_value."' ORDER BY id DESC LIMIT 1")); 

					$vehicle_hoursss            = $resultsss['vehicle_hours'];

					$fix_kilometersss           = $resultsss['fix_kilometers'];
					
					$fix_ratesss                = $resultsss['fix_rate'];
					
					$extra_km_ratesss           = $resultsss['extra_km_rate'];
					
					$vehicle_allot_datesss      = $resultsss['vehicle_allot_date'];
					
					$vehicle_expired_datesss    = $resultsss['vehicle_expired_date'];

					if ($vehicle_hoursss != $vehicle_hours_value || $fix_kilometersss != $fix_kilometers_value || $fix_ratesss != $fix_rate_value || $extra_km_ratesss != $extra_km_rate_value || $vehicle_allot_datesss != $vehicle_allot_date_value || $vehicle_expired_datesss != $vehicle_expired_date_value) {
					
						$insertVehicleAllotTable = $this->query("INSERT INTO ".PREFIX."".$select_type."_vehicle_allot (company_id, ".$select_type."_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
						('".$company_id."','".$customer_id."','".$vehicle_no_value."', '".$vehicle_type_value."', '".$vehicle_hours_value."', '".$fix_kilometers_value."', '".$fix_rate_value."','".$extra_km_rate_value."', '".$vehicle_allot_date_value."','".$vehicle_expired_date_value."', '".$user_by."', '".CURRENTMILIS."')");
					
						$insertVehicleHistoryTable = $this->query("INSERT INTO ".PREFIX."".$select_type."_vehicle_allot_history (company_id, ".$select_type."_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
						('".$company_id."','".$customer_id."','".$vehicle_no_value."', '".$vehicle_type_value."', '".$vehicle_hours_value."', '".$fix_kilometers_value."', '".$fix_rate_value."','".$extra_km_rate_value."', '".$vehicle_allot_date_value."','".$vehicle_expired_date_value."', '".$user_by."', '".CURRENTMILIS."')");
	
						$updateVehicleMaster = $this->query("update ".PREFIX."vehicle_master set ".$select_type."_id = '".$customer_id."', vehicle_allot_date = '".$vehicle_allot_date_value."', vehicle_expired_date = '".$vehicle_expired_date_value."' WHERE id = '".$vehicle_no_value."' ");
	
					}
			
				}

			}

			return $customer_id;
		}


		function getAllsupplierName($company_id, $supplierId)
		{
			
			$query = "select * from ".PREFIX."supplier_master where company_id ='".$company_id."' AND id <> '".$supplierId."' AND active = 1 AND  deleted_time = 0";

			return $this->query($query);

		}

		function moveVehicleNewsupplier($data,$user_by,$company_id,$oldsupplierId,$vehicleNo)
		{

			$supplier_id          = $this->escape_string($this->strip_all($data['supplier_id']));
						
			$vehicle_type         = $this->escape_string($this->strip_all($data['vehicle_type']));
			
			$vehicle_hours        = $this->escape_string($this->strip_all($data['vehicle_hours']));
			
			$fix_kilometers       = $this->escape_string($this->strip_all($data['fix_kilometers']));
			
			$fix_rate             = $this->escape_string($this->strip_all($data['fix_rate']));
			
			$extra_km_rate        = $this->escape_string($this->strip_all($data['extra_km_rate']));
			
			$vehicle_allot_date   = $this->escape_string($this->strip_all($data['vehicle_allot_date']));
			
			$vehicle_expired_date = $this->escape_string($this->strip_all($data['vehicle_expired_date']));

			$insertNewSupplierVehicleAllotTable = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot (company_id, supplier_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$supplier_id."','".$vehicleNo."', '".$vehicle_type."', '".$vehicle_hours."', '".$fix_kilometers."', '".$fix_rate."','".$extra_km_rate."', '".$vehicle_allot_date."','".$vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");
				
			$insertNewSupplierVehicleHistoryTable = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot_history (company_id, supplier_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$supplier_id."','".$vehicleNo."', '".$vehicle_type."', '".$vehicle_hours."', '".$fix_kilometers."', '".$fix_rate."','".$extra_km_rate."', '".$vehicle_allot_date."','".$vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");

			$updateVehicleMaster = $this->query("update ".PREFIX."vehicle_master set supplier_id = '".$supplier_id."', vehicle_allot_date = '".$vehicle_allot_date."', vehicle_expired_date = '".$vehicle_expired_date."' WHERE id = '".$vehicleNo."' ");

			$old_supplier_vehicle_type               = $this->escape_string($this->strip_all($data['old_supplier_vehicle_type']));

			$old_supplier_vehicle_hours              = $this->escape_string($this->strip_all($data['old_supplier_vehicle_hours']));

			$old_supplier_fix_kilometers             = $this->escape_string($this->strip_all($data['old_supplier_fix_kilometers']));

			$old_supplier_fix_rate                   = $this->escape_string($this->strip_all($data['old_supplier_fix_rate']));

			$old_supplier_extra_km_rate              = $this->escape_string($this->strip_all($data['old_supplier_extra_km_rate']));

			$old_supplier_vehicle_allot_date         = $this->escape_string($this->strip_all($data['old_supplier_vehicle_allot_date']));

			$old_supplier_vehicle_expired_date       = $this->escape_string($this->strip_all($data['old_supplier_vehicle_expired_date']));

			$old_supplier_latest_vehicle_expired_date = date("Y-m-d");

			$insertMovingSupplierHistoryTable  = $this->query("INSERT INTO ".PREFIX."moving_supplier_history ( company_id, old_supplier_id, old_supplier_vehicle_no, old_supplier_vehicle_type, old_supplier_vehicle_hours, old_supplier_fix_kilometers, old_supplier_fix_rate, old_supplier_extra_km_rate, old_supplier_vehicle_allot_date, old_supplier_vehicle_expired_date, old_supplier_latest_vehicle_expired_date, new_supplier_id, new_supplier_vehicle_no, new_supplier_vehicle_type, new_supplier_vehicle_hours, new_supplier_fix_kilometers, new_supplier_fix_rate, new_supplier_extra_km_rate, new_supplier_vehicle_allot_date, new_supplier_vehicle_expired_date, created_by, created_time) values 
			('".$company_id."','".$oldsupplierId."', '".$vehicleNo."','".$old_supplier_vehicle_type."','".$old_supplier_vehicle_hours."','".$old_supplier_fix_kilometers."','".$old_supplier_fix_rate."','".$old_supplier_extra_km_rate."','".$old_supplier_vehicle_allot_date."','".$old_supplier_vehicle_expired_date."', '".$old_supplier_latest_vehicle_expired_date."','".$supplier_id."','".$vehicle_no."', '".$vehicle_type."', '".$vehicle_hours."', '".$fix_kilometers."', '".$fix_rate."','".$extra_km_rate."', '".$vehicle_allot_date."','".$vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");

			$insertOldSupplierVehicleAllotTable = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot (company_id, supplier_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$oldsupplierId."', '".$vehicleNo."','".$old_supplier_vehicle_type."','".$old_supplier_vehicle_hours."','".$old_supplier_fix_kilometers."','".$old_supplier_fix_rate."','".$old_supplier_extra_km_rate."','".$old_supplier_vehicle_allot_date."','".$old_supplier_latest_vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");
				
			$insertOldSupplierVehicleHistoryTable = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot_history (company_id, supplier_id, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values 
					('".$company_id."','".$oldsupplierId."', '".$vehicleNo."','".$old_supplier_vehicle_type."','".$old_supplier_vehicle_hours."','".$old_supplier_fix_kilometers."','".$old_supplier_fix_rate."','".$old_supplier_extra_km_rate."','".$old_supplier_vehicle_allot_date."','".$old_supplier_latest_vehicle_expired_date."', '".$user_by."', '".CURRENTMILIS."')");

			return $oldsupplierId;
		}

		/* ============================= Customer Vehicle Allot ENDS===================================*/

		/* ============================= Login Master STARTS===================================*/

		function getUniqueloginMasterById($id)
		{
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."admin where id='".$id."' ";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
		
		}

		function addloginMaster($data,$user_by,$company_id)
		{
			
			$full_name         = $this->escape_string($this->strip_all($data['full_name']));

			$username          = $this->escape_string($this->strip_all($data['username']));

			$email             = $this->escape_string($this->strip_all($data['email']));

			$mobile            = $this->escape_string($this->strip_all($data['mobile']));

			$password          = $this->escape_string($this->strip_all($data['password']));
			
			$password          = password_hash($password, PASSWORD_DEFAULT);	

			$role              = $this->escape_string($this->strip_all($data['role']));

			$active            = $this->escape_string($this->strip_all($data['active']));

			if (isset($data['customer_master'])) {
				
				$customer_master = 1;
			
			} else {
				
				$customer_master = 0;
			
			}

			if (isset($data['vehicle_master'])) {
			
				$vehicle_master = 1;
			
			} else {
			
				$vehicle_master = 0;
			
			}

			if (isset($data['vehicle_allotment'])) {

				$vehicle_allotment = 1;

			} else {

				$vehicle_allotment = 0;

			}

			if (isset($data['supplier_master'])) {

				$supplier_master = 1;

			} else {

				$supplier_master = 0;

			}

			if (isset($data['login_master'])) {
			
				$login_master = 1;
			
			} else {
			
				$login_master = 0;
			
			}

			if (isset($data['operation'])) {
			
				$operation = 1;
			
			} else {
			
				$operation = 0;
			
			}

			if (isset($data['account'])) {
			
				$account = 1;
			
			} else {
			
				$account = 0;
			
			}

			$checkEmail        = $this->fetch($this->query("select email x from ".PREFIX."admin where company_id='".$company_id."' AND email = '".$email."'  "))['x'];

			if ($checkEmail) {

				return header("location:login-master.php?erroremail");

			} else {
			
				$query = "insert into ".PREFIX."admin( company_id, full_name, username, email, mobile, password, role, created, active, customer_master, vehicle_master, vehicle_allotment, supplier_master, login_master,operation,account, created_by, created_time) values  ('".$company_id."', '".$full_name."', '".$username."', '".$email."', '".$mobile."', '".$password."', '".$role."','".$user_by."', '".$active."', '".$customer_master."', '".$vehicle_master."', '".$vehicle_allotment."', '".$supplier_master."', '".$login_master."','".$operation."','".$account."', '".$user_by."', '".CURRENTMILIS."')";
				
				return $this->query($query);

			}

		}

		function updateloginMaster($data,$user_by,$company_id)
		{
			
			$id                = $this->escape_string($this->strip_all($data['id']));

			$full_name         = $this->escape_string($this->strip_all($data['full_name']));

			$username          = $this->escape_string($this->strip_all($data['username']));

			$email             = $this->escape_string($this->strip_all($data['email']));

			$mobile            = $this->escape_string($this->strip_all($data['mobile']));

			$password          = $this->escape_string($this->strip_all($data['password']));
			
			if ($password == '') {

				$password      = $this->fetch($this->query("select password x from ".PREFIX."admin where company_id='".$company_id."' AND id = '".$id."'  "))['x'];

			} else {

				$password      = password_hash($password, PASSWORD_DEFAULT);

			}

			$role              = $this->escape_string($this->strip_all($data['role']));

			$active            = $this->escape_string($this->strip_all($data['active']));

			if (isset($data['customer_master'])) {
				
				$customer_master = 1;
			
			} else {
				
				$customer_master = 0;
			
			}

			if (isset($data['vehicle_master'])) {
			
				$vehicle_master = 1;
			
			} else {
			
				$vehicle_master = 0;
			
			}

			if (isset($data['vehicle_allotment'])) {

				$vehicle_allotment = 1;

			} else {

				$vehicle_allotment = 0;

			}

			if (isset($data['supplier_master'])) {

				$supplier_master = 1;

			} else {

				$supplier_master = 0;

			}

			if (isset($data['login_master'])) {
			
				$login_master = 1;
			
			} else {
			
				$login_master = 0;
			
			}

			if (isset($data['operation'])) {
			
				$operation = 1;
			
			} else {
			
				$operation = 0;
			
			}

			if (isset($data['account'])) {
			
				$account = 1;
			
			} else {
			
				$account = 0;
			
			}

			$created = date("Y-m-d h:i:s", (CURRENTMILIS/1000) );

			$query   = "update  ".PREFIX."admin set full_name = '".$full_name."', username = '".$username."', email = '".$email."', mobile = '".$mobile."', password = '".$password."', role = '".$role."', created = '".$created."', active = '".$active."', customer_master = '".$customer_master."', vehicle_master = '".$vehicle_master."', vehicle_allotment = '".$vehicle_allotment."', supplier_master = '".$supplier_master."', login_master = '".$login_master."' , operation='".$operation."',account='".$account."', created_by = '".$user_by."', created_time = '".CURRENTMILIS."' WHERE  company_id = '".$company_id."' AND id = '".$id."' ";
				
			return $this->query($query);


		}
		/* ============================= Login Master ENDS===================================*/

		/* ============================= Vehicle Out Start===================================*/


		function addVehicleOut($data,$user_by,$company_id)
		{

			$vehicle_out_date     = $this->escape_string($this->strip_all($data['vehicle_out_date']));

			$vehicle_out_time     = $this->escape_string($this->strip_all($data['vehicle_out_time']));

			$lr_no                = $this->escape_string($this->strip_all($data['lr_no']));

			$type_of_document     = $this->escape_string($this->strip_all($data['type_of_document']));

			$document_no          = $this->escape_string($this->strip_all($data['document_no']));
			
			$good_description     = $this->escape_string($this->strip_all($data['good_description']));

			$customer_id          = $this->escape_string($this->strip_all($data['customer_id']));

			$consignee            = $this->escape_string($this->strip_all($data['consignee']));

			$active               = $this->escape_string($this->strip_all($data['active']));

			$vehicle_no           = $this->escape_string($this->strip_all($data['vehicle_no']));

			$vehicle_type         = $this->escape_string($this->strip_all($data['vehicle_type']));

			$vehicle_hours        = $this->escape_string($this->strip_all($data['vehicle_hours']));
			
			$fix_kilometers       = $this->escape_string($this->strip_all($data['fix_kilometers']));

			$fix_rate             = $this->escape_string($this->strip_all($data['fix_rate']));

			$extra_km_rate        = $this->escape_string($this->strip_all($data['extra_km_rate']));

			$vehicle_allot_date   = $this->escape_string($this->strip_all($data['vehicle_allot_date']));

			$vehicle_expired_date = $this->escape_string($this->strip_all($data['vehicle_expired_date']));

			$empty_vehicle        = $this->escape_string($this->strip_all($data['empty_vehicle']));

			$from_destination     = $this->clean($this->escape_string($this->strip_all($data['from_destination'])));
			
			$to_destination       = $this->clean($this->escape_string($this->strip_all($data['to_destination'])));

			$query                = $this->query("insert into ".PREFIX."vehicle_out(company_id, superviser_id, vehicle_out_date, vehicle_out_time, lr_no, type_of_document, document_no, customer_id, consignee, good_description, vehicle_no, vehicle_type, vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, empty_vehicle, from_destination, to_destination, created_by, created_time, active) VALUES ('".$company_id."','".$user_by."','".$vehicle_out_date."','".$vehicle_out_time."','".$lr_no."','".$type_of_document."','".$document_no."','".$customer_id."','".$consignee."','".$good_description."','".$vehicle_no."','".$vehicle_type."','".$vehicle_hours."','".$fix_kilometers."','".$fix_rate."','".$extra_km_rate."','".$vehicle_allot_date."','".$vehicle_expired_date."','".$empty_vehicle."','".$from_destination."','".$to_destination."','".$user_by."','".CURRENTMILIS."','".$active."')");
			
			$updatestatus         = $this->query("UPDATE ".PREFIX."vehicle_master SET status='OUT' WHERE id='".$vehicle_no."' ");

			$destinationName      = $this->fetch($this->query("select destination_name x from ".PREFIX."destination WHERE destination_name = '".$from_destination."' "))['x'];
			
			$destinationName1     = $this->fetch($this->query("select destination_name x from ".PREFIX."destination WHERE destination_name ='".$to_destination."' "))['x'];

			if ($destinationName != $from_destination) {
				
				$insertDestination = $this->query("insert into ".PREFIX."destination (destination_name) VALUES ('".$from_destination."')");

			}
			 
			if($destinationName1 != $to_destination) {

				$insertDestination = $this->query("insert into ".PREFIX."destination (destination_name) VALUES ('".$to_destination."')");

			}

			return $query;

		}


		function getUniqueVehicleOutBycustomerIdAndLrDate($company_id,$customer_id,$lr_from,$lr_to) {

			$company_id    = $this->escape_string($this->strip_all($company_id));
			$customer_id    = $this->escape_string($this->strip_all($customer_id));
			$lr_from    = $this->escape_string($this->strip_all($lr_from));
			$lr_to    = $this->escape_string($this->strip_all($lr_to));
			
			$query = "select * from ".PREFIX."vehicle_out where (vehicle_out_date BETWEEN '".$lr_from."' AND '".$lr_to."') AND confirm_time<>0 AND company_id='".$company_id."' AND customer_id='".$customer_id."' AND invoice_id=0";

			return $this->query($query);
		}

		function getUniqueVehicleOutBycustomerIdAndLrDate1($company_id,$customer_id,$lr_from,$lr_to) {
			$company_id    = $this->escape_string($this->strip_all($company_id));
			$customer_id    = $this->escape_string($this->strip_all($customer_id));
			$lr_from    = $this->escape_string($this->strip_all($lr_from));
			$lr_to    = $this->escape_string($this->strip_all($lr_to));
			$query = "select * from ".PREFIX."vehicle_out where (vehicle_out_date BETWEEN '".$lr_from."' AND '".$lr_to."') AND confirm_time<>0 AND company_id='".$company_id."' AND customer_id='".$customer_id."' AND invoice_id=0 GROUP BY vehicle_no";
			return $this->query($query);
		}

		function getUniqueVehicleOutById($id) {

			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."vehicle_out where id='".$id."' ";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);

		}

		function updateVehicleOut($data,$user_by,$company_id)
		{

			$id                   = $this->escape_string($this->strip_all($data['id']));

			$vehicle_out_date     = $this->escape_string($this->strip_all($data['vehicle_out_date']));

			$vehicle_out_time     = $this->escape_string($this->strip_all($data['vehicle_out_time']));

			$lr_no                = $this->escape_string($this->strip_all($data['lr_no']));

			$type_of_document     = $this->escape_string($this->strip_all($data['type_of_document']));

			$document_no          = $this->escape_string($this->strip_all($data['document_no']));
			
			$good_description     = $this->escape_string($this->strip_all($data['good_description']));

			$customer_id          = $this->escape_string($this->strip_all($data['customer_id']));

			$consignee            = $this->escape_string($this->strip_all($data['consignee']));

			$active               = $this->escape_string($this->strip_all($data['active']));

			$vehicle_no           = $this->escape_string($this->strip_all($data['vehicle_no']));

			$vehicle_type         = $this->escape_string($this->strip_all($data['vehicle_type']));

			$vehicle_hours        = $this->escape_string($this->strip_all($data['vehicle_hours']));
			
			$fix_kilometers       = $this->escape_string($this->strip_all($data['fix_kilometers']));

			$fix_rate             = $this->escape_string($this->strip_all($data['fix_rate']));

			$extra_km_rate        = $this->escape_string($this->strip_all($data['extra_km_rate']));

			$vehicle_allot_date   = $this->escape_string($this->strip_all($data['vehicle_allot_date']));

			$vehicle_expired_date = $this->escape_string($this->strip_all($data['vehicle_expired_date']));

			$empty_vehicle        = $this->escape_string($this->strip_all($data['empty_vehicle']));

			$from_destination     = $this->clean($this->escape_string($this->strip_all($data['from_destination'])));
			
			$to_destination       = $this->clean($this->escape_string($this->strip_all($data['to_destination'])));

			$selectolddetails 	  = $this->getUniqueVehicleOutById($id);

			$updatestatus         = $this->query("UPDATE ".PREFIX."vehicle_master SET status='IN' WHERE id='".$selectolddetails['vehicle_no']."' ");

			$query                = $this->query("update ".PREFIX."vehicle_out set superviser_id = '".$user_by."', vehicle_out_date = '".$vehicle_out_date."', vehicle_out_time = '".$vehicle_out_time."', lr_no = '".$lr_no."', type_of_document = '".$type_of_document."', document_no = '".$document_no."', customer_id = '".$customer_id."', consignee = '".$consignee."', good_description = '".$good_description."', vehicle_no = '".$vehicle_no."', vehicle_type = '".$vehicle_type."', vehicle_hours = '".$vehicle_hours."', fix_kilometers = '".$fix_kilometers."', fix_rate = '".$fix_rate."', extra_km_rate = '".$extra_km_rate."', vehicle_allot_date = '".$vehicle_allot_date."', vehicle_expired_date = '".$vehicle_expired_date."', empty_vehicle = '".$empty_vehicle."', from_destination = '".$from_destination."',to_destination = '".$to_destination."', updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."', active = '".$active."' WHERE id = '".$id."' AND company_id = '".$company_id."' ");

			$destinationName      = $this->fetch($this->query("select destination_name x from ".PREFIX."destination WHERE destination_name = '".$from_destination."' "))['x'];
			
			$destinationName1     = $this->fetch($this->query("select destination_name x from ".PREFIX."destination WHERE destination_name ='".$to_destination."' "))['x'];

			if ($destinationName != $from_destination) {
				
				$insertDestination = $this->query("insert into ".PREFIX."destination (destination_name) VALUES ('".$from_destination."')");

			}

			if($destinationName1 != $to_destination) {

				$insertDestination = $this->query("insert into ".PREFIX."destination (destination_name) VALUES ('".$to_destination."')");

			}
			
			$updatestatus      = $this->query("UPDATE ".PREFIX."vehicle_master SET status='OUT' WHERE id='".$vehicle_no."' ");


			return $query;
			
		}

		/* ============================= Vehicle Out ENDS===================================*/

		/* ============================= Vehicle Reach At Destination Start===================================*/

		function updateVehicleReachAtDestination($data,$user_by,$company_id)
		{
			
			
			$id               = $this->escape_string($this->strip_all($data['id']));

			$destination_date = $this->escape_string($this->strip_all($data['destination_date']));

			$destination_time = $this->escape_string($this->strip_all($data['destination_time']));

			$unloading_date   = $this->escape_string($this->strip_all($data['unloading_date']));

			$unloading_time   = $this->escape_string($this->strip_all($data['unloading_time']));

			$query            = $this->query("update ".PREFIX."vehicle_out set destination_date = '".$destination_date."', destination_time = '".$destination_time."', unloading_date = '".$unloading_date."', unloading_time = '".$unloading_time."', vehicle_reach_by = '".$user_by."', vehicle_reach_time = '".CURRENTMILIS."' WHERE id = '".$id."' AND company_id = '".$company_id."' ");

			return $query;

		}

		/* ============================= Vehicle Reach At Destination ENDS===================================*/

		/* ============================= Vehicle In Start===================================*/

		function updateVehicleIn($data,$file,$user_by,$company_id)
		{
			
			$id               = $this->escape_string($this->strip_all($data['id']));

			$start_km         = $this->escape_string($this->strip_all($data['start_km']));

			$end_km           = $this->escape_string($this->strip_all($data['end_km']));

			$total_km         = $this->escape_string($this->strip_all($data['total_km']));

			$toll_amount_paid = $this->escape_string($this->strip_all($data['toll_amount_paid']));

			if(isset($file['toll_copy_attachment']['name']) && !empty($file['toll_copy_attachment']['name'])) {
				
				$SaveImage = new SaveImage();
				
				$imgDir    = 'pod/';
				
				if(isset($file['toll_copy_attachment']['name']) && !empty($file['toll_copy_attachment']['name'])){
				
					$file_name = strtolower( pathinfo($file['toll_copy_attachment']['name'], PATHINFO_FILENAME));
				
					$cropData  = $this->strip_all($data['cropData1']);
				
					$photo     = $this ->getCmsHeaderCol('toll_copy_attachment','vehicle_out');

					$fileNames = str_replace('', '-', strtolower( pathinfo($photo, PATHINFO_FILENAME)));
					
					$ext       = pathinfo($photo, PATHINFO_EXTENSION);
					
					unlink($imgDir.$fileNames.'_large.'.$ext);

					unlink($imgDir.$fileNames.'_crop.'.$ext);
				
					$toll_copy_attachment = $SaveImage->uploadCroppedImageFileFromForm($file['toll_copy_attachment'], 1926, $cropData, $imgDir, $file_name.'-'.time().'-1');
				
					$sql                  = "UPDATE ".PREFIX."vehicle_out set toll_copy_attachment='".$toll_copy_attachment."' WHERE id = '".$id."' ";
				
					$this->query($sql);
				
				}
		
			}

			if(isset($file['gate_pass_copy']['name']) && !empty($file['gate_pass_copy']['name'])) {
				
				$SaveImage = new SaveImage();
				
				$imgDir    = 'pod/';
				
				if(isset($file['gate_pass_copy']['name']) && !empty($file['gate_pass_copy']['name'])){
				
					$file_name = strtolower( pathinfo($file['gate_pass_copy']['name'], PATHINFO_FILENAME));
				
					$cropData  = $this->strip_all($data['cropData1']);
				
					$photo_1   = $this -> getCmsHeaderCol('gate_pass_copy','vehicle_out');

					$fileNames = str_replace('', '-', strtolower( pathinfo($photo_1, PATHINFO_FILENAME)));
					
					$ext       = pathinfo($photo_1, PATHINFO_EXTENSION);
					
					unlink($imgDir.$fileNames.'_large.'.$ext);

					unlink($imgDir.$fileNames.'_crop.'.$ext);
				
					$gate_pass_copy = $SaveImage->uploadCroppedImageFileFromForm($file['gate_pass_copy'], 1926, $cropData, $imgDir, $file_name.'-'.time().'-1');
				
					$sql            = "UPDATE ".PREFIX."vehicle_out set gate_pass_copy='".$gate_pass_copy."' WHERE id = '".$id."'";
				
					$this->query($sql);
				
				}
			
			}

			$query  = $this->query("update ".PREFIX."vehicle_out set start_km = '".$start_km."', end_km = '".$end_km."', total_km = '".$total_km."', toll_amount_paid = '".$toll_amount_paid."', vehcile_out_by = '".$user_by."', vehcile_out_time = '".CURRENTMILIS."' WHERE id = '".$id."' AND company_id = '".$company_id."' ");
			
		}

		/* ============================= Vehicle In End===================================*/

		function getUniqueItemMasterById($id) {
			$id = $this->escape_string($this->strip_all($id));
			$query = "select * from ".PREFIX."item_master where id='".$id."' AND deleted_time IS NULL";
			$sql = $this->query($query);
			return $this->fetch($sql);
		}
		
		function getActiveItemUnitMaster() {
			$query = "select stock_unit from ".PREFIX."item_master where active='1' AND deleted_time IS NULL";
			return $this->query($query);
		}
		
		function getActiveItemDetails() {
			$query = "select * from ".PREFIX."item_master where active='1' AND deleted_time IS NULL";
			return $this->query($query);
		}
		
		function addItemMaster($data,$user_by) {
			$group_id = $this->escape_string($this->strip_all($data['group_id']));
			$item_name = $this->escape_string($this->strip_all($data['item_name']));
			$item_short_name = $this->escape_string($this->strip_all($data['item_short_name']));
			$stock_unit = $this->escape_string($this->strip_all($data['stock_unit']));
			$gst_percentage = $this->escape_string($this->strip_all($data['gst_percentage']));
			$reorder_quantitiy = $this->escape_string($this->strip_all($data['reorder_quantitiy']));
			$hsn_code = $this->escape_string($this->strip_all($data['hsn_code']));
			$active = $this->escape_string($this->strip_all($data['active']));
			
			$query = "insert into ".PREFIX."item_master(group_id, item_name, item_short_name, stock_unit, gst_percentage, reorder_quantitiy, hsn_code, active, created_by, created_time) values ('".$group_id."', '".$item_name."', '".$item_short_name."', '".strtoupper($stock_unit)."', '".$gst_percentage."', '".$reorder_quantitiy."', '".$hsn_code."', '".$active."', '".$user_by."', '".CURRENTMILIS."')";
			return $this->query($query);
		}

		function updateItemMaster($data,$user_by){
			$id = $this->escape_string($this->strip_all($data['id']));
			$group_id = $this->escape_string($this->strip_all($data['group_id']));
			$item_name = $this->escape_string($this->strip_all($data['item_name']));
			$item_short_name = $this->escape_string($this->strip_all($data['item_short_name']));
			$stock_unit = $this->escape_string($this->strip_all($data['stock_unit']));
			$gst_percentage = $this->escape_string($this->strip_all($data['gst_percentage']));
			$reorder_quantitiy = $this->escape_string($this->strip_all($data['reorder_quantitiy']));
			$hsn_code = $this->escape_string($this->strip_all($data['hsn_code']));
			$active = $this->escape_string($this->strip_all($data['active']));
 
			$query = "update ".PREFIX."item_master SET group_id='".$group_id."', item_name='".$item_name."', item_short_name='".$item_short_name."', stock_unit='".strtoupper($stock_unit)."', gst_percentage='".$gst_percentage."', reorder_quantitiy='".$reorder_quantitiy."', hsn_code='".$hsn_code."', active='".$active."' WHERE id='".$id."'";
			return $this->query($query);
	}
		/* ============================= ITEM MASTER ENDS===================================*/

		/* ============================= ITEM MASTER STARTS===================================*/
		
		function addCustomerRateMaster($data,$user_by) {
			$customer_id = $this->escape_string($this->strip_all($data['customer_id']));
			$item_id = $this->escape_string($this->strip_all($data['item_id']));
			$rate = $this->escape_string($this->strip_all($data['rate']));
		
			$query = "insert into ".PREFIX."customer_rate_master (customer_id, item_id, rate, created_by, created_time) values ('".$customer_id."', '".$item_id."', '".$rate."', '".$user_by."', '".CURRENTMILIS."')";
			return $this->query($query);
		}

		/* ============================= ITEM MASTER ENDS===================================*/
	

		//Sameer coded Start 

		/* ============================= VEHICLE ALLOTMENT STARTS===================================*/
		
		
		function getvehicleallotmentbycustomerId($company_id,$customer_id,$vehicle_id){
			$currentdate = date("Y-m-d");
			$query = $this->query("SELECT *,(Select own_fix FROM ".PREFIX."vehicle_master WHERE ".PREFIX."vehicle_master.id=".PREFIX."customer_vehicle_allot.vehicle_id) as vehicle_type FROM ".PREFIX."customer_vehicle_allot WHERE company_id='".$company_id."' AND customer_id='".$customer_id."' AND vehicle_id='".$vehicle_id."' AND vehicle_expired_date>='$currentdate'");
			return $this->fetch($query);
		}

		function getvehicleallotment($company_id,$customer_id){
			$currentdate = date("Y-m-d");
			$query = $this->query("SELECT * FROM ".PREFIX."customer_vehicle_allot WHERE company_id='".$company_id."' AND customer_id='".$customer_id."' AND vehicle_expired_date>='$currentdate'");
			return $query;
		}

		function getvehicleallotmentsupplier($company_id,$supplier_id){
			$currentdate = date("Y-m-d");
			$query = $this->query("SELECT * FROM ".PREFIX."supplier_vehicle_allot WHERE company_id='".$company_id."' AND supplier_id='".$supplier_id."' AND vehicle_expired_date>='$currentdate'");
			return $query;
		}

		function addVehicleAllotment($data,$user_by,$company_id){
			$customer_id = $this->escape_string($this->strip_all($data['customer_id']));
			$arr1=array();
			$arr2=array();
			$expiremovedelete = $this->query("SELECT * FROM ".PREFIX."customer_vehicle_allot WHERE customer_id='".$customer_id."' ");
			while($row = $this->fetch($expiremovedelete)){
				array_push($arr1,$row['vehicle_id']);
			}

			if(isset($data['vehicle_id'])) {
				$vehicle_id = $data['vehicle_id'];
				$vehicle_no = $data['vehicle_no'];
				$vehicle_hours = $data['vehicle_hours'];
				$fix_kilometers = $data['fix_kilometers'];
				$fix_rate = $data['fix_rate'];
				$extra_km_rate = $data['extra_km_rate'];
				$vehicle_allot_date = $data['vehicle_allot_date'];
				$vehicle_expired_date = $data['vehicle_expired_date'];
				
				for ($i = 0; $i < count($data['vehicle_id']); $i++) {
					 $vehicle_id_value = $this->escape_string($this->strip_all($vehicle_id[$i])); 
					 array_push($arr2,$vehicle_id_value);
					 $vehicle_no_value = $this->escape_string($this->strip_all($vehicle_no[$i])); 
					 $vehicle_hours_value = $this->escape_string($this->strip_all($vehicle_hours[$i])); 
					$fix_kilometers_value = $this->escape_string($this->strip_all($fix_kilometers[$i]));
					$fix_rate_value = $this->escape_string($this->strip_all($fix_rate[$i]));
					$extra_km_rate_value = $this->escape_string($this->strip_all($extra_km_rate[$i]));
					$vehicle_allot_date_value = $this->escape_string($this->strip_all($vehicle_allot_date[$i]));
					$vehicle_expired_date_value = $this->escape_string($this->strip_all($vehicle_expired_date[$i]));
					$check = $this->fetch($this->query("SELECT id x FROM ".PREFIX."customer_vehicle_allot WHERE vehicle_id='".$vehicle_id_value."' AND vehicle_hours='".$vehicle_hours_value."' AND fix_kilometers='".$fix_kilometers_value."' AND fix_rate='".$fix_rate_value."' AND extra_km_rate='".$extra_km_rate_value."' AND vehicle_allot_date='".$vehicle_allot_date_value."' AND vehicle_expired_date='".$vehicle_expired_date_value."'"))['x'];
					if(!$check){
						$move = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot_history SELECT * FROM ".PREFIX."customer_vehicle_allot WHERE vehicle_id = '".$vehicle_id_value."'");
						$delete = $this->query("DELETE FROM ".PREFIX."customer_vehicle_allot WHERE vehicle_id = '".$vehicle_id_value."'");
						$insertIntoDetailsTable = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot (company_id, customer_id, vehicle_id, vehicle_no,vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values ('".$company_id."', '".$customer_id."','".$vehicle_id_value."','".$vehicle_no_value."', '".$vehicle_hours_value."', '".$fix_kilometers_value."', '".$fix_rate_value."', '".$extra_km_rate_value."', '".$vehicle_allot_date_value."', '".$vehicle_expired_date_value."', '".$user_by."', '".CURRENTMILIS."')");
						$update = $this->query("UPDATE ".PREFIX."vehicle_master SET vehicle_allot_date='".$vehicle_allot_date_value."', vehicle_expired_date='".$vehicle_expired_date_value."' WHERE id = '".$vehicle_id_value."'");
					}
				}
			}
			$arr3=(array_diff($arr1, $arr2));
			$currentdate = date("Y-m-d");
			foreach ($arr3 as $key => $value) {
				$move = $this->query("INSERT INTO ".PREFIX."customer_vehicle_allot_history SELECT * FROM ".PREFIX."customer_vehicle_allot WHERE vehicle_id = '".$value."'");	
				$last_id = $this->last_insert_id();
				$delete = $this->query("DELETE FROM ".PREFIX."customer_vehicle_allot WHERE vehicle_id = '".$value."'");
				$update = $this->query("UPDATE ".PREFIX."vehicle_master SET vehicle_expired_date='".$currentdate."' WHERE id = '".$value."'");
				$update = $this->query("UPDATE ".PREFIX."customer_vehicle_allot_history SET vehicle_expired_date='".$currentdate."' WHERE id = '".$last_id."'");

			}
		}

		function getUniqueTaxInvoiceDetailsById($id) {
			$sql = $this->query("select * from ".PREFIX."tax_invoice where id='".$id."'");
			return $this->fetch($sql);
		}

		function getexpensesSumByInvoiceId($invoiceId) {
			$amount = $this->fetch($this->query("select SUM(amount) x from ".PREFIX."expenses where invoice_id='".$invoiceId."' "))['x'];
			if($amount==''){
				$amount=0;
			}
			return $amount;
		}

		function getPaymentSumByInvoiceId($invoiceId) {
			$amount = $this->fetch($this->query("select SUM(amount) x from ".PREFIX."income where invoice_id='".$invoiceId."' "))['x'];
			if($amount==''){
				$amount=0;
			}
			return $amount;
		}

		function getUniqueTaxInvoiceItemDetailsByTaxInvoiceId($tit_id) {
			$query = "select * from ".PREFIX."tax_invoice_item where invoice_id='".$tit_id."'";
			return $this->query($query);
		}

		function getUniqueTaxInvoiceItemDetailsByTaxInvoiceId1($tit_id) {
			$query = "select vehicle_no from ".PREFIX."tax_invoice_item where invoice_id='".$tit_id."' GROUP BY vehicle_no";
			return $this->query($query);
		}

		//-----------------
		function getUniuqePaymentNo($company_id){
			
			$int_invoice_on=$this->fetch($this->query("select MAX(int_payment_on) x from ".PREFIX."expenses WHERE company_id='".$company_id."' ORDER BY int_payment_on DESC"))['x'];
			
			if(!$int_invoice_on){
					$int_invoice_on='00001';
				}else{
					$int_invoice_on++;
					$int_invoice_on=sprintf('%05s', $int_invoice_on);
				}

				if (date('m') <= 4) {
					$financial_year = (date('y')-1) . '-' . date('y');
				} else {
					$financial_year = date('y') . '-' . (date('y') + 1);
				}
			return 'PAY/'.$financial_year.'/'.$int_invoice_on;
		}

		function getUniuqeIntPaymentNo($company_id){
			$int_invoice_on=$this->fetch($this->query("select MAX(int_payment_on) x from ".PREFIX."expenses WHERE company_id='".$company_id."' ORDER BY int_payment_on DESC"))['x'];
			
				$int_invoice_on++;
				$int_invoice_on=sprintf('%05s', $int_invoice_on);

			return $int_invoice_on;
		}

		//
		function getUniuqeIncomeNo($company_id){
			
			$int_invoice_on=$this->fetch($this->query("select MAX(int_payment_on) x from ".PREFIX."income WHERE company_id='".$company_id."' ORDER BY int_payment_on DESC"))['x'];
			
			if(!$int_invoice_on){
					$int_invoice_on='00001';
				}else{
					$int_invoice_on++;
					$int_invoice_on=sprintf('%05s', $int_invoice_on);
				}

				if (date('m') <= 4) {
					$financial_year = (date('y')-1) . '-' . date('y');
				} else {
					$financial_year = date('y') . '-' . (date('y') + 1);
				}
			return 'REC/'.$financial_year.'/'.$int_invoice_on;
		}

		function getUniuqeIntIncomeNo($company_id){
			$int_invoice_on=$this->fetch($this->query("select MAX(int_payment_on) x from ".PREFIX."income WHERE company_id='".$company_id."' ORDER BY int_payment_on DESC"))['x'];
			
				$int_invoice_on++;
				$int_invoice_on=sprintf('%05s', $int_invoice_on);

			return $int_invoice_on;
		}
		
		//----------------------

		function getUniuqeTaxInvoiceNo($company_id){
			
			$int_invoice_on=$this->fetch($this->query("select MAX(int_invoice_on) x from ".PREFIX."tax_invoice WHERE company_id='".$company_id."' ORDER BY int_invoice_on DESC"))['x'];
			
			if(!$int_invoice_on){
					$int_invoice_on='00001';
				}else{
					$int_invoice_on++;
					$int_invoice_on=sprintf('%05s', $int_invoice_on);
				}

				if (date('m') <= 4) {
					$financial_year = (date('y')-1) . '-' . date('y');
				} else {
					$financial_year = date('y') . '-' . (date('y') + 1);
				}
			return 'INV/'.$financial_year.'/'.$int_invoice_on;
		}

		function getUniuqeTaxIntInvoiceNo($company_id){
			$int_invoice_on=$this->fetch($this->query("select MAX(int_invoice_on) x from ".PREFIX."tax_invoice WHERE company_id='".$company_id."' ORDER BY int_invoice_on DESC"))['x'];
			
				$int_invoice_on++;
				$int_invoice_on=sprintf('%05s', $int_invoice_on);

			return $int_invoice_on;
		}

		function addVehicleAllotmentsupplier($data,$user_by,$company_id){
			$supplier_id = $this->escape_string($this->strip_all($data['supplier_id']));
			$arr1=array();
			$arr2=array();
			$expiremovedelete = $this->query("SELECT * FROM ".PREFIX."supplier_vehicle_allot WHERE supplier_id='".$supplier_id."' ");
			while($row = $this->fetch($expiremovedelete)){
				array_push($arr1,$row['vehicle_id']);
			}
		
			if(isset($data['vehicle_id'])) {
				$vehicle_id = $data['vehicle_id'];
				$vehicle_no = $data['vehicle_no'];
				$vehicle_hours = $data['vehicle_hours'];
				$fix_kilometers = $data['fix_kilometers'];
				$fix_rate = $data['fix_rate'];
				$extra_km_rate = $data['extra_km_rate'];
				$vehicle_allot_date = $data['vehicle_allot_date'];
				$vehicle_expired_date = $data['vehicle_expired_date'];
				
				for ($i = 0; $i < count($data['vehicle_id']); $i++) {
					 $vehicle_id_value = $this->escape_string($this->strip_all($vehicle_id[$i])); 
					 array_push($arr2,$vehicle_id_value);
					 $vehicle_no_value = $this->escape_string($this->strip_all($vehicle_no[$i])); 
					 $vehicle_hours_value = $this->escape_string($this->strip_all($vehicle_hours[$i])); 
					$fix_kilometers_value = $this->escape_string($this->strip_all($fix_kilometers[$i]));
					$fix_rate_value = $this->escape_string($this->strip_all($fix_rate[$i]));
					$extra_km_rate_value = $this->escape_string($this->strip_all($extra_km_rate[$i]));
					$vehicle_allot_date_value = $this->escape_string($this->strip_all($vehicle_allot_date[$i]));
					$vehicle_expired_date_value = $this->escape_string($this->strip_all($vehicle_expired_date[$i]));
					$check = $this->fetch($this->query("SELECT id x FROM ".PREFIX."supplier_vehicle_allot WHERE vehicle_id='".$vehicle_id_value."' AND vehicle_hours='".$vehicle_hours_value."' AND fix_kilometers='".$fix_kilometers_value."' AND fix_rate='".$fix_rate_value."' AND extra_km_rate='".$extra_km_rate_value."' AND vehicle_allot_date='".$vehicle_allot_date_value."' AND vehicle_expired_date='".$vehicle_expired_date_value."'"))['x'];
					if(!$check){
						$move = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot_history SELECT * FROM ".PREFIX."supplier_vehicle_allot WHERE vehicle_id = '".$vehicle_id_value."'");
						$delete = $this->query("DELETE FROM ".PREFIX."supplier_vehicle_allot WHERE vehicle_id = '".$vehicle_id_value."'");
						$insertIntoDetailsTable = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot (company_id, supplier_id, vehicle_id, vehicle_no,vehicle_hours, fix_kilometers, fix_rate, extra_km_rate, vehicle_allot_date, vehicle_expired_date, created_by, created_time) values ('".$company_id."', '".$supplier_id."','".$vehicle_id_value."','".$vehicle_no_value."', '".$vehicle_hours_value."', '".$fix_kilometers_value."', '".$fix_rate_value."', '".$extra_km_rate_value."', '".$vehicle_allot_date_value."', '".$vehicle_expired_date_value."', '".$user_by."', '".CURRENTMILIS."')");
						$update = $this->query("UPDATE ".PREFIX."vehicle_master SET supplier_vehicle_allot_date='".$vehicle_allot_date_value."', supplier_vehicle_expired_date='".$vehicle_expired_date_value."' WHERE id = '".$vehicle_id_value."'");
					}
				}
			}
			$arr3=(array_diff($arr1, $arr2));
			$currentdate = date("Y-m-d");
			foreach ($arr3 as $key => $value) {
				$move = $this->query("INSERT INTO ".PREFIX."supplier_vehicle_allot_history SELECT * FROM ".PREFIX."supplier_vehicle_allot WHERE vehicle_id = '".$value."'");	
				$last_id = $this->last_insert_id();
				$delete = $this->query("DELETE FROM ".PREFIX."supplier_vehicle_allot WHERE vehicle_id = '".$value."'");
				$update = $this->query("UPDATE ".PREFIX."vehicle_master SET supplier_vehicle_expired_date='".$currentdate."' WHERE id = '".$value."'");
				$update = $this->query("UPDATE ".PREFIX."supplier_vehicle_allot_history SET vehicle_expired_date='".$currentdate."' WHERE id = '".$last_id."'");

			}
		}

		/* ============================= VEHICLE ALLOTMENT ENDS===================================*/

// Payment

			function updatePayment($data,$company_id,$user_by){
				$invoice_id =  $this->escape_string($this->strip_all($data['id'])); 
				$payment_no =  $this-> getUniuqePaymentNo($company_id); 
				$int_payment_on =  $this-> getUniuqeIntPaymentNo($company_id); 	
				$payment_date =  $this->escape_string($this->strip_all($data['payment_date'])); 
				$payment_mode =  $this->escape_string($this->strip_all($data['payment_mode'])); 

				
				$transaction_no =  $this->escape_string($this->strip_all($data['transaction_no'])); 
				$reference =  $this->escape_string($this->strip_all($data['reference'])); 
				$credit_to =  $this->escape_string($this->strip_all($data['credit_to'])); 
				$head_id =  $this->escape_string($this->strip_all($data['head_id'])); 			
				$amount =  $this->escape_string($this->strip_all($data['amount'])); 
				$query = $this->query("INSERT into ".PREFIX."expenses (payment_mode,company_id,invoice_id, payment_no, int_payment_on, payment_date, transaction_no, reference, credit_to, head_id, amount,created_by, created_time) values ('".$payment_mode."','".$company_id."','".$invoice_id."', '".$payment_no."', '".$int_payment_on."', '".$payment_date."', '".$transaction_no."', '".$reference."', '".$credit_to."', '".$head_id."', '".$amount."','".$user_by."','".CURRENTMILIS."' )");
				return $query;
			}

			function updateIncome($data,$company_id,$user_by){
				$invoice_id =  $this->escape_string($this->strip_all($data['id'])); 
				$payment_no =  $this-> getUniuqePaymentNo($company_id); 
				$int_payment_on =  $this-> getUniuqeIntPaymentNo($company_id); 	
				$payment_date =  $this->escape_string($this->strip_all($data['payment_date'])); 
				$payment_mode =  $this->escape_string($this->strip_all($data['payment_mode'])); 
				$transaction_no =  $this->escape_string($this->strip_all($data['transaction_no'])); 
				$reference =  $this->escape_string($this->strip_all($data['reference'])); 
				$credit_to =  $this->escape_string($this->strip_all($data['credit_to'])); 
				$head_id =  $this->escape_string($this->strip_all($data['head_id'])); 			
				$amount =  $this->escape_string($this->strip_all($data['amount'])); 
				$query = $this->query("INSERT into ".PREFIX."income (payment_mode,company_id,invoice_id, payment_no, int_payment_on, payment_date, transaction_no, reference, credit_to, head_id, amount,created_by, created_time) values ('".$payment_mode."','".$company_id."','".$invoice_id."', '".$payment_no."', '".$int_payment_on."', '".$payment_date."', '".$transaction_no."', '".$reference."', '".$credit_to."', '".$head_id."', '".$amount."','".$user_by."','".CURRENTMILIS."' )");
				return $query;
			}
		// TAX INVOICE

		function addTaxInvoice($data,$company_id,$user_by){
			$invoice_no =  $this-> getUniuqeTaxInvoiceNo($company_id); 
			$int_invoice_on =  $this-> getUniuqeTaxIntInvoiceNo($company_id); 
			$invoice_date =  $this->escape_string($this->strip_all($data['invoice_date'])); 
			$customer_id =  $this->escape_string($this->strip_all($data['customer_id'])); 
			$lr_from =  $this->escape_string($this->strip_all($data['lr_from'])); 
			$lr_to =  $this->escape_string($this->strip_all($data['lr_to'])); 
			$terms_of_payment =  $this->escape_string($this->strip_all($data['terms_of_payment'])); 
			$total_days =  $this->escape_string($this->strip_all($data['total_days'])); 
			$total_km_sum =  $this->escape_string($this->strip_all($data['total_km_sum'])); 
			$total_extra_km =  $this->escape_string($this->strip_all($data['total_extra_km'])); 
			$total_extra_amt =  $this->escape_string($this->strip_all($data['total_extra_amt'])); 
			$total_freight =  $this->escape_string($this->strip_all($data['total_freight'])); 
			$total_rto =  $this->escape_string($this->strip_all($data['total_rto'])); 
			$total_grand =  $this->escape_string($this->strip_all($data['total_grand']));
			$last_id=0;
			if(isset($data['lr_id'])) {
			$query = $this->query("insert into ".PREFIX."tax_invoice (company_id,invoice_no, int_invoice_on, invoice_date, customer_id, lr_from, lr_to, terms_of_payment, total_days, total_km_sum, total_extra_km, total_extra_amt, total_freight, total_rto, total_grand,created_by, created_time) values ('".$company_id."','".$invoice_no."', '".$int_invoice_on."', '".$invoice_date."', '".$customer_id."', '".$lr_from."', '".$lr_to."', '".$terms_of_payment."', '".$total_days."', '".$total_km_sum."', '".$total_extra_km."', '".$total_extra_amt."', '".$total_freight."', '".$total_rto."', '".$total_grand."','".$user_by."', '".CURRENTMILIS."')");
			$last_id  = $this->last_insert_id();
			}
			if(isset($data['lr_id'])) {
			$lr_no = $data['lr_no'];
			$lr_id = $data['lr_id'];
			$destination = $data['destination'];
			$vehicle_no = $data['vehicle_no'];
			$vehicle_type = $data['vehicle_type'];
			$vehicle_out_date = $data['vehicle_out_date'];
			$actual_delivery_date = $data['actual_delivery_date'];
			$days = $data['days'];
			$start_km = $data['start_km'];
			$end_km = $data['end_km'];
			$total_km = $data['total_km'];
			$extra_km = $data['extra_km'];
			$extra_km_amount = $data['extra_km_amount'];
			$freight = $data['freight'];
			$rto = $data['rto'];
			$total = $data['total'];
			for ($i = 0; $i < count($data['lr_id']); $i++) {
				$lr_no_value = $this->escape_string($this->strip_all($lr_no[$i]));  
				$lr_id_value = $this->escape_string($this->strip_all($lr_id[$i]));  
				$destination_value = $this->escape_string($this->strip_all($destination[$i]));  
				$vehicle_no_value = $this->escape_string($this->strip_all($vehicle_no[$i]));  
				$vehicle_type_value = $this->escape_string($this->strip_all($vehicle_type[$i]));  
				$vehicle_out_date_value = $this->escape_string($this->strip_all($vehicle_out_date[$i]));  
				$actual_delivery_date_value = $this->escape_string($this->strip_all($actual_delivery_date[$i]));  
				$days_value = $this->escape_string($this->strip_all($days[$i]));  
				$start_km_value = $this->escape_string($this->strip_all($start_km[$i]));  
				$end_km_value = $this->escape_string($this->strip_all($end_km[$i]));  
				$total_km_value = $this->escape_string($this->strip_all($total_km[$i]));  
				$extra_km_value = $this->escape_string($this->strip_all($extra_km[$i]));  
				$extra_km_amount_value = $this->escape_string($this->strip_all($extra_km_amount[$i]));  
				$freight_value = $this->escape_string($this->strip_all($freight[$i]));  
				$rto_value = $this->escape_string($this->strip_all($rto[$i]));  
				$total_value = $this->escape_string($this->strip_all($total[$i]));  
				$query = $this->query("insert into ".PREFIX."tax_invoice_item (invoice_id,lr_no, lr_id, destination, vehicle_no, vehicle_type, vehicle_out_date, actual_delivery_date, days, start_km, end_km, total_km, extra_km, extra_km_amount, freight, rto, total) values ('".$last_id."','".$lr_no_value."', '".$lr_id_value."', '".$destination_value."', '".$vehicle_no_value."', '".$vehicle_type_value."', '".$vehicle_out_date_value."', '".$actual_delivery_date_value."', '".$days_value."', '".$start_km_value."', '".$end_km_value."', '".$total_km_value."', '".$extra_km_value."', '".$extra_km_amount_value."', '".$freight_value."', '".$rto_value."', '".$total_value."')");
				$update = $this->query("UPDATE ".PREFIX."vehicle_out SET invoice_id='".$last_id."' WHERE id = '".$lr_id_value."'");

			}
		}
		return $last_id;
	}

	function updateTaxInvoice($data,$company_id,$user_by){
			$last_id = $this->escape_string($this->strip_all($data['id']));
			$invoice_date =  $this->escape_string($this->strip_all($data['invoice_date'])); 
			$customer_id =  $this->escape_string($this->strip_all($data['customer_id'])); 
			$lr_from =  $this->escape_string($this->strip_all($data['lr_from'])); 
			$lr_to =  $this->escape_string($this->strip_all($data['lr_to'])); 
			$terms_of_payment =  $this->escape_string($this->strip_all($data['terms_of_payment'])); 
			$total_days =  $this->escape_string($this->strip_all($data['total_days'])); 
			$total_km_sum =  $this->escape_string($this->strip_all($data['total_km_sum'])); 
			$total_extra_km =  $this->escape_string($this->strip_all($data['total_extra_km'])); 
			$total_extra_amt =  $this->escape_string($this->strip_all($data['total_extra_amt'])); 
			$total_freight =  $this->escape_string($this->strip_all($data['total_freight'])); 
			$total_rto =  $this->escape_string($this->strip_all($data['total_rto'])); 
			$total_grand =  $this->escape_string($this->strip_all($data['total_grand']));


		

			if(isset($data['lr_id'])) {
				$query = $this->query("UPDATE ".PREFIX."tax_invoice SET invoice_date='".$invoice_date."', customer_id='".$customer_id."', lr_from='".$lr_from."', lr_to='".$lr_to."', terms_of_payment='".$terms_of_payment."', total_days='".$total_days."', total_km_sum='".$total_km_sum."', total_extra_km='".$total_extra_km."', total_extra_amt='".$total_extra_amt."', total_freight='".$total_freight."', total_rto='".$total_rto."', total_grand='".$total_grand."' WHERE id='".$last_id."'");
				$delete = $this->query("DELETE FROM ".PREFIX."tax_invoice_item WHERE invoice_id = '".$last_id."'");
				$update = $this->query("UPDATE ".PREFIX."vehicle_out SET invoice_id='0' WHERE invoice_id = '".$last_id."'");

			}
			if(isset($data['lr_id'])) {
			$lr_no = $data['lr_no'];
			$lr_id = $data['lr_id'];
			$destination = $data['destination'];
			$vehicle_no = $data['vehicle_no'];
			$vehicle_type = $data['vehicle_type'];
			$vehicle_out_date = $data['vehicle_out_date'];
			$actual_delivery_date = $data['actual_delivery_date'];
			$days = $data['days'];
			$start_km = $data['start_km'];
			$end_km = $data['end_km'];
			$total_km = $data['total_km'];
			$extra_km = $data['extra_km'];
			$extra_km_amount = $data['extra_km_amount'];
			$freight = $data['freight'];
			$rto = $data['rto'];
			$total = $data['total'];
			for ($i = 0; $i < count($data['lr_id']); $i++) {
				$lr_no_value = $this->escape_string($this->strip_all($lr_no[$i]));  
				$lr_id_value = $this->escape_string($this->strip_all($lr_id[$i]));  
				$destination_value = $this->escape_string($this->strip_all($destination[$i]));  
				$vehicle_no_value = $this->escape_string($this->strip_all($vehicle_no[$i]));  
				$vehicle_type_value = $this->escape_string($this->strip_all($vehicle_type[$i]));  
				$vehicle_out_date_value = $this->escape_string($this->strip_all($vehicle_out_date[$i]));  
				$actual_delivery_date_value = $this->escape_string($this->strip_all($actual_delivery_date[$i]));  
				$days_value = $this->escape_string($this->strip_all($days[$i]));  
				$start_km_value = $this->escape_string($this->strip_all($start_km[$i]));  
				$end_km_value = $this->escape_string($this->strip_all($end_km[$i]));  
				$total_km_value = $this->escape_string($this->strip_all($total_km[$i]));  
				$extra_km_value = $this->escape_string($this->strip_all($extra_km[$i]));  
				$extra_km_amount_value = $this->escape_string($this->strip_all($extra_km_amount[$i]));  
				$freight_value = $this->escape_string($this->strip_all($freight[$i]));  
				$rto_value = $this->escape_string($this->strip_all($rto[$i]));  
				$total_value = $this->escape_string($this->strip_all($total[$i]));  
				$query = $this->query("insert into ".PREFIX."tax_invoice_item (invoice_id,lr_no, lr_id, destination, vehicle_no, vehicle_type, vehicle_out_date, actual_delivery_date, days, start_km, end_km, total_km, extra_km, extra_km_amount, freight, rto, total) values ('".$last_id."','".$lr_no_value."', '".$lr_id_value."', '".$destination_value."', '".$vehicle_no_value."', '".$vehicle_type_value."', '".$vehicle_out_date_value."', '".$actual_delivery_date_value."', '".$days_value."', '".$start_km_value."', '".$end_km_value."', '".$total_km_value."', '".$extra_km_value."', '".$extra_km_amount_value."', '".$freight_value."', '".$rto_value."', '".$total_value."')");
				$update = $this->query("UPDATE ".PREFIX."vehicle_out SET invoice_id='".$last_id."' WHERE id = '".$lr_id_value."'");

			}
		}
		return $last_id;
	}

	function getAllActiveAccountHeads($head_type){
			$query = $this->query("SELECT * FROM ".PREFIX."account_head WHERE active='1' AND head_type='".$head_type."' AND id<>1 AND id<>2 AND id<>3");
	return $query;
}

	function addAccountHeads($data){
			$head_name = $this->escape_string($this->strip_all($data['head_name']));
			$active =  $this->escape_string($this->strip_all($data['active'])); 
			$head_type =  $this->escape_string($this->strip_all($data['head_type'])); 

			$query = $this->query("insert into ".PREFIX."account_head (head_type,head_name,active) values ('".$head_type."','".$head_name."','".$active."')");
		return $query;
	}

	function updateAccountHead($data){
				$id = $this->escape_string($this->strip_all($data['id']));
				$head_name = $this->escape_string($this->strip_all($data['head_name']));
				$active =  $this->escape_string($this->strip_all($data['active'])); 
				$head_type =  $this->escape_string($this->strip_all($data['head_type'])); 
				$query = $this->query("UPDATE ".PREFIX."account_head SET head_type='".$head_type."', head_name='".$head_name."',active='".$active."' WHERE id='".$id."'");
				return $query;	
	}

	function getUniqueHeadById($userId) {
		$userId = $this->escape_string($this->strip_all($userId)) ;
		$query = "select * from ".PREFIX."account_head where id='".$userId."'";
		$sql = $this->query($query);
		return $this->fetch($sql);
	}

		//sameer coded End

		function addPatientRegistrationForm($data,$user_by)
		{
			$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
		
			$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
			
			$sex               = $this->escape_string($this->strip_all($data['sex']));
			
			$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
			
			$address           = $this->escape_string($this->strip_all($data['address']));

			$phone_no          = $this->fetch($this->query("select contact_no x from ".PREFIX."patient_info where contact_no='".$contact_no."' AND deleted_time = 0"))['x'];

			if ($contact_no == $phone_no) {

				return  header("location:patient-Registration-Form.php?erroremail");
				
				exit();

			} else {

				$insertDestination = $this->query("insert into ".PREFIX."patient_info (patient_name, patient_age, sex, contact_no, address, created_by, created_time) VALUES ('".$patient_name."', '".$patient_age."', '".$sex."', '".$contact_no."', '".$address."' , '".$user_by."','".CURRENTMILIS."')");

				header("location:".$pageURL."?registersuccess");
    
      			exit();
				
			}

		}

		function getUniquePatientRegistrationForm($id)
		{
			
			$id    = $this->escape_string($this->strip_all($id));
			
			$query = "select * from ".PREFIX."patient_info where id='".$id."' AND deleted_time = 0";
			
			$sql   = $this->query($query);
			
			return $this->fetch($sql);
			
		}

		function getAllPatient()
		{
						
			$query = "select * from ".PREFIX."patient_info where deleted_time = 0";
			
			$sql   = $this->query($query);
			
			return $sql;
			
		}

		function updatePatientRegistrationForm($data,$user_by)
		{
			$id                = $this->escape_string($this->strip_all($data['id']));

			$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
		
			$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
			
			$sex               = $this->escape_string($this->strip_all($data['sex']));
			
			$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
			
			$address           = $this->escape_string($this->strip_all($data['address']));

			$phone_no          = $this->fetch($this->query("select contact_no x from ".PREFIX."patient_info where contact_no='".$contact_no."' AND id <> '".$id."' AND deleted_time = 0"))['x'];
			
			if ($contact_no == $phone_no) {

				return  header("location:patient-Registration-Form.php?erroremail");
				
				exit();

			} else {

				$updatePatientInfo = $this->query("update ".PREFIX."patient_info set patient_name = '".$patient_name."', patient_age = '".$patient_age."', sex = '".$sex."', contact_no = '".$contact_no."', address = '".$address."', updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."' WHERE id = '".$id."' ");

				header("location:".$pageURL."?updatesuccess");
    
      			exit();
				
			}	

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
		
		$customRadio       = $this->escape_string($this->strip_all($data['customRadio']));
		
		$day               = $this->escape_string($this->strip_all($data['day']));
		
		$tos               = $this->escape_string($this->strip_all($data['tos']));
		
		$nows              = $this->escape_string($this->strip_all($data['nows']));
		
		$other             = $this->escape_string($this->strip_all($data['other']));
		
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
		
		$medicine          = $this->escape_string($this->strip_all($data['medicine']));
		
		$insertDestination = $this->query("insert into ".PREFIX."patient_registration (patient_name, patient_age, sex, contact_no, address, lmp, registration_date, registration_time, customRadio, day, tos, nows, other, temperature, p, bp , sp02, rs, cvs, cns, pa, htn, dm, thyroad, other_description, medicine, created_by, created_time) VALUES ('".$patient_name."', '".$patient_age."', '".$sex."', '".$contact_no."', '".$address."', '".$lmp."', '".$registration_date."', '".$registration_time."', '".$customRadio."', '".$day."', '".$tos   ."', '".$nows."', '".$other."', '".$temperature."', '".$p."', '".$bp ."', '".$sp02."', '".$rs."', '".$cvs."', '".$cns."', '".$pa."', '".$htn."', '".$dm."', '".$thyroad."', '".$other_description."', '".$medicine."', '".$user_by."','".CURRENTMILIS."')");

		return $insertDestination;
	
	}

	function getUniquePatientForm($id)
	{
		
		$id    = $this->escape_string($this->strip_all($id));
		
		$query = "select * from ".PREFIX."patient_registration where id='".$id."' AND deleted_time = 0";
		
		$sql   = $this->query($query);
		
		return $this->fetch($sql);
		
	}

	function updatePatientForm($data,$user_by)
	{
		$id                = $this->escape_string($this->strip_all($data['id']));

		$patient_name      = $this->escape_string($this->strip_all($data['patient_name']));
		
		$patient_age       = $this->escape_string($this->strip_all($data['patient_age']));
		
		$sex               = $this->escape_string($this->strip_all($data['sex']));
		
		$contact_no        = $this->escape_string($this->strip_all($data['contact_no']));
		
		$address           = $this->escape_string($this->strip_all($data['address']));
		
		$lmp               = $this->escape_string($this->strip_all($data['lmp']));
		
		$registration_date = $this->escape_string($this->strip_all($data['registration_date']));
		
		$registration_time = $this->escape_string($this->strip_all($data['registration_time']));
		
		$customRadio       = $this->escape_string($this->strip_all($data['customRadio']));
		
		$day               = $this->escape_string($this->strip_all($data['day']));
		
		$tos               = $this->escape_string($this->strip_all($data['tos']));
		
		$nows              = $this->escape_string($this->strip_all($data['nows']));
		
		$other             = $this->escape_string($this->strip_all($data['other']));
		
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
		
		$medicine          = $this->escape_string($this->strip_all($data['medicine']));

		$updatePatientRegistration = $this->query("update ".PREFIX."patient_registration set patient_name = '".$patient_name."', patient_age = '".$patient_age."', sex = '".$sex."', contact_no = '".$contact_no."', address = '".$address."', lmp = '".$lmp."', registration_date = '".$registration_date."', registration_time = '".$registration_time."', customRadio = '".$customRadio."', day = '".$day."', tos    = '".$tos   ."', nows = '".$nows."', other = '".$other."', temperature = '".$temperature."', p = '".$p."', bp  = '".$bp ."', sp02 = '".$sp02."', rs = '".$rs."', cvs = '".$cvs."', cns = '".$cns."', pa = '".$pa."', htn = '".$htn."', dm = '".$dm."', thyroad = '".$thyroad."', other_description = '".$other_description."', medicine = '".$medicine."', updated_by = '".$user_by."', updated_time = '".CURRENTMILIS."' WHERE id = '".$id."'");

		return $updatePatientRegistration;


	}


	}
?>