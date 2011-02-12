<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.8
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: login.php
//----------------------------------------------------------------------
//  Last update: 12/02/2011
//----------------------------------------------------------------------
error_reporting(0);
//database connection
include ("../include/configconnect.php");
include ("../include/post.php");
$crawlencode = urlencode($crawler);
//get the functions files
$times = 0;
include ("../include/functions.php");
if (isset($crawlthost)) //version >= 150
{
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
} else {
	$connexion = mysql_connect($host, $user, $password) or die("MySQL connection to database problem");
	$selection = mysql_select_db($db) or die("MySQL database selection problem");
}

//clear the cache folder at the first entry on crawltrack to avoid to have it oversized
$dir = dir('../cache/');
while (false !== $entry = $dir->read()) {
	// Skip pointers
	if ($entry == '.' || $entry == '..') {
		continue;
	}
	unlink("../cache/$entry");
}

//clear cache table
$sqlcache = "TRUNCATE TABLE crawlt_cache";
$requetecache = mysql_query($sqlcache, $connexion);

//clear graph table
$sqlcache = "TRUNCATE TABLE crawlt_graph";
$requetecache = mysql_query($sqlcache, $connexion);

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
$requetelogin = mysql_query($sqllogin, $connexion) or die("MySQL query error");
if (isset($crawlthost)) //version >= 150
{
	$sqllogin2 = "SELECT public FROM crawlt_config";
	$requetelogin2 = mysql_query($sqllogin2, $connexion) or die("MySQL query error");
}

//mysql connexion close
mysql_close($connexion);
$validuser = 0;
$userpass2 = md5($userpass);
while ($ligne = mysql_fetch_object($requetelogin)) {
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
	while ($ligne2 = mysql_fetch_object($requetelogin2)) {
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
$validity_time = 600;
$token_clair=$secret_key.$_SERVER['HTTP_HOST'].$_SERVER['HTTP_USER_AGENT'];
$informations=time()."-".$user;
$token = hash('sha256', $token_clair.$informations);
setcookie("session_token", $token, time()+$validity_time,'/');
setcookie("session_informations", $informations, time()+$validity_time,'/');

// we define session variables
$_SESSION['userlogin'] = $userlogin;
$_SESSION['rightsite'] = $rightsite;
$_SESSION['rightadmin'] = $rightadmin;
$_SESSION['rightspamreferer'] = 1;
if (!isset($_SESSION['clearcache'])) {
	$_SESSION['clearcache'] = "0";
}
if ($crawltpublic == 1 && $logitself != 1) {
	header("Location: ../index.php?navig=6&graphpos=$graphpos");
	exit;
} else {
	header("Location: ../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall");
	exit;
}


} else {
	header("Location: ../index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall");
	exit;
}
?>
