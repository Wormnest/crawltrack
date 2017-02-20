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
	/*
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
		$settings->ispublic = 0;
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
	} else {
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
		$sqlconfig = "SELECT * FROM crawlt_config";
		$nbrresult = db_query($sqlconfig, $connexion);
		if ($nbrresult->num_rows >= 1) {
			$ligne = mysqli_fetch_assoc($nbrresult);
			$times = $ligne['timeshift'];
			$settings->ispublic = $ligne['public'];
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
	}*/
	require_once("include/db.class.php");
	require_once("include/settings.class.php");
	$db = new ctDb();
	$settings = new ctSettings($db);
	$installed = 1;
} else {
	// New installation
	$settings = new ctSettings(null);
	$installed = 0;
	//$crawltcharset = 1;
}

// Needs to be included AFTER reading settings since post may set values that are not yet set.
// TODO: Maybe this should also be processed from within the settings class!
// Now done inside settings class, except for installing
//include ("include/post.php");

// period calculation needs a valid $settings->timediff
/* Already done in settings class
if (!isset($settings->timediff)) {
	$settings->timediff = 0;
}*/

// TODO: First part of periodcalculation can probably be moved to settings initialization.
// TODO: The rest should be made into a function in settings classs and called only when needed.
include("include/periodcalculation.php");

/* Since we will need the connection again soon after in most cases we will not close it.
if ($installed) {
	$db->close();
} */

require_once("include/listlang.php");
require_once("include/functions.php");

// TODO: Setting language should be done in settings class.
// Select language filename
if ($settings->useutf8 != 1) {
	$settings->language = $settings->language . "iso";
}

//language file include
if (file_exists("language/" . $settings->language . ".php") && in_array($settings->language, $listlangcrawlt)) {
	require_once ("language/" . $settings->language . ".php");
} else {
	exit('<h1>Language file " . $settings->language . " missing!</h1>');
}

// session start 'crawlt'
if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}

