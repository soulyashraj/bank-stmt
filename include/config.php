<?php
	/*
	 * AppConfig 
	 * v1 - this files birthday
	 * v2 - added SMS enable/disable flag
	 * v3 - updated BASE CONFIG, error_reporting based on PROJECTSTATUS
	 * v4 - added staging option
	 * v4.1 - BUGFIX in staging option
	 * v5 - removed SMS enable/disable flag
	 */

	/* DEVELOPMENT CONFIG */
// 		DEFINE('PROJECTSTATUS','DEV');
		//DEFINE('PROJECTSTATUS','STAGING');
		// DEFINE('PROJECTSTATUS','DEMO');
		DEFINE('PROJECTSTATUS','LIVE');
	/* DEVELOPMENT CONFIG */

	/* TIMEZONE CONFIG */
	$timezone = "Asia/Kolkata";
	if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
	/* TIMEZONE CONFIG */

	if(PROJECTSTATUS=="LIVE"){
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);
		DEFINE('BASE_URL','https://sunnytailor.in/ajay/');  // do NOT add / at the end !IMPORTANT!
		DEFINE('PAYMENT_URL','https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction');
		DEFINE('WORKING_KEY','91E2FE92E2FD52D5411445C785CAF091');
		DEFINE('ACCESS_CODE','AVND86GH93AD61DNDA');
		DEFINE('MERCHANT_ID','227638');


	} else if(PROJECTSTATUS == "DEMO"){
		

	} else if(PROJECTSTATUS=="STAGING"){
		error_reporting(E_ALL);
		DEFINE('BASE_URL','http://shareittofriends.com/demo/cabiz');
		DEFINE('PAYMENT_URL','https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction');
		DEFINE('WORKING_KEY','91E2FE92E2FD52D5411445C785CAF091');
		DEFINE('ACCESS_CODE','AVND86GH93AD61DNDA');
		DEFINE('MERCHANT_ID','227638');


	} else { // DEFAULT TO DEV
		error_reporting(E_ALL);
		//error_reporting(0);
		DEFINE('BASE_URL','http://ajay.local');  // do NOT add / at the end !IMPORTANT!
		DEFINE('PAYMENT_URL','https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction');
		DEFINE('WORKING_KEY','91E2FE92E2FD52D5411445C785CAF091');
		DEFINE('ACCESS_CODE','AVND86GH93AD61DNDA');
		DEFINE('MERCHANT_ID','227638');

	}

	/* BASE CONFIG */
	DEFINE('SITE_NAME','CABIZNET');
	DEFINE('NEW_PRODUCTS_LIMIT','15');
	DEFINE('FEATURED_PRODUCTS_LIMIT','15');
	DEFINE('PRODUCTS_LIMIT','15');

	DEFINE('TITLE','CABIZ');
	DEFINE('PREFIX','hp_');
	DEFINE('COPYRIGHT','2020');
	DEFINE('CURRENTMILIS',round(microtime(true) * 1000));
	DEFINE('CURRENTDATETIME',date('Y-m-d H:i:s'));
	// DEFINE('CURRENTMILIS',date("Y-m-d H:i:s"));
	DEFINE('LOGO', BASE_URL.'/images/connect-logo.png');
	DEFINE('COMPLIANCELOGO', BASE_URL.'/images/compliance-logo.png');
?>