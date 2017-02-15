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
// file: index.php
//----------------------------------------------------------------------

// make sure PHP version  >= 5.3.7 is used (New password hashing function needs at least 5.3.7)
if (version_compare(PHP_VERSION, '5.3.7', '<')) exit("Sorry, CrawlTrack needs at least PHP version 5.3.0 to run ! You are running version " . PHP_VERSION . " \n");

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

// CrawlTrack version id
$versionid = '341';

//initialize array & data
$listlangcrawlt = array();
$numbquery = 0;

//function to count the number of mysql query
function db_query($sql, $connexion) {
	global $numbquery;
	$numbquery++;
	return $connexion->query($sql);
}

//function to measure the time used for the calculation
function getTime() {
	static $timer = false, $start;
	if ($timer === false) {
		$start = array_sum(explode(' ', microtime()));
		$timer = true;
		return NULL;
	} else {
		$timer = false;
		$end = array_sum(explode(' ', microtime()));
		return round(($end - $start), 3);
	}
}
getTime();

// do not modify
define('IN_CRAWLT', TRUE);

//if already installed get all the config datas
if (file_exists('include/configconnect.php')) {
	//connection file include
	require_once ("include/configconnect.php");
	require_once("include/jgbdb.php");
	if (!isset($crawlthost)) //case old version (before 150)
	{
		$crawlthost = $host;
		$crawltuser = $user;
		$crawltpassword = $password;
		$crawltdb = $db;
		$crawltlang = $lang;
		$crawltpublic = 0;
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
	} else {
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
		$sqlconfig = "SELECT * FROM crawlt_config";
		$nbrresult = db_query($sqlconfig, $connexion);
		if ($nbrresult->num_rows >= 1) {
			$ligne = mysqli_fetch_assoc($nbrresult);
			$times = $ligne['timeshift'];
			$crawltpublic = $ligne['public'];
			$crawltmail = $ligne['mail'];
			$crawltlastday = $ligne['datelastmail'];
			$crawltdest = $ligne['addressmail'];
			$crawltlang = $ligne['lang'];
			$version = $ligne['version'];
			if ($version > 160) {
				$firstdayweek = $ligne['firstdayweek'];
			}
			if ($version > 171) {
				$datecleaning = $ligne['datelastcleaning'];
			}
			if ($version > 210) {
				$rowdisplay = $ligne['rowdisplay'];
				$order = $ligne['orderdisplay'];
			} else {
				$rowdisplay = 30;
				$order = 0;
			}
			if ($version > 220) {
				$crawltmailishtml = $ligne['typemail'];
				$crawltcharset = $ligne['typecharset'];
			} else {
				$crawltmailishtml = 1;
				$crawltcharset = 1;
			}
			if ($version > 281) {
				$crawltblockattack = $ligne['blockattack'];
				$crawltsessionid = $ligne['sessionid'];
				$crawltincludeparameter = $ligne['includeparameter'];
			} else {
				$crawltblockattack = 0;
				$crawltsessionid = 0;
				$crawltincludeparameter = 1;
			}
		}
	}
	$installed = 1;
} else {
	// New installation
	$installed = 0;
	$crawltcharset = 1;
}

// Needs to be included AFTER reading settings since post may set values that are not yet set.
// TODO: Maybe this should also be processed from within the settings class!
include ("include/post.php");

//for the install we need to give a value to $times
if (!isset($times)) {
	$times = 0;
}

if ($installed) {
	if ($crawltcharset != 1) {
		$crawltlang = $crawltlang . "iso";
	}
}

require_once ("include/listlang.php");
require_once ("include/functions.php");

if ($installed) {
	mysqli_close($connexion);
}

//language file include
if (file_exists("language/" . $crawltlang . ".php") && in_array($crawltlang, $listlangcrawlt)) {
	require_once ("language/" . $crawltlang . ".php");
} else {
	exit('<h1>Language file " . $crawltlang . " missing!</h1>');
}

