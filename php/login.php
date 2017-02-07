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

//database connection
include ("../include/configconnect.php");
require_once("../include/jgbdb.php");
// Make POST variables safe
include ("../include/post.php");
$crawlencode = urlencode($crawler);
//get the functions files
$times = 0;
include ("../include/functions.php");

if (isset($crawlthost)) //version >= 150
{
	$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
} else {
	$connexion = db_connect($host, $tuser, $password, $db);
}

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

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = $connexion->query($sqlcache);

//clear graph table
$sqlcache = "TRUNCATE TABLE crawlt_graph";
$requetecache = $connexion->query($sqlcache);

//get values
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
if (isset($_POST['graphpos'])) {
	$graphpos = $_POST['graphpos'];
} else {
	if (isset($_GET['graphpos'])) {
		$graphpos = $_GET['graphpos'];
	} else {
		$graphpos = 0;
	}
}

//mysql query
$sqllogin = "SELECT * FROM crawlt_login";
$requetelogin = $connexion->query($sqllogin) or die("MySQL query error");
if (isset($crawlthost)) //version >= 150
{
	$sqllogin2 = "SELECT public FROM crawlt_config";
	$requetelogin2 = $connexion->query($sqllogin2) or die("MySQL query error");
}

//mysql connexion close
mysqli_close($connexion);

$validuser = 0;
$userpass2 = md5($userpass);

while ($ligne = $requetelogin->fetch_object()) {
	$user = $ligne->crawlt_user;
	$passw = $ligne->crawlt_password;
	$admin = $ligne->admin;
	$sitelog = $ligne->site;
	if ($user == $userlogin && $passw == $userpass2) {
		$rightsite = $sitelog;
		$rightadmin = $admin;
		$validuser = 1;
	}
}
if (isset($crawlthost)) //version >= 150
{
	while ($ligne2 = $requetelogin2->fetch_object()) {
		$crawltpublic = $ligne2->public;
	}
} else {
	$crawltpublic = 0;
}
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
$informations=time()."-".$user;
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
	header("Location: ../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall");
	exit;
}
?>
