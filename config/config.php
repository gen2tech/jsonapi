<?php
/**
* This is the CConfiguration file
*
* @author  Ogunyemi Oludayo
* Infinite Life Inc
* infinitetech7@gmail.com
* 08069685283, 08074288823
*/
ob_start('ob_gzhandler');
//ob_start();
// Ensure we have session
if(session_id() === ""){
    session_start();
}

date_default_timezone_set("Africa/Lagos");

error_reporting(E_ALL);
ini_set('display_errors', 1);


// ========================== USER EDITABLE CONFIGURATION DATA==============================================================
// App Name
defined("APP_NAME") || define("APP_NAME", 'Project');
defined("MULTIPLE_DB") || define("MULTIPLE_DB", true);


define( 'DB_HOST_DEFUALT', '' ); // set database host
define( 'DB_USER_DEFUALT', '' ); // set database user
define( 'DB_PASS_DEFUALT', '' ); // set database password
define( 'DB_NAME_DEFUALT', '' ); // set database name
define( 'DB_PORT_DEFUALT', '' ); // set database port

// ========================== CONFIGURATION DATA==============================================================
// directory separator
defined("DS") || define("DS", DIRECTORY_SEPARATOR);//we are dynamically recognising the seperator (/ or \) slash based on the system type if its windows linux or mac

// path separator
defined("PS") || define("PS", PATH_SEPARATOR);//we are dynamically recognising the path seperator (: or ;) based on the system type if its windows linux or mac

// URL separator
defined("US") || define("US", '/');//we are dynamically recognising the path seperator (: or ;) based on the system type if its windows linux or mac

$baseFolder = str_replace(str_replace(US,DS,$_SERVER['DOCUMENT_ROOT']),'',dirname(__DIR__));


//App Folder name if the project is inside a folder inside htdocs of the server use ('foldername'.US) otherwise use (US)
//$AFN 	=  !empty($baseFolder) ? str_replace(DS,US, ltrim($baseFolder,US) ).US: '';
//define('AFN', ltrim($AFN, US));
$AFN 	=  !empty($baseFolder) ? ltrim(str_replace(DS,US, $baseFolder), US ).US: '';
define('AFN', $AFN);


// ========================== CONFIGURATION DATA==============================================================

// ========================== URL PATH========================================================================
//Defining the website domain url
$_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';  // Set the http or https

// Define Port
$PORT = (($_protocol == 'http' && (int)$_SERVER['SERVER_PORT'] !== 80) || ($_protocol == 'https' && (int)$_SERVER['SERVER_PORT'] !== 443)) ? ":" . $_SERVER['SERVER_PORT'] : '';

defined("URL") || define("URL", $_protocol.'://'.$_SERVER['SERVER_NAME'].$PORT. US); //assign the global site url www.yoursite.com/

// website domain url with Application Folder Path
defined("URL_PATH") || define("URL_PATH", URL . AFN);// www.yoursite.com/app_folder_name
// ========================== URL PATH========================================================================

// ========================== ROOT PATH=======================================================================
// root path
//we are dynamcially getting to root of our application like ../store/
defined("ROOT") || define("ROOT", $_SERVER['DOCUMENT_ROOT'] . DS.str_replace(US, DS,AFN));
// ========================== ROOT PATH=======================================================================


        
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET,POST,PUT,OPTION, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");