// session start 'crawlt'
if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}

//if already install
if ($installed && $navig != 15) {
	if ($navig == 0) {
		$main = ("include/display-dashboard.php");
	} elseif ($navig == 1) {
		$main = ("include/display-all-crawlers.php");
	} elseif ($navig == 2) {
		$main = ("include/display-one-crawler.php");
	} elseif ($navig == 3) {
		$main = ("include/display-all-pages.php");
	} elseif ($navig == 4) {
		$main = ("include/display-one-page.php");
	} elseif ($navig == 5) {
		$main = ("include/search.php");
	} elseif ($navig == 6) {
		$main = ("include/admin.php");
	} elseif ($navig == 7) {
		$main = ("include/index.htm"); // to avoid notice error in Apache logs
		session_destroy();
		header("Location:index.php");
		exit;
	} elseif ($navig == 8) {
		$main = ("include/display-crawlers-info.php");
	} elseif ($navig == 10) {
		$main = ("include/updateurl.php");
	} elseif ($navig == 11) {
		$main = ("include/display-seo.php");
	} elseif ($navig == 12) {
		$main = ("include/display-keyword.php");
	} elseif ($navig == 13) {
		$main = ("include/display-entrypage.php");
	} elseif ($navig == 14) {
		$main = ("include/display-one-entrypage.php");
	}
	// 15 is used for installation
	elseif ($navig == 16) {
		$main = ("include/display-one-keyword.php");
	} elseif ($navig == 17) {
		$main = ("include/display-hacking.php");
	} elseif ($navig == 18) {
		$main = ("include/display-hacking-xss.php");
	} elseif ($navig == 19) {
		$main = ("include/display-hacking-sql.php");
	} elseif ($navig == 20) {
		$main = ("include/display-visitors.php");
	} elseif ($navig == 21) {
		$main = ("include/display-pages-visitors.php");
	} elseif ($navig == 22) {
		$main = ("include/display-errors.php");
	} elseif ($navig == 23) {
		$main = ("include/display-summary.php");
	} else {
		$main = ("include/display-dashboard.php");
	}
	//  IF NO SESSION LOGIN
	if (!isset($_SESSION['userlogin'])) {
		if ($crawltpublic == 1 && $navig != 6 && $logitself != 1) {
			//case free access to the stats
			if (!isset($_SESSION['rightsite'])) {
				//clear the cache folder at the first entry on crawltrack to avoid to have it oversized
				empty_cache('cache/');
			}
			// session start 'crawlt'
			if (!isset($_SESSION)) {
				session_name('crawlt');
				session_start();
				$_SESSION['rightsite'] = "0";
			} else {
				$_SESSION['rightsite'] = "0";
			}
			//test to see if version is up-to-date
			if (!isset($version)) {
				$version = 100;
			}
			if ($version == $versionid) {
				include ("include/nocache.php");
				//installation is up-to-date, display stats
				include ("include/header.php");
				include ("$main");
				include ("include/footer.php");
			} else {
				//update the installation
				include ("include/header.php");
				include ("include/updatecrawltrack.php");
				include ("include/footer.php");
			}
		} else {
			//get values
			if (isset($_POST['userlogin'])) {
				$userlogin = htmlentities($_POST['userlogin']);
			} else {
				$userlogin = '';
			}
			if (isset($_POST['userpass'])) {
				$userpass = htmlentities($_POST['userpass']);
			} else {
				$userpass = '';
			}
			//access form
			include ("include/header.php");
			echo "<div class=\"content\">\n";

			if ($crawltpublic == 1 && $logitself != 1) {
				echo "<h1>" . $language['admin_protected'] . "</h1>\n";
			} else {
				echo "<h1>" . $language['restrited_access'] . "</h1>\n";
			}
			
			if ($nocookie==1) {
			echo "<div class=\"alert2\">".$language['no_cookie']."</div>\n";
			}
			
			
			echo "<h2>" . $language['enter_login'] . "</h2>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"php/login.php\" method=\"POST\" name=\"login\" >\n";
			echo "<table align=\"left\" width=\"400px\">\n";
			echo "<tr>\n";
			echo "<td >" . $language['login'] . "&nbsp;<input name='userlogin' value='$userlogin' type='text' maxlength='20' size='20'/></td></tr>\n";
			echo "<tr><td></td></tr>\n";
			echo "<tr><td>" . $language['password'] . "&nbsp;<input name='userpass'  value='$userpass' type='password' maxlength='20' size='20'/></td</tr>\n";
			if (isset($lang)) {
				echo "<input type=\"hidden\" name ='lang' value='$lang'>\n";
			} else {
				echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
			}
			echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"$validform\">\n";
			echo "<input type=\"hidden\" name ='displayall' value=\"$displayall\">\n";
			echo "<input type=\"hidden\" name ='logitself' value=\"$logitself\">\n";
			echo "<tr><td><input name='ok' type='submit'  value='OK' size='20'></td></tr>\n";
			echo "</table></form>\n";
			echo "<script type=\"text/javascript\"> document.forms[\"login\"].elements[\"userlogin\"].focus()</script>\n";
			echo "<br><br><br><br><br>\n";
			echo "<div align='center'><br><iframe name=\"I1\" src=\"http://www.crawltrack.net/news/rel.php?r=".$versionid."&p=".PHP_VERSION."&l=".$crawltlang."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\0\" frameborder=\"0\" width=\"400px\" height=\"20px\"></iframe></div><br><br>\n";
			echo "</div>\n";
			include ("include/footer.php");
		}
	} else {
		//check token
		//Thanks to François Lasselin (http://blog.nalis.fr/index.php?post/2009/09/28/Securisation-stateless-PHP-avec-un-jeton-de-session-%28token%29-protection-CSRF-en-PHP)
		$validity_time = 1800;
		$token_clair= $secret_key.$_SERVER['HTTP_HOST'].$_SERVER['HTTP_USER_AGENT'];
		$token = hash('sha256', $token_clair.$_COOKIE["session_informations"]);
		if(strcmp($_COOKIE["session_token"], $token)==0)
			{
			list($date, $user) = preg_split('[-]', $_COOKIE["session_informations"]);
			if($date+ $validity_time>time() AND $date <=time())
				{
					//test to see if version is up-to-date
					if (!isset($version)) {
						$version = 100;
					}
					if ($version == $versionid) {
						include ("include/nocache.php");
						//installation is up-to-date, display stats
						include ("include/header.php");
						include ("$main");
						include ("include/footer.php");
					} else {
						//update the installation
						include ("include/header.php");
						include ("include/updatecrawltrack.php");
						include ("include/footer.php");
					}
				}
			else
				{
				unset($_SESSION['userlogin']);
				$crawlencode = urlencode($crawler);
				header("Location: index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall");
				exit;
				}
			}
		else
			{
			unset($_SESSION['userlogin']);
			$crawlencode = urlencode($crawler);
			header("Location: index.php?navig=$navig&period=$period&site=$site&crawler=$crawlencode&graphpos=$graphpos&displayall=$displayall");
			exit;
			}
	}
} else {
	//display install
	$navig = '';
	include ("include/header.php");
	include ("include/install.php");
	include ("include/footer.php");
}
if ($navig == 0 || $navig == 1 || $navig == 2 || $navig == 3 || $navig == 4 || $navig == 8 || $navig == 11 || $navig == 12 || $navig == 13 || $navig == 14 || $navig == 16 || $navig == 17 || $navig == 18 || $navig == 19 || $navig == 20 || $navig == 21 || $navig == 22 || $navig == 23) {
	//close the cache function
	close();
} else {
	echo "<div class='smalltextgrey'>" . $numbquery . " mysql queries           " . getTime() . " s</div>";
	echo "</body>\n";
	echo "</html>\n";
}
?>