// Check if CrawlTrack is already installed.
if ($installed && $settings->navig != 15) {
	// Check which page we should show.
	// TODO: Maybe replace this with a switch/case statement.
	if ($settings->navig == 0) {
		$main = ("include/display-dashboard.php");
	} elseif ($settings->navig == 1) {
		$main = ("include/display-all-crawlers.php");
	} elseif ($settings->navig == 2) {
		$main = ("include/display-one-crawler.php");
	} elseif ($settings->navig == 3) {
		$main = ("include/display-all-pages.php");
	} elseif ($settings->navig == 4) {
		$main = ("include/display-one-page.php");
	} elseif ($settings->navig == 5) {
		$main = ("include/search.php");
	} elseif ($settings->navig == 6) {
		$main = ("include/admin.php");
	} elseif ($settings->navig == 7) {
		$main = ("include/index.htm"); // to avoid notice error in Apache logs
		session_destroy();
		header("Location:index.php");
		exit;
	} elseif ($settings->navig == 8) {
		$main = ("include/display-crawlers-info.php");
	} elseif ($settings->navig == 10) {
		$main = ("include/updateurl.php");
	} elseif ($settings->navig == 11) {
		$main = ("include/display-seo.php");
	} elseif ($settings->navig == 12) {
		$main = ("include/display-keyword.php");
	} elseif ($settings->navig == 13) {
		$main = ("include/display-entrypage.php");
	} elseif ($settings->navig == 14) {
		$main = ("include/display-one-entrypage.php");
	}
	// 15 is used for installation
	elseif ($settings->navig == 16) {
		$main = ("include/display-one-keyword.php");
	} elseif ($settings->navig == 17) {
		$main = ("include/display-hacking.php");
	} elseif ($settings->navig == 18) {
		$main = ("include/display-hacking-xss.php");
	} elseif ($settings->navig == 19) {
		$main = ("include/display-hacking-sql.php");
	} elseif ($settings->navig == 20) {
		$main = ("include/display-visitors.php");
	} elseif ($settings->navig == 21) {
		$main = ("include/display-pages-visitors.php");
	} elseif ($settings->navig == 22) {
		$main = ("include/display-errors.php");
	} elseif ($settings->navig == 23) {
		$main = ("include/display-summary.php");
	} else {
		$main = ("include/display-dashboard.php");
	}
	//  IF NO SESSION LOGIN
	if (!isset($_SESSION['userlogin'])) {
		if ($settings->ispublic == 1 && $settings->navig != 6 && $settings->logitself != 1) {
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
			if (!isset($settings->version)) {
				$version = 100;
			}
			if ($settings->version == $versionid) {
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

			if ($settings->ispublic == 1 && $settings->logitself != 1) {
				echo "<h1>" . $language['admin_protected'] . "</h1>\n";
			} else {
				echo "<h1>" . $language['restrited_access'] . "</h1>\n";
			}
			
			if ($settings->nocookie==1) {
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
			echo "<input type=\"hidden\" name ='lang' value='$settings->language'>\n";
			echo "<input type=\"hidden\" name ='graphpos' value=\"$settings->graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$settings->navig\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"$settings->validform\">\n";
			echo "<input type=\"hidden\" name ='displayall' value=\"$settings->displayall\">\n";
			echo "<input type=\"hidden\" name ='logitself' value=\"$settings->logitself\">\n";
			echo "<tr><td><input name='ok' type='submit'  value='OK' size='20'></td></tr>\n";
			echo "</table></form>\n";
			echo "<script type=\"text/javascript\"> document.forms[\"login\"].elements[\"userlogin\"].focus()</script>\n";
			echo "<br><br><br><br><br>\n";
			echo "<div align='center'><br><iframe name=\"I1\" src=\"http://www.crawltrack.net/news/rel.php?r=".$versionid."&p=".PHP_VERSION."&l=".$settings->language."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\0\" frameborder=\"0\" width=\"400px\" height=\"20px\"></iframe></div><br><br>\n";
			echo "</div>\n";
			include ("include/footer.php");
		}
	} else {
		//check token
		//Thanks to François Lasselin (http://blog.nalis.fr/index.php?post/2009/09/28/Securisation-stateless-PHP-avec-un-jeton-de-session-%28token%29-protection-CSRF-en-PHP)
		$validity_time = 1800;
		$token_clair= $settings->secret_key.$_SERVER['HTTP_HOST'].$_SERVER['HTTP_USER_AGENT'];
		$token = hash('sha256', $token_clair.$_COOKIE["session_informations"]);
		if(strcmp($_COOKIE["session_token"], $token)==0)
			{
			list($date, $user) = preg_split('[-]', $_COOKIE["session_informations"]);
			if($date+ $validity_time>time() AND $date <=time())
				{
					//test to see if version is up-to-date
					if (!isset($settings->version)) {
						$settings->version = 100;
					}
					if ($settings->version == $versionid) {
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
				// Session expired
				unset($_SESSION['userlogin']);
				$crawlencode = urlencode($settings->crawler);
				header("Location: index.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&graphpos=$settings->graphpos&displayall=$settings->displayall");
				exit;
				}
			}
		else
			{
			// Incorrect session token
			unset($_SESSION['userlogin']);
			$crawlencode = urlencode($settings->crawler);
			header("Location: index.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&graphpos=$settings->graphpos&displayall=$settings->displayall");
			exit;
			}
	}
} else {
	//display install
	$settings->navig = '';
	include ("include/header.php");
	include ("include/install.php");
	include ("include/footer.php");
}
if ($settings->navig == 0 || $settings->navig == 1 || $settings->navig == 2 || $settings->navig == 3 || $settings->navig == 4 || $settings->navig == 8 || $settings->navig == 11 || $settings->navig == 12 || $settings->navig == 13 || $settings->navig == 14 || $settings->navig == 16 || $settings->navig == 17 || $settings->navig == 18 || $settings->navig == 19 || $settings->navig == 20 || $settings->navig == 21 || $settings->navig == 22 || $settings->navig == 23) {
	//close the cache function
	// TODO: Rename to close_cache and use a class for it.
	close();
} else {
	echo "<div class='smalltextgrey'>" . $numbquery . " mysql queries           " . getTime() . " s</div>";
	echo "</body>\n";
	echo "</html>\n";
}
?>
