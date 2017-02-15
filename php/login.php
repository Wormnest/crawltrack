<?php
//----------------------------------------------------------------------
//  CrawlTrack
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Updating: Jacob Boerema
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: login.php
//----------------------------------------------------------------------

// TODO: Replace md5 with safer routine for password hashing!!!

// Set debugging to non zero to turn it on.
// DON'T FORGET TO TURN IT OFF AFTER YOU FINISH DEBUGGING OR WHEN COMMITTING CHANGES!
$DEBUG = 0;

if ($DEBUG == 0) {
	// Normal: don't show any errors, warnings, notices.
	error_reporting(0);
} else {
	// DURING DEBUGGING ONLY
	error_reporting(E_ALL);
}

// Convert POST/GET variables to named variables.
include ("../include/post.php");
$crawlencode = urlencode($crawler);

//get the functions files
$times = 0;
// Needed for empty_cache
require_once("../include/functions.php");

//clear the cache folder at the first entry on crawltrack to avoid to have it oversized
empty_cache('../cache/');

//clear the cachecloseperiod folder if there is more than 200 files in it to avoid to have it oversized
$list = glob("../cachecloseperiod/*.gz"); 
if(count($list)>200) {
	$dir = dir('../cachecloseperiod/');
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..' || $entry == 'index.htm') {
			continue;
		}
		unlink("../cachecloseperiod/$entry");
	}
	// Clean up
	$dir->close();
}

//get values
// TODO: Should we add these to post.php
if (isset($_POST['userlogin'])) {
	$userlogin = $_POST['userlogin'];
} else {
	$userlogin = '';
}
if (isset($_POST['userpass'])) {
	$userpass = $_POST['userpass'];
} else {
	$userpass = '';
}

define('IN_CRAWLT', true);
require_once("../include/db.class.php");
require_once("../include/accounts.class.php");

$db = new ctDb(); // Create db connection
$oldversion = $db->oldversion; // true if version < 150
$secret_key = $db->secret_key;

// TODO: Clearing these cache tables should be part of a cache maintenance class.
//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = $db->connexion->query($sqlcache);

//clear graph table
$sqlcache = "TRUNCATE TABLE crawlt_graph";
$requetecache = $db->connexion->query($sqlcache);

$crawltpublic = 0; // Default not public
if (!$oldversion) //version >= 150
{
	$sql = "SELECT public FROM crawlt_config";
	$result = $db->connexion->query($sql) or die("MySQL query error");
	// Since there should be only 1 row of configuration settings we just grab the first row
	$row = $result->fetch_object();
	if ($row) {
		$crawltpublic = $row->public;
	}
}

// Login handling
$pw = new ctAccounts($db);

// Check if we have a valid user and password.
if ($pw->is_valid_login($userlogin, $userpass)) {
	$validuser = 1;
	$rightsite = $pw->rightsite;
	$rightadmin = $pw->rightadmin;
} else {
	$validuser = 0;
}
// Close the database.
$db->close();

if ($validuser == 1) {
	// session start 'crawlt'
	if (!isset($_SESSION['flag'])) {
		session_name('crawlt');
		session_start();
		$_SESSION['flag'] = true;
	}

	//create token
	//Thanks to François Lasselin (http://blog.nalis.fr/index.php?post/2009/09/28/Securisation-stateless-PHP-avec-un-jeton-de-session-%28token%29-protection-CSRF-en-PHP)
	$validity_time = 1800;
	$token_clair=$secret_key.$_SERVER['HTTP_HOST'].$_SERVER['HTTP_USER_AGENT'];
	$informations=time()."-".$userlogin;
	$token = hash('sha256', $token_clair.$informations);
	setcookie("session_token", $token, time()+$validity_time,'/');
	setcookie("session_informations", $informations, time()+$validity_time,'/');

	// we define session variables
	$_SESSION['cookie'] = 1;
	$_SESSION['userlogin'] = $userlogin;
	$_SESSION['rightsite'] = $rightsite;
	$_SESSION['rightadmin'] = $rightadmin;
	$_SESSION['rightspamreferer'] = 1;
	if (!isset($_SESSION['clearcache'])) {
		$_SESSION['clearcache'] = "0";
	}
	if ($crawltpublic == 1 && $logitself != 1) {
		header("Location: ../index.php?navig=6&graphpos=$graphpos&nocookie=1");
		exit;
	} else {
		header("Location: ../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall&nocookie=1");
		exit;
	}

} else {
	// No valid login
	header("Location: ../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall");
	exit;
}
?>
